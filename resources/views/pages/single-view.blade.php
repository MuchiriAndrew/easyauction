@extends('layouts.app')

@section('title', "EasyAuction - $car->make $car->model")

@section('content')

    @php
        $features = $car->features;
        $car_photos = $car->photo_path;
        // dd($car ,$vendor, $features, $car_photos);

        $user = auth()->user();
        $auction = \App\Models\Auction::whereRaw("JSON_CONTAINS(car_ids, '\"$car->id\"')")->first();

        // dd($user);
    @endphp

    <section class="car-details">
        @if ($errors->any() || session('error'))
        @php
            $error = session('error') ?? $errors->first();
        @endphp
            <div class="alert alert-danger error-messages col-md-12 flex justify-center items-center">
                <p class="text-center text-white">
                    {{ $error }}
                </p>
            </div>
        @endif
        <div class="container">
            <div class="row">
                <div id="single-car" class="col-md-8">
                    <div class="up-content clearfix">
                        <h2>{{ $car->make }} - {{ $car->model }}</h2>
                        {{-- <span>Highest Bid: KSh.0.00</span> --}}
                    </div>


                   

                    
                    <div class="swiper auction-carousel rounded-t-md">
                        
                        <div class="swiper-wrapper">
                            @foreach ($car_photos as $photoPath)
                                <div class="swiper-slide">
                                    <img id="featured-image" src="{{ asset('storage/' . $photoPath) }}" alt="Auction Image"
                                    class="w-full h-full object-cover">
                                    <style>
                                        #featured-image {
                                            /* height:500px; */
                                            width: 100%;
                                        }
                                    </style>
                                </div>
                            @endforeach
                        </div>
                        <!-- Add Navigation -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <!-- Add Pagination -->
                        <div class="swiper-pagination"></div>
                    </div>


                    <div class="tab">
                        <div class="tabs">
                            <ul class="tab-links">
                                <li class="active"><a href="#tab1">VEHICLE OVERVIEW</a></li>

                                <li><a href="#tab4">CONTACT VENDOR</a></li>
                            </ul>


                            <div class="tab-content">
                                <div id="tab1" class="tab active">
                                    <p>{{ $car->description }}</p>
                                </div>

                                <div id="tab4" class="tab">
                                    <p>
                                        <strong>Name:</strong> {{ $vendor->name }}<br>
                                        <strong>Email:</strong> {{ $vendor->email }}<br>
                                        <strong>Phone:</strong> {{ $vendor->phone_number }}<br>
                                    </p>
                                </div>
                            </div>


                            <div class="more-info">
                                <div class="row">
                                    <div class="first-info col-md-4">
                                        <h4>Vehicle Features</h4>
                                        <ul>
                                            @foreach($features as $feature)
                                                <li><i class="fa fa-check"></i>{{ ucwords(str_replace("_"," ",$feature)) }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>


                </div>

                {{-- sidebar --}}
                <div id="left-info" class="col-md-4">
                    <div class="details">
                        <div class="head-side-bar">
                            <h4>Vehicle Details</h4>
                        </div>
                        <div class="list-info">
                            <ul>
                                <li><span>Make:</span>{{ strtoupper($car->make) }}</li>
                                <li><span>Manufacture Year:</span>{{ strtoupper($car->year) }}</li>
                                <li><span>Fuel Type:</span>{{ strtoupper($car->fuel_type) }}</li>
                                <li><span>Transmission:</span>{{ strtoupper($car->transmission) }}</li>
                                <li><span>Color:</span>{{ strtoupper($car->color) }}</li>
                                <li><span>Auction:</span>{{$auction->name ?? '' }}</li>
                                @if($highest_bid)
                                <li><span>Highest Bid:</span>KSH {{number_format($highest_bid->amount ?? 0)}}</li>
                                @else
                                <li><span>Highest Bid:</span>KSH 0.00</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="enquiry">
                        <div class="head-side-bar">
                            <h4>SUBMIT BID</h4>
                        </div>
                        @if($auction->status == 'closed')
                            <p class="text-red-600 text-center p-5">You can no longer place bids on this vehicle because its auction has been closed</p>
                        @else
                        <form action="/customer/register" method="POST" class="contact-form">
                            @csrf
                            <p>Fill in your details here to submit your bid</p>
                            {{-- <label for="name">Name <span class="text-red-600"> *</span></label>
                            <input type="text" class="name" value="{{ old('name') }}"
                                name="name" placeholder="Your Name" value=""> --}}

                            <label for="name">Email<span class="text-red-600"> *</span></label>
                            <input type="email" value="{{ old('email') }}" class="email" name="email"
                                placeholder="Email Address" value="">

                            <label for="name">Phone Number<span class="text-red-600"> *</span></label>
                            <input type="text" value="{{ old('phone_number') }}" class="phone" name="phone_number"
                                placeholder="Your Phone Number" value="">

                            <label for="bid_amount">Bid Amount<span class="text-red-600"> *</span></label>
                            {{-- <input type="text" class="phone" name="bid_amount" id="bid_amount"
                                placeholder="{{ $errors->has('bid_amount') ? $errors->first('bid_amount') : 'Your Bid Amount' }}"
                                style="color: red"
                                value="{{ old('bid_amount') }}" "
                                    oninput="formatBidAmount(this)" /> --}}
                            <input type="text" class="phone" name="bid_amount" id="bid_amount"
                                placeholder="Your Bid Amount" value="{{ old('bid_amount') }}"
                                oninput="formatBidAmount(this)" />



                            <script>
                                function formatBidAmount(input) {
                                    // Remove any non-numeric characters except commas
                                    let value = input.value.replace(/,/g, '').replace(/\D/g, '');
                                    // Format the number with commas
                                    input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                                }
                            </script>

                            <input type="hidden" name="auction_id" value="{{ $auction->id }}">
                            <input type="hidden" name="car_id" value="{{ $car->id }}">
                            {{-- <label for="name">Message (Optional)</label>
                        <textarea id="message" class="message" name="message" placeholder="Message..."></textarea> --}}
                            <div class="bg-priamry">
                                <button type="submit" class="advanced-button">
                                    <a>Submit Bid <i class="fa fa-paper-plane"></i></a>
                                </button>
                            </div>
                        </form>
                        @endif
                        <div class="subhead-side-bar">
                            <h4>Contact the Seller</h4>
                        </div>
                        <div class="call-info">
                            <i class="fa fa-phone"></i>
                            <h6>{{ $vendor->phone_number }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- @include('partials.featured-listing') --}}


    {{-- @include('partials.cta-2') --}}


@endsection
