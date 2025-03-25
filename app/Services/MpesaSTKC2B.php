<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MpesaSTKC2B {
    public function stk_push($phone, $amount, $auction_id, $credentials, $callbackUrl, $env)
    {

        $consumerKey = $credentials['consumerKey']; // Replace with your consumer key
        $consumerSecret = $credentials['consumerSecret']; // Replace with your consumer secret
        $shortCode = $credentials['shortCode']; // Test Shortcode (use your own for production)
        $passkey = $credentials['passkey']; // From Safaricom portal
        $accountReference = $credentials['accountReference'] == '' ? "$auction_id" : $credentials['accountReference']; // This is anything that helps identify the specific transaction       
        $callbackUrl = env('MPESA_CALLBACK_URL'); // This is the URL where the results from the Safaricom API will be sent to


        //get the first char of the phone which would be a 0 and replace it with 254
        $phone = preg_replace('/^0/', '254', $phone);


        // Ensure phone is in the correct format
        if (!preg_match('/^2547\d{8}$/', $phone) && !preg_match('/^2541\d{8}$/', $phone)) {
            die("Invalid phone number format. Use 2547XXXXXXXX or 2541XXXXXXXX.");
        }

        $accessToken = $this->getAccessToken($consumerKey, $consumerSecret);
        $password = $this->generatePassword($shortCode, $passkey);
        $timestamp = date('YmdHis');

        // Prepare the STK Push Request Payload
        $data = array(
            'BusinessShortCode' => $shortCode,
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $amount,
            'PartyA' => $phone, //this is the sender's phone number
            'PartyB' => $shortCode, //this is the paybill number of the receiver
            'PhoneNumber' => $phone,
            'CallBackURL' => $callbackUrl,
            // 'CallBackURL' => 'https://44c1-154-152-219-169.ngrok-free.app/mpesa-callback',
            'AccountReference' => $accountReference,
            'TransactionDesc' => "Payment for order $auction_id"
            //pass the order id where it can be accessed in the callback

        );

        //log the req
        Log::info(json_encode($data));

        

        //Send STK Push Request
        $curl = curl_init();
        if($env == 'sandbox'){
            curl_setopt($curl, CURLOPT_URL, 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
        }else{
            curl_setopt($curl, CURLOPT_URL, 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest');
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Authorization: Bearer ' . $accessToken));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        // Display the response from Safaricom API
        //get the MerchantRequestID and add it to the order's meta data
        $result = json_decode($response, true);
        
        Log::info(json_encode($result));

        
        return $result;
    }

    public function getAccessToken($consumerKey, $consumerSecret) {
        $credentials = base64_encode($consumerKey . ':' . $consumerSecret);
        $curl = curl_init();
        // curl_setopt($curl, CURLOPT_URL, 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
        curl_setopt($curl, CURLOPT_URL, 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials');
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic ' . $credentials));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($response, true);
        return $result['access_token'];
    }
    
    // Generate the Password for the STK Push
    public function generatePassword($shortCode, $passkey) {
        $timestamp = date('YmdHis');
        return base64_encode($shortCode . $passkey . $timestamp);
    }
}