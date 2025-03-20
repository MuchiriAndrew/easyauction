<?php

namespace App\Http\Controllers;

use App\Mail\BidPlaced;
use App\Models\Bid;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class BidController extends Controller
{
    public function place_bid($auction_id, $bid_amount, $user_id, $car_id) {
        //mpesa logic will go here



        // dd($auction_id, $bid_amount, $user_id, $car_id);


        //THIS IS SIMULATION OF A SUCCESS PAYMENT OF THE HIGHEST BID IN AN OPEN AUCTION

        //check that there is no higher bid than the one being being placed now
        $previous_highest_bid = Bid::where('auction_id', $auction_id)->where('car_id', $car_id)->where('status', 'HIGHEST')->first();
        $previous_highest_bid_amount = $previous_highest_bid->amount;
        // dd($previous_highest_bid);

        if($previous_highest_bid_amount >= $bid_amount) {
            return [
                'success' => false,
                'message' => 'There is a higher bid than the one being placed. Please place a higher bid.',
            ];
        }




        //create a new bid record
        $bid = new Bid();
        $bid->auction_id = $auction_id;
        $bid->amount = $bid_amount;
        $bid->user_id = $user_id;
        $bid->car_id = $car_id;
        $bid->status = 'HIGHEST';
        $bid->save();


        //create a new transaction here
        $transaction = new Transaction();
        $transaction->auction_id = $auction_id;
        $transaction->user_id = $user_id;
        $transaction->amount = $bid_amount;
        $transaction->payment_status = 'PAID';
        $transaction->transaction_date = now();
        $transaction->save();

        //update the previous bid status to outbid
        // dd($previous_highest_bid);
        $previous_highest_bid->status = 'OUTBID';
        $previous_highest_bid->save();


        //send a confirmation email to the user
        // Mail::to($user->email)->send(new BidPlaced($bid));
        $mail_controller = new MailController();
        $res = $mail_controller->sendBidPlaced($bid);
        // dd($res);

        if($res) {
            return [
                'success' => true,  
                'message' => 'Bid placed successfully. Kindly wait for the auction to end to see if you have won the car or place a higher bid incase you have lost another bidder bids higher than you.',
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Bid placement failed. Please try again.',
            ];
        }
    }
}
