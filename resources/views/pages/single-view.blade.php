@extends('layouts.app')

@section('title', "EasyAuction - $car->make $car->model")

@section('content')

@php
    // dd($car ,$vendor);
    $user = auth()->user();
    // dd($user);
@endphp

<section class="car-details mt-15">
    <div class="container">
        <div class="row">
            <div id="single-car" class="col-md-8">
                <div class="up-content clearfix">
                    <h2>{{$car->make}} - {{$car->model}}</h2>
                    {{-- <span>Highest Bid: KSh.0.00</span> --}}
                </div>


                <div class="flexslider">
                    <ul class="slidesr">
                          <img src="{{asset("storage/$car->photo_path")}}" alt="" />
                       
                    </ul>
                </div>

 
                <div class="tab">
                    <div class="tabs">
                        <ul class="tab-links">
                            <li class="active"><a href="#tab1">VEHICLE OVERVIEW</a></li>
                            
                            <li><a href="#tab4">CONTACT DEALER</a></li>
                        </ul>


                        <div class="tab-content">
                            <div id="tab1" class="tab active">
                                <p>{{$car->description}}</p>
                            </div>								 
                            
                            <div id="tab4" class="tab">
                                <p>
                                    <strong>Name:</strong> {{$vendor->name}}<br>
                                    <strong>Email:</strong> {{$vendor->email}}<br>
                                    <strong>Phone:</strong> {{$vendor->phone_number}}<br>
                                </p>
                            </div>
                        </div>


                        <div class="more-info">
                            <div class="row">
                                <div class="first-info col-md-4">
                                    <h4>Enterainment</h4>
                                    <ul>
                                        <li><i class="fa fa-check"></i>Central Locking</li>
                                        <li><i class="fa fa-check"></i>Automatic Air Conditioning</li>
                                        <li><i class="fa fa-check"></i>Full Leather Interior</li>
                                        <li><i class="fa fa-check"></i>Electric Heated Seats</li>
                                        <li><i class="fa fa-check"></i>Navigation GPS Multimedia</li>
                                    </ul>
                                </div>
                                <div class="second-info col-md-4">
                                    <h4>exterior features</h4>
                                    <ul>
                                        <li><i class="fa fa-check"></i>Parking Sensors</li>
                                        <li><i class="fa fa-check"></i>Double Exhaust</li>
                                        <li><i class="fa fa-check"></i>Electric Mirrors</li>
                                        <li><i class="fa fa-check"></i>Manifacturing Year 2015</li>
                                        <li><i class="fa fa-check"></i>Full Service History</li>
                                    </ul>
                                </div>
                                <div class="third-info col-md-4">
                                    <h4>interior features</h4>
                                    <ul>
                                        <li><i class="fa fa-check"></i>ABS</li>
                                        <li><i class="fa fa-check"></i>Xenon Headlights</li>
                                        <li><i class="fa fa-check"></i>Immobilizer</li>
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
                            <li><span>Make:</span>{{strtoupper($car->make)}}</li>
                            <li><span>Manufacture Year:</span>{{strtoupper($car->year)}}</li>
                            <li><span>Fuel Type:</span>{{strtoupper($car->fuel_type)}}</li>
                            <li><span>Transmission:</span>{{strtoupper($car->transmission)}}</li>
                            <li><span>Color:</span>{{strtoupper($car->color)}}</li>
                            <li><span>Highest Bid:</span>0.0</li>
                        </ul>
                    </div> 
                </div>
                <div class="enquiry">
                    <div class="head-side-bar">
                        <h4>SUBMIT BID</h4>
                    </div>
                    <form
                    action="/customer/register"
                    method="POST"
                    class="contact-form">
                        @csrf
                        <p>Fill in your details here to submit your bid</p>
                        <label for="name">Name <span class="text-red-600"> *</span></label>
                        <input type="text" class="name" name="name" placeholder="Your Name" value="">

                        <label for="name">Email<span class="text-red-600"> *</span></label>
                        <input type="email" class="email" name="email" placeholder="Email Address" value="">

                        <label for="name">Phone Number<span class="text-red-600"> *</span></label>
                        <input type="text" class="phone" name="phone_number" placeholder="Your Phone Number" value="">

                        <label for="name">Bid Amount<span class="text-red-600"> *</span></label>
                        <input type="text" class="phone" name="bid_amount" placeholder="Your Bid Amount" value="">

                        <input type="hidden" name="auction_id" value="{{$auction->id}}">

                        {{-- <label for="name">Message (Optional)</label>
                        <textarea id="message" class="message" name="message" placeholder="Message..."></textarea> --}}
                        <div class="check-boxes">
                            <button type="submit" class="advanced-button">
                                <a href="#">Submit Bid <i class="fa fa-paper-plane"></i></a>
                            </button>
                        </div>
                    </form>
                    <div class="subhead-side-bar">
                        <h4>Contact the Seller</h4>
                    </div>
                    <div class="call-info">
                        <i class="fa fa-phone"></i>
                        <h6>0786638466</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- @include('partials.featured-listing') --}}


{{-- @include('partials.cta-2') --}}


@endsection