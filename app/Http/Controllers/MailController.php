<?php
// filepath: /home/muchiri/myprojects/easyauction-project/app/Http/Controllers/ContactController.php

namespace App\Http\Controllers;

use App\Mail\AccountConfirmation;
use App\Mail\BidPlaced;
use Illuminate\Http\Request;
use App\Mail\ContactForm;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function displayContactForm()
    {
        return view('emails.display-contact-form');
    }
    
    public function sendContactForm(Request $request)
    {
        // dd($request->all());
        $details = [
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
            'subject' => $request->subject
        ];

        Mail::to('kariukia225@gmail.com')->send(new ContactForm($details));

        return back()->with('success', 'Email sent successfully on live!');
    }


    public function sendAccountConfirmation($email_details)
    {
        $email = $email_details['email'];
        $confirmation_string = $email_details['confirmation_string'];

        $app_url = env('APP_URL');
        $link = $app_url . "/confirm-account/$confirmation_string";

        $email_details['link'] = $link;

        // dd($email_details);

        //send the email

        try {
            //uncomment once done with testing
            $res = Mail::to($email)->send(new AccountConfirmation($email_details));

            
            // $res = Mail::to("kariukia225@gmail.com")->send(new AccountConfirmation($email_details));
            return $res;
        } catch (\Exception $e) {
            return back()->with('error', 'Email not sent');
        }
    }

    public function sendEmailVerification($email_details)
    {
        $email = $email_details['email'];
        $confirmation_string = $email_details['confirmation_string'];

        $app_url = env('APP_URL');
        $link = $app_url . "/verify-email/$confirmation_string";

        $email_details['link'] = $link;

        // dd($email_details);

        //send the email

        try {
            //uncomment once done with testing
            $res = Mail::to($email)->send(new AccountConfirmation($email_details));
            
            // $res = Mail::to("kariukia225@gmail.com")->send(new VerifyEmail($email_details));
            return $res;
        } catch (\Exception $e) {
            return back()->with('error', 'Email not sent');
        }
    }

    public function sendBidPlaced($bid)
    {
        $email = $bid->user->email;
        $username = $bid->user->name;
        $car_name = $bid->car->make . ' ' . $bid->car->model;
        $auction_name = $bid->auction->name;
        $bid_amount = $bid->amount;

        $details = [
            'username' => $username,
            'car_name' => $car_name,
            'auction_name' => $auction_name,
            'bid_amount' => $bid_amount
        ];

        $res = Mail::to($email)->send(new BidPlaced($details));
        return $res;
    }
}