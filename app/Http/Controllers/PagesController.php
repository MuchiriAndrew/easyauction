<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }


    public function single_view() {
        return view('pages.single-view');
    }
    
    public function listings() {
        return view('pages.listings');
    }
}
