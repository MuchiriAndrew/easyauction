<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Car;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function auctions() {
        $auctions = Auction::all();
        $count = count($auctions);
        return view('pages.auctions', compact('auctions', 'count'));
    }

    public function auction_listings($id) {
        $auction = Auction::find($id);
        return view('pages.auctions-single', compact('auction'));
    }

    public function single_view($id) {
        //get the auction with that id
        // $auction = Auction::with('car')->find($id);

        // $car = $auction->car;
        $car = Car::find($id);
        $vendor = $car->vendor;

        //get the auction with the car id
        $auction = Auction::where(function($query) use ($id) {
            $query->whereRaw("JSON_CONTAINS(car_ids, '\"$id\"')");
        })->first();
        // dd($auction);


        return view('pages.single-view', compact('auction', 'car', 'vendor', 'auction'));
    }
    
    public function listings(Request $request) {
        // dd($request->all());

        //get the filter parameters
        $filter = $request->query('filter');
        // dd($filter);

        //get all auctions
        $auctions = Auction::all();
        // dd($auctions);
        $count = count($auctions);

        if($filter) {
            // dd($request->all());
            $parameters = $request->validate([
                'make' => 'required',
                'model' => 'required',
                'style' => 'required',
                'color' => 'required',
            ]);
            foreach($auctions as $auction) {
                $carIds = $auction->car_ids;
                // $carIds = str_replace(' ', '', $carIds);
                // $carIds = explode(',', $carIds);
                $cars = [];
                foreach($carIds as $carId) {
                    $car = Car::find($carId);
                    // dd($car, $parameters['color']);

                    if(strtolower($car->make) == strtolower($parameters['make']) || strtolower($car->model) == strtolower($parameters['model']) || strtolower($car->style) == strtolower($parameters['style']) || strtolower($car->color) == strtolower($parameters['color'])) {
                        $cars[] = $car;
                    }
                }
            }
            $count = count($cars);
            return view('pages.listings', compact('cars', 'count'));


           
        } else {
            //get all auctions
            // $auctions = Auction::all();
            foreach($auctions as $auction) {
                $carIds = $auction->car_ids;

                $carIds = str_replace(' ', '', $carIds);
                $cars = [];
                foreach($carIds as $carId) {
                    $car = Car::find($carId);
                    $cars[] = $car;
                   
                }
                
            }
            $count = count($cars);
            return view('pages.listings', compact('cars', 'count'));
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
