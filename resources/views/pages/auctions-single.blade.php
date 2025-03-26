@extends('layouts.app')

@section('title', "EasyAuction - $auction->name")

@section('content')

@php
    // dd($car ,$vendor);
    $user = auth()->user();
    // dd($user);

    $name = $auction->name;
    $description = $auction->description;
    $end_time = $auction->end_time;
    $current_bid = $auction->current_bid;
    $photo_path = $auction->photo_path;
    // $cars = $auction->cars;

    $carIds = $auction->car_ids;
    $cars = [];
    foreach($carIds as $carId) {
        $car = \App\Models\Car::find($carId);
        $cars[] = $car;
    }

    $count = count($cars);
    // dd($highest_bids);
    
    
@endphp

<section class="listing-grid mt-15">
    <div class="container">
        <div class="row">
            <div id="listing-cars" class="col-md-8">
                <div class="pre-featured clearfix h-17">
                    <div class="info-text">
                        <h4>{{ $count ?? '0' }} featured {{ $count == 1 ? 'car' : 'cars' }} found</h4>
                    </div>
                    {{-- <h2>{{$name}}</h2> --}}
                    {{-- <span>Highest Bid: KSh.0.00</span> --}}
                </div>

                <div id="featured-cars">

                    <div class="row">
                        <style>
                            .row {
                                height: auto !important;
                                /* background: red ; */
                            }
                        </style>

                        @php
                            // dd($auctions);
                        @endphp

                        @foreach ($cars as $ind=>$car)
                            @php
                                // $car = $auction->car;
                                // dd($car->photo_path, $auction);
                                $highest_bid = $highest_bids->where('car_id', $car->id)->first()->amount ?? 0;
                                // dd($highest_bid);
                            @endphp

                            <x-vehicle-card :auction="json_encode($auction)" :image="'storage/' . $car->photo_path[0]" :link="'/single-view/' . $car->id" :title="$car->make . ' ' . $car->model" :description="$car->description"
                                :fuel="$car->fuel_type" :type="$car->style" :mileage="$car->mileage" :highest="$highest_bid ?? '0.00'" />
                        @endforeach

                    </div>
                </div>

                
                



                

 
               


            </div>

            {{-- sidebar --}}
            <div id="left-info" class="col-md-4">
                <div class="details">
                    <div class="head-side-bar">
                        <h4>Auction Details</h4>
                    </div>
                    <div class="list-info">
                        <ul>
                            <li><span>Name:</span>{{strtoupper($name)}}</li>
                            <li><span>Description:</span>{{($description)}}</li>
                            <li><span>End Time:</span>{{strtoupper($end_time)}}</li>
                            <li><span>Time Remaining:</span></li>
                            <li><span>Current Bid:</span>{{strtoupper($current_bid)}}</li>
                            
                        </ul>
                    </div> 
                </div>
                
            </div>
        </div>
    </div>
</section>

{{-- @include('partials.featured-listing') --}}


{{-- @include('partials.cta-2') --}}


@endsection