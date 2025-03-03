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
    
    public function listings(Request $request) {

        //get the filter parameters
        $filter = $request->query('filter');
        // dd($filter);

        if($filter) {
            // dd($request->all());
            $parameters = $request->validate([
                'make' => 'required',
                'model' => 'required',
                'style' => 'required',
                'color' => 'required',
            ]);
            $auctions = Auction::with('car')->whereHas('car', function($query) use ($parameters) {
                $query->where('make', $parameters['make'])
                    ->where('model', $parameters['model'])
                    ->where('style', $parameters['style'])
                    ->where('color', $parameters['color']);
            })->get();
            // dd($auctions);
            $count = count($auctions);
            return view('pages.listings', compact('auctions', 'count'));
        } else {
            //get all auctions
            $auctions = Auction::with('car')->get();
            $count = count($auctions);
            return view('pages.listings', compact('auctions', 'count'));
        }
        
    }

    public function filter(Request $request) {
        // dd($request->all());

        //validate request
        $parameters = $request->validate([
            'make' => 'required',
            'model' => 'required',
            'style' => 'required',
            'color' => 'required',
        ]);


        //get the filter parameters and add to the /listings route

        $url = '/listings?filter=true&make='.$parameters['make'].'&model='.$parameters['model'].'&style='.$parameters['style'].'&color='.$parameters['color'];

        return redirect($url);
    }
}
