<?php

namespace App\Console\Commands;

use App\Http\Controllers\MailController;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\Transaction;
use App\Services\MpesaB2C;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckAuctionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:auction-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //function that will be checking the start and endtimes of auctions and updating their status if need be
        //once an auction is closed the function will also notify the winner and the seller!!

        //get all auctions that are pending/open...we have no need to check closed auctions
        $auctions = Auction::where('status', 'pending')
                            ->orWhere('status', 'open')
                            ->orderBy('id', 'desc')
                            ->take(5)
                            ->get();

        if (count($auctions) > 0) {
            foreach ($auctions as $auction) {
                //lets start with the ones that are pending first
                if ($auction->status == 'pending') {
                    //check if the auction start time is less than the current time
                    // dd($auction->start_time , now());
                    if ($auction->start_time < now()) {
                        //update the status to open
                        $auction->status = 'open';
                        $auction->save();
                        Log::info("Auction " . $auction->id . " is now open");
                    } else {
                        Log::info("Auction " . $auction->id . " is still pending");
                    }
                } elseif ($auction->status == 'open') {
                    //check if the auction end time is less than the current time
                    if ($auction->end_time < now()) {
                        //update the status to closed
                        $auction->status = 'closed';
                        $auction->save();
                        Log::info("Auction " . $auction->id . " is now closed");

                        //find the winning bid
                        $winning_bids = Bid::where('auction_id', $auction->id)
                            ->where('status', 'HIGHEST')
                            //use the get() because there can be multiple highest bids in the same auction since there are different cars
                            ->get();

                        if (count($winning_bids) > 0) {
                            foreach ($winning_bids as $winning_bid) {
                                $car = $winning_bid->car;
                                //update the status of the car to sold so that it cant be used in another auction
                                $car->status = 'SOLD';
                                $car->save();


                                //NOTIFY THE PARTIES INVOLVED


                                Log::info(">>>>>>>>>>>>Auction " . $auction->id . " has a winning bid " . $winning_bid->id);

                                //get the vendor
                                $vendor = $car->vendor;
                                $vendor_email = $vendor->email;
                                $mailController = new MailController();
                                Log::info(">>>>>>>>>>>>Sending email to vendor " . $vendor_email);
                                $res = $mailController->sendVendorWinner($auction, $car, $vendor, $vendor_email, $winning_bid);


                                //get the user
                                $user = $winning_bid->user;
                                $email = $user->email;
                                $mailController = new MailController();
                                Log::info(">>>>>>>>>>>>Sending email to customer " . $email);
                                $res = $mailController->sendUserWinner($auction, $car, $user, $email, $winning_bid);


                                //transfer the money to the vendor
                                $amount = $winning_bid->amount;
                                $this->transfer_money_to_vendor($vendor, $amount, $auction, $car, $winning_bid);
                            }
                        } else {
                            Log::info("No winning bid found for auction " . $auction->id);
                        }
                    } else {
                        Log::info("Auction " . $auction->id . " is still open");
                    }
                }
            }
        } else {
            Log::info("No auctions to check");
        }
    }

    public function transfer_money_to_vendor($vendor, $amount, $auction, $car, $winning_bid)
    {
        //get the commission
        $commission = 0.05 * $amount;
        $vendor_amount = $amount - $commission;

        //create a credit transaction for the vendor(mpesa b2c)
        //NB: to calculate the total revenue that the system will make, we will minus all credit transactions from the total amount of money that has been deposited by the users (transactions with a status of 'PAID')

        $b2c = new MpesaB2C();
        $res = $b2c->b2c($vendor->phone_number, $vendor_amount);


        $response_code = $res->ResponseCode;
        $response_description = $res->ResponseDescription;

        if ($response_code == 0) {
            //create a credit transaction for the vendor
            $transaction = new Transaction();
            $transaction->auction_id = $auction->id;
            $transaction->bid_id = null;
            $transaction->user_id = $vendor->id;
            $transaction->car_id = $car->id;
            $transaction->transaction_details = json_encode('Payment for car sale');
            $transaction->amount = $vendor_amount;
            $transaction->payment_status = 'PAID TO VENDOR';
            $transaction->merchant_request_id = null;
            $transaction->callback = null;
            $transaction->dr_cr = 'CR';
            $transaction->transaction_date = now();
            $transaction->save();

        } else {
            //log the error
            Log::info("Error transferring money to vendor: " . $response_description);
        }
    }
}
