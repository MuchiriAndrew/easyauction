@extends('layouts.app')

@section('title', 'EasyAuction- Account Confirmation')

@section('content')

@php
    // dd($user);
@endphp

<style>
    #contact_form {
        height: auto !important;
    }
</style>
<section class=" mt-15">
    <div class="container">
        <div class="row">
            <h2>Kindly confirm your account details</h2>
            <div class="col-md-8">
                <div class="contact-form">
                    <form 
                    action="/confirm-account"
                    method="POST"
                    id="contact_form" class=" d-flex flex-column justify-content-center align-items-center" action="#" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="name">Name</label>
                                <input type="text" class="name" name="name" placeholder="Name" value="{{$user->name}}">
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="email">Email</label>
                                <input type="text" class="email" name="email" placeholder="Email address" value="{{$user->email}}">
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="phone">Phone</label>
                                <input type="text" class="phone" name="phone_number" placeholder="Phone" value="{{$user->phone_number}}">
                            </div>
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="password">Password(Update password)</label>
                                <input type="password" class="password" name="password" placeholder=""
                                value=""
                                >
                            </div>
                            <input type="hidden" name="user_id" value="{{$user->id}}">

                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <label for="password">Confirm Password (Confirm updated password)</label>
                                <input type="password" class="password" name="password_confirmation" placeholder=""
                                value=""
                                >
                            </div>
                            
                        </div>
                        <div class="col-md-6">
                            <button type="submit" class="advanced-button">
                                <a>Confirm Account</a>
                            </button>
                        </div>
                    </form>		
                </div>
            </div>
        
        </div>
    </div>
</div>

@endsection