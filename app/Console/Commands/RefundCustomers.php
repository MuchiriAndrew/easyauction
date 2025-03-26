<?php

namespace App\Console\Commands;

use App\Models\Auction;
use App\Models\Transaction;
use App\Services\MpesaB2C;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RefundCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refund:customers';

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
        //function will check all transactions with a payment status of PENDING REFUND and refund the customers using a b2c api

        //first query all auctions that are closed
        $closed_auctions = Auction::where('status', 'closed')->get();
        // dd($closed_auctions);

        Log::info(">>>>>>>>>>Found " . count($closed_auctions) . " closed auctions>>>>>>>>>>");

        if (count($closed_auctions) > 0) {
            foreach ($closed_auctions as $auction) {
                //get all the transaction regarding thos auction that have a status like "PENDING REFUND"
                $transactions = Transaction::where('auction_id', $auction->id)
                    ->where('payment_status', "like", '%PENDING REFUND%')
                    ->whereNot('payment_status', "like", '%FAILED%')
                    // ->take(1)
                    ->get();
                    

                if (count($transactions) > 0) {
                    $count = count($transactions);
                    Log::info(">>>>>>>>Refunding $count transactions for auction " . $auction->id . ">>>>>>>> $auction->name");
                    foreach ($transactions as $transaction) {
                        //refund the customer using a b2c api
                        $b2c = new MpesaB2C();
                        $res = $b2c->b2c($transaction->user->phone_number, $transaction->amount);

                        $response_code = $res->ResponseCode;
                        $response_description = $res->ResponseDescription;

                        if ($response_code == 0) {
                            //update the transaction status to "REFUNDED"
                            $transaction->update([
                                'payment_status' => 'REFUNDED'
                            ]);
                            Log::info("Refunded customer: " . $response_description);
                        } else {
                            //log the error
                            Log::info("Error refunding customer: " . $response_description);
                        }
                    }
                } else {
                    Log::info("No transactions to refund for auction " . $auction->id . ">>>>>>>> $auction->name");
                }
            }
        } else {
            Log::info("No closed auctions found");
        }
    }
}
