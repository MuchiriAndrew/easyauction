<?php

use App\Http\Controllers\BidController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\UserController;
use App\Services\MpesaB2C;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //return view('welcome');
    //redirect to home page
    return redirect()->route('listings');

    // return view('pages.home');
});


// Route::get('/home', [PagesController::class, "home"])->name('home');
Route::get('/listings', [PagesController::class, "listings"])->name('listings');
Route::get('/single-view/{id}', [PagesController::class, "single_view"])->name('single-view');


Route::get('/auctions', [PagesController::class, "auctions"])->name('auctions');
Route::get('/auctions/{id}', [PagesController::class, "auction_listings"])->name('auction.listings');



Route::post('/send-contact-form', [MailController::class, 'sendContactForm'])->name('send.contact.form');
Route::get('/send-contact-form', [MailController::class, 'displayContactForm'])->name('display.contact.form');


Route::post('/send-account-confirmation', [MailController::class, 'sendAccountConfirmation'])->name('send.account.confirmation');
Route::post('/send-email-verification', [MailController::class, 'sendEmailVerification'])->name('send.email.verificaion');

Route::post('/customer/register', [UserController::class, 'register'])->name('register');
Route::post('/mpesa-callback', [BidController::class, 'callback']);

Route::get('/payment-processing/{id}', [PagesController::class, 'payment_processing'])->name('payment.processing');
Route::get('/poll-transaction-status/{id}', [BidController::class, 'poll_transaction_status']);
Route::get('/mpesa-payment-failed/{id}', [PagesController::class, 'mpesa_payment_failed']);


Route::get('/login', [UserController::class, 'login'])->name('login');
Route::post('/login', [UserController::class, 'loginUser'])->name('login.user');

Route::get('/confirm-account/{confirmation_string}', [UserController::class, 'confirmAccount'])->name('confirm.account');
Route::get('/verify-email/{confirmation_string}', [UserController::class, 'verifyEmail'])->name('verify.email');
Route::post('/confirm-account', [UserController::class, 'update_after_confirmation'])->name('update.confirm.account');

Route::post('filter', [PagesController::class, 'filter'])->name('filter');

Route::get('testb2c', [MpesaB2C::class, 'b2c'])->name('testb2c');
Route::get('testemail', [MailController::class, 'sendUserWinner']);
