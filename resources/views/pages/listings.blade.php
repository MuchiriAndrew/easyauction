@extends('layouts.app')

@section('title', 'EasyAuction- Listings')

@section('content')

    @php
        // dd(request()->all());
        $filter = request('filter');

        if ($filter && $filter == 'true') {
            $make = request('make');
            $model = request('model');
            $style = request('style');
            $color = request('color');
        } else {
            $make = '';
            $model = '';
            $style = '';
            $color = '';
        }
    @endphp

    <section class="listing-grid mt-15">
        <div class="container">
            <div class="row">
                <div id="listing-cars" class="col-md-9">
                    <div class="pre-featured clearfix h-17">
                        <div class="info-text">
                            <h4>{{ $count ?? '0' }} results found</h4>
                        </div>

                        
                        @if ($filter && $filter == 'true')
                        <div class="right-content">
                            
                            
                            <div class="input-select">
                                <button type="submit" class="advanced-button">
                                    <a href="/listings">Clear Filters<i class="fa fa-close"></i></a>
                                </button>
                                
                            </div>
                            
                        </div>
                        @endif



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
                                // dd($ind, $car, $cars);
                                $id = $car->id;
                                    // $car = $auction->car;
                                    // $auction = $car->auction;

                                    //find an auction where the car id is in the json car_ids field
                                    // $auction = \App\Models\Auction::whereJsonContains('car_ids', $car->id)->first();
                                    $auction = \App\Models\Auction::whereRaw("JSON_CONTAINS(car_ids, '\"$id\"')")->first();
                                    // $auction = \App\Models\Auction::where('id', 4)->first() ?? [];
                                    // dd($car->photo_path, $auction);

                                @endphp

                                <x-vehicle-card :auction="$auction"  :image="'storage/' . $car->photo_path[0]" :link="'/single-view/' . $car->id" :title="$car->make . ' ' . $car->model" :description="$car->description"
                                    :fuel="$car->fuel_type" :type="$car->style" :mileage="$car->mileage" :highest="$auction->current_bid ?? '0.00'" />
                            @endforeach

                        </div>
                    </div>

                    
                    {{-- <div class="pagination">
                    <div class="prev">
                        <a href="#"><i class="fa fa-arrow-left"></i>Prev</a>
                    </div>
                    <div class="page-numbers">
                        <ul>
                            <li><a href="#">1</a></li>
                            <li><a href="#">...</a></li>
                            <li><a href="#">14</a></li>
                            <li class="active"><a href="#">15</a></li>
                            <li><a href="#">16</a></li>
                            <li><a href="#">...</a></li>
                            <li><a href="#">47</a></li>
                        </ul>
                    </div>
                    <div class="next">
                        <a href="#">Next<i class="fa fa-arrow-right"></i></a>
                    </div>
                </div> --}}
                </div>
                <div id="sidebar" class="col-md-3">
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
                            {{-- <div class="slider-range">
                                <p>
                                    <input type="text" class="range" id="amount" readonly>
                                </p>
                                <div id="slider-range"></div>
                            </div> --}}

                            <div class="select">
                                <select name="color" id="color">
                                    {{-- <option value="-1">Select Color</option>
                                    <option>Black</option>
                                    <option>Red</option>
                                    <option>Blue</option>
                                    <option>Silver</option> --}}

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

                </div>
            </div>
        </div>
    </section>


    {{-- @include('partials.cta-2') --}}


@endsection
