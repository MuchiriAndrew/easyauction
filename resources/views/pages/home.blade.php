@extends('layouts.app')

@section('title', 'EasyAuction- Homepage')

@section('content')

@include('partials.slider')
@include('partials.cta-1')
@include('partials.why-us')
@include('partials.featured-listing')
@include('partials.blog-news')
@include('partials.clients')
@include('partials.cta-2')

@endsection