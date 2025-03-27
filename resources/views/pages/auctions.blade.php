@extends('layouts.app')

@section('title', 'EasyAuction- Auctions')

@section('content')

    <section class="listing-grid mt-15">
        <div class="container">
            <div class="row">
                <div id="listing-cars" class="col-md-12">
                    <div class="pre-featured clearfix h-17">
                        <div class="info-text">
                            <h4>{{ $count ?? '0' }} {{ $count == 1 ? 'auction' : 'auctions' }} found</h4>
                        </div>



                    </div>
                    <div id="featured-auctions">

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

                            @foreach ($auctions as $ind=>$auction)
                                @php
                                //get the car ids
                                $carIds = $auction->car_ids;
                                $cars = [];
                                // $photoPaths = [];
                                foreach($carIds as $carId) {
                                    $car = \App\Models\Car::find($carId);
                                    //get the photo path
                                    $photoPath = $car->photo_path;
                                    //get the make and model
                                    $make = $car->make;
                                    $model = $car->model;
                                    // $photoPaths[] = $photoPath;
                                    $cars[] = $car;
                                }
                                   
                                @endphp
<div class=".auctioncard">
    <x-auction-card :link="'/auctions/' . $auction->id" :auction="$auction" :cars="$cars"   />
</div>


@endforeach

                        </div>
                    </div>
                   
                </div>

                <style>
                    /* .auctioncard {
                        margin: 50px !important;
                    } */
                </style>
                
            </div>
        </div>
    </section>


    {{-- @include('partials.cta-2') --}}


    


@endsection
