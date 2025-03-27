<?php

namespace App\Http\Controllers;

use App\Mail\BidPlaced;
use App\Models\Bid;
use App\Models\Car;
use App\Models\Transaction;
use App\Services\MpesaSTKC2B;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BidController extends Controller
{
    public function place_bid($auction_id, $bid_amount, $user_id, $car_id, $phone_number)
    {

        //check that there is no higher bid than the one being being placed now
        $previous_highest_bid = Bid::where('auction_id', $auction_id)->where('car_id', $car_id)->where('status', 'HIGHEST')->first();

        $car = Car::find($car_id);

        //make sure that the bid placed is higher than the reserve price
        $reserve_price = $car->price;
        if ($bid_amount < $reserve_price) {
            return [
                'success' => false,
                'message' => 'The bid amount is lower than the reserve price which is '. number_format($car->price) .' Please place a higher bid',
            ];
        }
        

        if (!$previous_highest_bid) {
            $previous_highest_bid_amount = 0;
        } else {
            $previous_highest_bid_amount = $previous_highest_bid->amount;
        }


        //also ensure that the previous highest bid was not placed by the same user
        //UNCOMMENT TO DISABLE SELF OUTBIDDING
        // if ($previous_highest_bid && $previous_highest_bid->user_id == $user_id) {
        //     return [
        //         'success' => false,
        //         'message' => 'You have already placed the highest bid for this car. You cannot outbid yourself.',
        //     ];
        // }

        if ($previous_highest_bid_amount > $bid_amount) {
            return [
                'success' => false,
                'message' => 'There is a higher bid than the one being placed. Please place a higher bid.',
            ];
        } elseif ($previous_highest_bid_amount == $bid_amount) {
                return [
                    'success' => false,
                    'message' => 'The bid amount is the same as the current highest bid. Please place a higher bid.',
                ];
            
        } else {

            //check if the user has placed a bid before so that if they want to raise the bid, they can do so
            //for this, we would have to check the bid they had placed and get the difference between the previous bid and the bid they want to place now
            $previous_user_bid = Bid::where('auction_id', $auction_id)->where('car_id', $car_id)->where('user_id', $user_id)->orderBy('id', 'desc')->first();
            if ($previous_user_bid) {
                $previous_user_bid_amount = $previous_user_bid->amount;
                $difference = $bid_amount - $previous_user_bid_amount;
                if ($difference > 0) {
                    // dd($previous_user_bid, $difference);
                    $res = $this->update_bid($previous_user_bid, $bid_amount, $difference, $auction_id, $car_id, $user_id, $phone_number);


                    $transaction = Transaction::find($res['transaction_id']);
                }
            } else {

                $res = $this->initiateBid($phone_number, $bid_amount, $auction_id, $user_id, $car_id);
                // dd($res);

                if (isset($res['ResponseCode']) && $res['ResponseCode'] == '0') {
                    // dd("here");
                    //create a transaction record and set the status to pending payment and set the decoded response message as the transaction details
                    $transaction = new Transaction();
                    $transaction->auction_id = $auction_id;
                    $transaction->user_id = $user_id;
                    $transaction->bid_id = null;
                    $transaction->car_id = $car_id;
                    $transaction->amount = $bid_amount;
                    $transaction->payment_status = 'PENDING PAYMENT';
                    $transaction->transaction_details = json_encode($res);
                    $transaction->merchant_request_id = $res['MerchantRequestID'];
                    $transaction->transaction_date = now();
                    $transaction->save();

                    $transaction = Transaction::find($transaction->id);
                }
                // dd($res, "here");

            }
        }

        if ($res) {
            return [
                'success' => true,
                'message' => 'STK push initiated successfully. Please wait for the payment prompt on your phone.',
                'transaction_id' => $transaction->id,
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to initiate STK push. Please try again later.',
            ];
        }
    }

    public function callback(Request $request)
    {
        Log::info($request->all());
        // dd($request->all(), "here");

        $request = json_decode($request->getContent());
        //get the merchant request idfrom the request
        $merchant_request_id = $request->Body->stkCallback->MerchantRequestID;
        // $resultCode = $request->Body->stkCallback->ResultCode;
        // dd($request, $merchant_request_id, $resultCode, "here");
        // $checkout_request_id = $request->Body->stkCallback->CheckoutRequestID;


        if(env('MPESA_SIMULATE_SUCCESS') == 'true'){
            $resultCode = 0;
        } else {
            $resultCode = $request->Body->stkCallback->ResultCode;
        }
        if ($resultCode == 0) {
            // dd("resultCode is 0");
            //look for a transaction with the merchant request id
            $transaction = Transaction::where('merchant_request_id', $merchant_request_id)->first();
            if ($transaction) {


                $auction_id = $transaction->auction_id;
                $user_id = $transaction->user_id;
                $car_id = $transaction->car_id;

                //get all tranactions with a similar user_id, auction_id and car_id and sum up the amounts to get the bid amount
                $transactions = Transaction::where('auction_id', $auction_id)
                    ->where('user_id', $user_id)
                    ->where('car_id', $car_id)
                    ->whereNot('payment_status', 'like', '%FAILED%')
                    ->whereNot("dr_cr", "CR")
                    ->get();
                if (count($transactions) > 0) {

                    $bid_amount = 0;
                    foreach ($transactions as $t) {
                        $t->payment_status = 'PAID';
                        $t->save();
                        $bid_amount += $t->amount;
                    }

                } else {
                    $bid_amount = $transaction->amount;
                }


                $previous_highest_bid = Bid::where('auction_id', $auction_id)->where('car_id', $car_id)->where('status', 'HIGHEST')->first();


                //update the transaction status to paid
                $transaction->payment_status = 'PAID';
                // $transaction->save();

                //create the bid record
                //create a new bid record
                $bid = new Bid();
                $bid->auction_id = $auction_id;
                $bid->amount = $bid_amount;
                $bid->user_id = $user_id;
                $bid->car_id = $car_id;
                $bid->status = 'HIGHEST';
                $bid->save();

                $bid = Bid::find($bid->id);

                $transaction->bid_id = $bid->id;
                $transaction->save();




                if ($previous_highest_bid) {

                    $previous_highest_bid->status = 'OUTBID';
                    $previous_highest_bid->save();

                    $prev_highest_bid_user_id = $previous_highest_bid->user_id;

                    if($prev_highest_bid_user_id != $user_id){
                        $transaction = Transaction::where('auction_id', $auction_id)
                            ->where('car_id', $car_id)
                            ->where('user_id', $prev_highest_bid_user_id)
                            ->where('payment_status', 'PAID')
                            ->whereNot("dr_cr", "CR")
                            ->get();
                        foreach ($transaction as $transaction) {
                            $status = $transaction->payment_status;
                            $transaction->payment_status = $status . '|PENDING REFUND';
                            $transaction->save();
                        }
                        
                    }

                }


                //change the status of the previous highest bid transaction to "PENDING REFUND"




                //UNCOMMENT TO SEND EMAIL NOTIFICATION AFTER BID IS PLACED
                // $mail_controller = new MailController();
                // $res = $mail_controller->sendBidPlaced($bid);
            } else {
                Log::info('Transaction not found');
            }
        } else {
            // dd("resultCode is not 0");

            //update the transaction status to failed
            $transaction = Transaction::where('merchant_request_id', $merchant_request_id)->first();
            $status = $transaction->payment_status;
            $transaction->payment_status = 'FAILED';
            $transaction->save();

            Log::info("STK Push not paid for...");
        }

        return json_encode([
            "message" => "Callback received and processed successfully",
        ]);
    }


    public function poll_transaction_status($id)
    {
        //get the transaction_id from the request
        $transaction_id = $id;

        //fetch it from the db
        $transaction = Transaction::find($transaction_id);
        //get the status
        $status = $transaction->payment_status;
        // dd($status, strpos($status, "FAILED"));


        if (strpos($status, "PAID") !== false) {
            return json_encode([
                'status' => "success",
            ]);
        } elseif (strpos($status, "FAILED") !== false) {
            return json_encode([
                'status' => "failed",
            ]);
        } else {
            return json_encode([
                'status' => "pending",
            ]);
        }
    }


    public function update_bid($previous_user_bid, $bid_amount, $difference, $auction_id, $car_id, $user_id, $phone_number)
    {
        //this function will update a previous bid to outbid the current highest amount

        $res = $this->initiateBid($phone_number, $difference, $auction_id, $user_id, $car_id);

        if (isset($res['ResponseCode']) && $res['ResponseCode'] == '0') {
            // dd("here");

            //first, get all initial transactions for the bid for this user and set the status to paid for all...so that none will be read as pending refund
            //get all transactions regarding this bid
            // $transactions = Transaction::where('user_id', $user_id)
            //                             ->where('auction_id', $auction_id)
            //                             ->where('car_id', $car_id)
            //                             ->where('payment_status', 'like', '%REFUND%')
            //                              ->whereNot("dr_cr", "CR")
            //                             ->get();
            // foreach ($transactions as $transaction) {
            //     $transaction->payment_status = 'PAID';
            //     $transaction->save();
            // }

            //create a new transaction for the difference
            $transaction = new Transaction();
            $transaction->auction_id = $auction_id;
            $transaction->user_id = $user_id;
            $transaction->bid_id = $previous_user_bid->id;
            $transaction->car_id = $car_id;
            $transaction->amount = $difference;
            $transaction->payment_status = 'PENDING PAYMENT';
            $transaction->transaction_details = json_encode($res);
            $transaction->merchant_request_id = $res['MerchantRequestID'];
            $transaction->transaction_date = now();
            $transaction->save();

            return [
                'success' => true,
                'message' => 'STK push initiated successfully. Please wait for the payment prompt on your phone.',
                'transaction_id' => $transaction->id,
            ];
        }
        // dd($res, "here");

    }


    public function initiateBid($phone_number, $bid_amount, $auction_id, $user_id, $car_id)
    {
        //initiate stk push to customer 
        $mpesa_service = new MpesaSTKC2B();
        $credentials = [
            'consumerKey' => env('MPESA_CONSUMER_KEY'),
            'consumerSecret' => env('MPESA_CONSUMER_SECRET'),
            'shortCode' => env('MPESA_SHORTCODE'),
            'passkey' => env('MPESA_PASSKEY'),
            'accountReference' => 'Bid Payment',
        ];
        $callbackUrl = env('MPESA_CALLBACK_URL');
        $env = env('MPESA_ENV');
        $res = $mpesa_service->stk_push($phone_number, $bid_amount, $auction_id, $credentials, $callbackUrl, $env);

        return $res;
    }
}
