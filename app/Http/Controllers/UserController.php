<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hashids\Hashids;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function register(Request $request)
    {
        //use this function to register new customers when they try to make a bid and then send them an email with their account details
        // dd($request->all());


        //verify the request first
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'bid_amount' => 'required',
            'auction_id' => 'required',
        ]);


       

        //create a string of random characters for the password
        $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 16 );

        //create a random id btwn 1 an 1000000 that will be used for confirmation
        $confirmation_id = rand(1, 10000000000);

        $hash = new Hashids(env('APP_KEY'),  20);
        $hashed = $hash->encode($confirmation_id);


        // dd($hashed, $confirmation_id);


        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->password = bcrypt($password);
        $user->confirmation_id = $confirmation_id;
        
        //assign the role of customer
        $user->assignRole('customer');

        //save the user
        // $user->save();

        //send the user an email with their account details
        $email_details = [
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => $password,
            'confirmation_string' => $hashed
        ];

        // dd($email_details, $user);
        //call the MailController to send the email
        $mail_controller = new MailController();
        $res = $mail_controller->sendAccountConfirmation($email_details);
        dd($res);





    }
}
