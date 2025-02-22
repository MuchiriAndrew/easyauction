<?php
// filepath: /home/muchiri/myprojects/easyauction-project/app/Http/Controllers/ContactController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactForm;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function sendContactForm(Request $request)
    {
        // dd($request->all());
        $details = [
            'name' => $request->name,
            'email' => $request->email,
            'message' => $request->message,
        ];

        Mail::to('andrew.muchiri@belvadigital.com')->send(new ContactForm($details));

        return back()->with('success', 'Email sent successfully!');
    }

    public function displayContactForm()
    {
        return view('emails.display-contact-form');
    }
}