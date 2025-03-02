<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }


    public function single_view($id) {
        //get the auction with that id
        $auction = Auction::with('car')->find($id);
        $car = $auction->car;
        $vendor = $car->vendor;
        // dd($vendor);

        //dd the vendor and his role
        // dd($vendor, $vendor->getRoleAttribute());


        return view('pages.single-view', compact('auction', 'car', 'vendor'));
    }
    
    public function listings() {
        //get all auctions
        $auctions = Auction::with('car')->get();
        $count = count($auctions);

        return view('pages.listings', compact('auctions', 'count'));
    }
}
