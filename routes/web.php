<?php
use App\Http\Controllers\MailController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    //return view('welcome');
    //redirect to home page
    return redirect()->route('listings');
});


// Route::get('/home', [PagesController::class, "home"])->name('home');
Route::get('/single-view/{id}', [PagesController::class, "single_view"])->name('single-view');
Route::get('/listings', [PagesController::class, "listings"])->name('listings');


Route::post('/send-contact-form', [MailController::class, 'sendContactForm'])->name('send.contact.form');
Route::get('/send-contact-form', [MailController::class, 'displayContactForm'])->name('display.contact.form');


Route::post('/send-account-confirmation', [MailController::class, 'sendAccountConfirmation'])->name('send.account.confirmation');
Route::post('/send-email-verification', [MailController::class, 'sendEmailVerification'])->name('send.email.verificaion');

Route::post('/customer/register', [UserController::class, 'register'])->name('register');

Route::get('/confirm-account/{confirmation_string}', [UserController::class, 'confirmAccount'])->name('confirm.account');
