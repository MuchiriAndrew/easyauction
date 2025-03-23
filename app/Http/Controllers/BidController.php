<?php

namespace App\Http\Controllers;

use App\Mail\BidPlaced;
use App\Models\Bid;
use App\Models\Transaction;
use App\Services\MpesaSTKC2B;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class BidController extends Controller
{
    public function place_bid($auction_id, $bid_amount, $user_id, $car_id, $phone_number) {
        
        //check that there is no higher bid than the one being being placed now
        $previous_highest_bid = Bid::where('auction_id', $auction_id)->where('car_id', $car_id)->where('status', 'HIGHEST')->first();

        if(!$previous_highest_bid) {
            $previous_highest_bid_amount = 0;
        } else {
            $previous_highest_bid_amount = $previous_highest_bid->amount;
        }

        if($previous_highest_bid_amount >= $bid_amount) {
            return [
                'success' => false,
                'message' => 'There is a higher bid than the one being placed. Please place a higher bid.',
            ];
        } else {

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


            
            if(isset($res['ResponseCode']) && $res['ResponseCode'] == '0') {
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
                $transaction->transaction_date = now();
                $transaction->save();
                
            }
            // dd($res, "here");

        }

        if($res) {
            return [
                'success' => true,  
                'message' => 'STK push initiated successfully. Please wait for the payment prompt on your phone.',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to initiate STK push. Please try again later.',
            ];
        }
    }

    public function callback(Request $request) { 
        //get the merchant request idfrom the request
        $merchant_request_id = $request->Body->stkCallback->MerchantRequestID;
        // $checkout_request_id = $request->Body->stkCallback->CheckoutRequestID;


        //look for a transaction with the merchant request id
        $transaction = Transaction::where('merchant_request_id', $merchant_request_id)->first();
        if($transaction) {
            $auction_id = $transaction->auction_id;
            $user_id = $transaction->user_id;
            $bid_amount = $transaction->amount;
            $car_id = $transaction->car_id;



            //update the transaction status to paid
            $transaction->payment_status = 'PAID';
            $transaction->save();

           //create the bid record
           //create a new bid record
           $bid = new Bid();
           $bid->auction_id = $auction_id;
           $bid->amount = $bid_amount;
           $bid->user_id = $user_id;
           $bid->car_id = $car_id;
           $bid->status = 'HIGHEST';
           $bid->save();
        } else {
            Log::info('Transaction not found');
        }
        

    }


    public function confirm_payment($auction_id, $previous_highest_bid, $user_id, $bid_amount, $car_id) {
         


        //create a new transaction here
        // $transaction = new Transaction();
        // $transaction->auction_id = $auction_id;
        // $transaction->user_id = $user_id;
        // $transaction->bid_id = $bid->id;
        // $transaction->amount = $bid_amount;
        // $transaction->payment_status = 'PAID';
        // $transaction->transaction_date = now();
        // $transaction->save();

        //update the previous bid status to outbid
        // dd($previous_highest_bid);
        if($previous_highest_bid) {

        $previous_highest_bid->status = 'OUTBID';
        $previous_highest_bid->save();
        }
        
        
        //change the status of the previous highest bid transaction to "PENDING REFUND"
        $transaction = Transaction::where('bid_id', $previous_highest_bid->id)->first();
        $status = $transaction->payment_status;
        $transaction->payment_status = $status . '|PENDING REFUND';
        $transaction->save();


        $mail_controller = new MailController();
        $res = $mail_controller->sendBidPlaced($bid);
        return $res;
    }

    public function update_bid() {
        //this function will update a previous bid to outbid the current highest amount

    }
}
