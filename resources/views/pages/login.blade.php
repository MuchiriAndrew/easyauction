@extends('layouts.app')

@section('title', 'EasyAuction- Account Confirmation')

@section('content')

    @php
        // dd(request()->all());
    @endphp

    <style>
        #contact_form {
            height: auto !important;
            border: 1px solid #ccc !important;
            /* background: aqua !important; */
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
            align-items: center !important;
            /* padding: 20px !important; */
            padding-top: 50px !important;
            padding-bottom: 50px !important;
        }
    </style>

    <section class="mt-15 text-center flex justify-center items-center">
        <h2>Login to your account</h2>
        <div class="container flex flex-column justify-center items-center">
            {{-- <div class=""> --}}
                <div class="col-md-8">
                    <div class="contact-form">
                        <form action="/login" method="POST" id="contact_form" class="">
                            @csrf

                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="email">Email</label>
                                <input type="text" class="email" name="email" placeholder="Email address">
                            </div>

                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="password">Password</label>
                                <input type="password" class="password" name="password" placeholder="" value="">
                            </div>

                            @if(request('place_bid') && request('place_bid') == 'true')
                                <input type="hidden" name="bid_params" value="{{request('params')}}">
                            @endif




                            <div class="col-md-6">
                                <button type="submit" class="advanced-button">
                                    <a>Login</a>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            {{-- </div> --}}
        </div>
        </div>



    @endsection
