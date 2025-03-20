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

                                <x-auction-card :link="'/auctions/' . $auction->id" :auction="$auction" :cars="$cars"   />
                                {{-- <x-auction-card :link="'/auctions/' . $auction->id" :auction="$auction" :cars="$cars"   /> --}}
                    
                            @endforeach

                        </div>
                    </div>
                   
                </div>
                {{-- <div id="sidebar" class="col-md-3">
                    <form action="/filter" method="POST" class="sidebar-content">
                        @csrf
                        <div class="head-side-bar">
                            <h4>Filter Your Search</h4>
                        </div>
                        <div class="search-form">
                            <div class="select">
                                <select name="make" id="make">
                                    <option value="-1">Select Makes</option>
                                    <option {{ $make == 'Jeep' ? 'selected' : '' }}>Jeep</option>
                                    <option {{ $make == 'Toyota' ? 'selected' : '' }}>Toyota</option>
                                    <option {{ $make == 'Mazda' ? 'selected' : '' }}>Mazda</option>
                                    <option {{ $make == 'Audi' ? 'selected' : '' }}>Audi</option>


                                </select>
                            </div>
                            <div class="select">
                                <select name="model" id="model">
                                    <option value="-1">Select Car Model</option>
                                    <option {{ $model == 'Axela' ? 'selected' : '' }}>Axela</option>
                                    <option {{ $model == 'Atenza' ? 'selected' : '' }}>Atenza</option>
                                    <option {{ $model == 'Wrangler' ? 'selected' : '' }}>Wrangler</option>
                                    <option {{ $model == 'CX-5' ? 'selected' : '' }}>CX-5</option>
                                    <option {{ $model == 'CX-3' ? 'selected' : '' }}>CX-3</option>
                                </select>
                            </div>
                            <div class="select">
                                <select name="style" id="style">

                                    <option value="-1">Select Style</option>
                                    <option {{ $style == 'Station Wagon' ? 'selected' : '' }}>Station Wagon</option>
                                    <option {{ $style == 'Saloon' ? 'selected' : '' }}>Saloon</option>
                                    <option {{ $style == 'SUV' ? 'selected' : '' }}>SUV</option>
                                    <option {{ $style == 'Coupe' ? 'selected' : '' }}>Coupe</option>

                                </select>
                            </div>
                          

                            <div class="select">
                                <select name="color" id="color">

                                    <option value="-1">Select Color</option>
                                    <option {{ $color == 'Black' ? 'selected' : '' }}>Black</option>
                                    <option {{ $color == 'Red' ? 'selected' : '' }}>Red</option>
                                    <option {{ $color == 'Blue' ? 'selected' : '' }}>Blue</option>
                                    <option {{ $color == 'Silver' ? 'selected' : '' }}>Silver</option>

                                </select>
                            </div>
                            <button type="submit" class="advanced-button">
                                <a>Search Now<i class="fa fa-search"></i></a>

                            </button>

                        </div>
                    </form>

                </div> --}}
            </div>
        </div>
    </section>


    {{-- @include('partials.cta-2') --}}


@endsection
