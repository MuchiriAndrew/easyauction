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
            // 'name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required|digits:10',
            'bid_amount' => 'required',
            'auction_id' => 'required',
            'car_id' => 'required',
        ],
        [
            // 'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'phone_number.required' => 'Phone number is required',
            'bid_amount.required' => 'Bid amount is required',
            'auction_id.required' => 'Auction id is required',
            'car_id.required' => 'Car id is required',
        ]);




        //check if there is a user already logged in and if so, use their details
        //if no user is logged, in, check the email if it is already in the database
        //if the email is in the database, direct the user to login
        //if the email is not in the database, create a new user
        $bid_amount = $request->bid_amount;
        $bid_amount = str_replace(',', '', $bid_amount);
        $phone_number = $request->phone_number;

        $user = auth()->user();
        if ($user) {
            
            //check if the credentials put in are the same as the user's credentials
            if ($user->email != $request->email) {
                return redirect()->back()->with('error', 'You are already logged in as another user. Please use your email to place a bid, or log in with the correct email');
            }
            
            $user_id = $user->id;
            $auction_id = $request->auction_id;
            $car_id = $request->car_id;
            $bid_controller = new BidController();
            $res = $bid_controller->place_bid($auction_id, $bid_amount, $user_id, $car_id, $phone_number);
            // dd($res);

            if($res['success']) {
                $transaction_id = $res['transaction_id'];
                return redirect("/payment-processing/$transaction_id")->with('success', $res['message']);
                // return redirect()->back()->with('success', $res['message']);

            } else {
                return redirect()->back()->with('error', $res['message']);
            }


        } else {
            //check if the email is already in the database
            $user = User::where('email', $request->email)->first();
            if ($user) {

                $bid_params = [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone_number' => $request->phone_number,
                    'bid_amount' => $bid_amount,
                    'auction_id' => $request->auction_id,
                    'car_id' => $request->car_id,
                    'phone_number' => $phone_number,
                ];
                //hash it and send it to the login page so that after login, the bid can be placed
                $bid_params = base64_encode(json_encode($bid_params));
                // dd($bid_params);

                $url = '/login?place_bid=true&params='.$bid_params;

                return redirect($url)
                ->with('error', 'Email already exists. Please login');


                
            } else {
                //create a new user

                //create a string of random characters for the password
                $password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 16);

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
                $user->save();

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
                // dd($res);

                return redirect()->back()->with('success', 'Bid submitted successfully. Check your email for account details');
            }
        }

    }

    public function confirmAccount($confirmation_string)
    {
        //use this function to confirm the account of a user
        // dd($confirmation_string);

        $hash = new Hashids(env('APP_KEY'),  20);
        $decoded = $hash->decode($confirmation_string);

        // dd($decoded);

        if (count($decoded) > 0) {
            $user = User::where('confirmation_id', $decoded[0])->first();
            // dd($user);

            if ($user) {
                return view('pages.confirm-account', compact('user'));

            } else {
                return redirect()->route('listings')->with('error', 'Account not confirmed');
            }
        } else {
            return redirect()->route('listings')->with('error', 'Account not confirmed');
        }
    }

    public function update_after_confirmation(Request $request) {
        // dd($request->all());

        //verify the request first
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'password' => 'required',
            'user_id' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);

        $user = User::find($request->user_id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->password = bcrypt($request->password);
        $user->email_verified_at = now();
        $user->email_verified = 1;
        $user->save();

        //logput any other user
        auth()->logout();
        //login the user
        auth()->login($user);

        return redirect('/admin')->with('success', 'Account confirmed successfully. Welcome to your customer dashboard');
    }

    public function verifyEmail($confirmation_string)
    {
        //use this function to confirm the account of a user
        // dd($confirmation_string);

        $hash = new Hashids(env('APP_KEY'),  20);
        $decoded = $hash->decode($confirmation_string);

        // dd($decoded);

        if (count($decoded) > 0) {
            $user = User::where('confirmation_id', $decoded[0])->first();
            // dd($user);

            if ($user) {
                $user->email_verified_at = now();
                $user->email_verified = 1;
                $user->save();

                return redirect()->route('listings')->with('success', 'Email verified successfully');

            } else {
                return redirect()->route('listings')->with('error', 'Email not verified');
            }
        } else {
            return redirect()->route('listings')->with('error', 'Account not confirmed');
        }
    }


    public function login()
    {
        //use this function to display the login form
        return view('pages.login');
    }

    public function loginUser(Request $request)
    {
        //use this function to login the user
        // dd($request->all());

        //verify the request first
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        //attempt to login the user
        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
        // if (1 == 1) {
            // Authentication passed...

            //place bid here
            $bid_params = json_decode(base64_decode($request['bid_params']), true);
            // dd("here", $request->all(), $request['bid_params'], base64_decode($request['bid_params']), $bid_params);

            $auction_id = $bid_params['auction_id'];
            $bid_amount = $bid_params['bid_amount'];
            $user_id = auth()->user()->id;
            $car_id = $bid_params['car_id'];
            $phone_number = $bid_params['phone_number'];

            $bid_controller = new BidController();
            $res = $bid_controller->place_bid($auction_id, $bid_amount, $user_id, $car_id, $phone_number);

            if($res['success']) {
                $transaction_id = $res['transaction_id'];
                return redirect("/payment-processing/$transaction_id")->with('success', $res['message']);
                // return redirect()->back()->with('success', $res['message']);

            } else {
                return redirect()->back()->with('error', $res['message']);
            }



            // return redirect()->route('listings')->with('success', 'Login successful and bid placed');
        } else {
            return redirect()->back()->with('error', 'Login failed');
        }
    }
}
