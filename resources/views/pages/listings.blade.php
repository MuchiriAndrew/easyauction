@extends('layouts.app')

@section('title', 'EasyAuction- Listings')

@section('content')

{{-- <div id="page-heading"> --}}
    {{-- <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <h1>Listing Results</h1>
                <div class="line"></div>
                <span>Praesent volutpat nisi sed imperdiet facilisis felis turpis fermentum lectus</span>
                <div class="page-active">
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><i class="fa fa-dot-circle-o"></i></li>
                        <li><a href="listin-right.html">Listing Results</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div> --}}
{{-- </div> --}}

<section class="listing-grid mt-15">
    <div class="container">
        <div class="row">
            <div id="listing-cars" class="col-md-9">
                <div class="pre-featured clearfix">
                    <div class="info-text">
                        <h4>24 results found</h4>
                    </div>
                    <div class="right-content">
                        <div class="input-select">
                            <select name="mark" id="mark">
                                <option value="-1">Sorted by</option>
                                  <option>Price</option>
                                  <option>Miles</option>
                                  <option>Year</option>
                                  <option>Near</option>
                            </select>
                        </div>
                        {{-- <div class="grid-list">
                            <ul>
                                <li><a href="#"><i class="fa fa-list"></i></a></li>
                                <li><a href="#"><i class="fa fa-square"></i></a></li>
                            </ul>
                        </div> --}}
                    </div>
                </div>
                <div id="featured-cars">

                    <div class="row">
                    <style>
                        .row {
                            height: auto !important;
                            /* background: red ; */
                        }
                    </style>
                        

                        <x-vehicle-card /> 
                        <x-vehicle-card /> 
                        <x-vehicle-card /> 
                        <x-vehicle-card /> 
                        <x-vehicle-card /> 
                        <x-vehicle-card /> 
                        <x-vehicle-card /> 
                        <x-vehicle-card /> 
                        <x-vehicle-card /> 

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
                <div class="sidebar-content">
                    <div class="head-side-bar">
                        <h4>Refine Your Search</h4>
                    </div>
                    <div class="search-form">
                        <div class="select">
                            <select name="mark" id="make">
                                <option value="-1">Select Makes</option>
                                <option>Jeep</option>
                                <option>Toyota</option>
                                <option>Mazda</option>
                                <option>Audi</option>
                            </select>
                        </div>
                        <div class="select">
                            <select name="mark" id="model">
                                <option value="-1">Select Car Model</option>
                                  <option>Axela</option>
                                  <option>Atenza</option>
                                  <option>CX-5</option>
                                  <option>CX-3</option>
                            </select>
                        </div>
                        <div class="select">
                            <select name="mark" id="style">
                                <option value="-1">Select Style</option>
                                  <option>Station Wagon</option>
                                  <option>Saloon</option>
                                  <option>SUV</option>
                                  <option>Coupe</option>
                            </select>
                        </div>
                        <div class="slider-range">
                            <p>
                                <input type="text" class="range" id="amount" readonly>
                            </p>
                            <div id="slider-range"></div>
                        </div>
                        {{-- <div class="select">
                            <select name="mark" id="types">
                                <option value="-1">Select Car Types</option>
                                  <option>Jeep</option>
                                  <option>Toyota</option>
                                  <option>Mazda</option>
                                  <option>Audi</option>
                            </select>
                        </div> --}}
                        <div class="select">
                            <select name="mark" id="color">
                                <option value="-1">Select Color</option>
                                  <option>Black</option>
                                  <option>Red</option>
                                  <option>Blue</option>
                                  <option>Silver</option>
                            </select>
                        </div>
                        <div class="advanced-button">
                            <a href="listing-right.html">Search Now<i class="fa fa-search"></i></a>
                        </div>
                    </div>
                </div>
                <div class="video-post hidden">
                    <div class="video-holder">
                        <img src="http://dummyimage.com/270x170/cccccc/fff.jpg" alt="">
                        <div class="video-content">
                            <a href="single-blog.html"><i class="fa fa-play"></i></a>
                            <a href="single-blog.html"><h4>Video post example</h4></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- @include('partials.cta-2') --}}


@endsection