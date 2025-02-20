<?php

use App\Http\Controllers\PagesController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    //redirect to home page
    return redirect()->route('listings');
});


// Route::get('/home', [PagesController::class, "home"])->name('home');
Route::get('/single-view', [PagesController::class, "single_view"])->name('single-view');
Route::get('/listings', [PagesController::class, "listings"])->name('listings');