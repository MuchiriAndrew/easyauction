@extends('layouts.app')

@section('title', 'EasyAuction- Homepage')

@section('content')

<section class="car-details mt-15">
    <div class="container">
        <div class="row">
            <div id="single-car" class="col-md-8">
                <div class="up-content clearfix">
                    <h2>Audi a6 tsi s-line</h2>
                    <span>$30.000</span>
                </div>


                <div class="flexslider">
                    <ul class="slides">
                        <li data-thumb="{{asset("assets/images/featured-slide-image.jpg")}}">
                          <img src="{{asset("assets/images/featured-slide-image.jpg")}}" alt="" />
                        </li>
                        {{-- <li >
                          <img src="{{asset("assets/images/featured-slide-image.jpg")}}" alt="" />
                        </li> --}}
                        {{-- <li data-thumb="{{asset("assets/images/featured-slide-image.jpg")}}">
                          <img src="{{asset("assets/images/featured-slide-image.jpg")}}" alt="" />
                        </li>
                        <li data-thumb="{{asset("assets/images/featured-slide-image.jpg")}}">
                          <img src="{{asset("assets/images/featured-slide-image.jpg")}}" alt="" />
                        </li>
                        <li data-thumb="{{asset("assets/images/featured-slide-image.jpg")}}">
                          <img src="{{asset("assets/images/featured-slide-image.jpg")}}" alt="" />
                        </li> --}}
                    </ul>
                </div>


                <div class="tab">
                    <div class="tabs">
                        <ul class="tab-links">
                            <li><a href="#tab1">vehicle overview</a></li>
                            <li class="active"><a href="#tab2">description</a></li>
                            <li><a href="#tab3">vehicle location</a></li>
                            <li><a href="#tab4">contact dealer</a></li>
                        </ul>


                        <div class="tab-content">
                            <div id="tab1" class="tab">
                                <p>	Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi in dolorem blanditiis voluptatibus quidem nisi eaque, cupiditate minus omnis, voluptatum corporis neque placeat quod temporibus mollitia. Quod accusamus iure eveniet laboriosam laudantium, saepe quidem incidunt, laboriosam aliquid quibusdam atque.</p>
                            </div>								 
                            <div id="tab2" class="tab active">
                                <h6>The dealer's details will be emailed to you immediately after you submit your query</h6>
                                <p>Selvage drinking vinegar roof party bitters beard wolf craft beer Blue Bottle, literally you probably haven't heard of them. Deep v jean shorts Williamsburg synth pork belly actually. Organic PBRB viral four loko Bushwick pork belly. Selvage fashion axe sartorial cliche before they sold out, mustache vinyl DIY gastropub fingerstache mlkshk. High Life lo-fi chillwave meggings.<br><br>Migas gluten-free ennui Truffaut ugh, listicle umami plaid lomo sustainable mumblecore street art biodiesel readymade. Polaroid ethical Pitchfork, sartorial bitters mlkshk cliche keytar tofu four loko pork belly High Life lomo listicle.<br><br>Semiotics Vice Wes Anderson Bushwick organic. Chambray twee Banksy, asymmetrical disrupt bitters selfies Helvetica. Gentrify direct trade disrupt Odd Future. Bespoke tote bag small batch, try-hard drinking vinegar cronut beard migas ethical. Seitan wolf Vice banh mi YOLO flannel. Banh mi pug cred church-key, cardigan drinking vinegar hella bicycle rights ugh sustainable. Marfa Bushwick aesthetic, locavore messenger bag 8-bit tote bag.</p>
                            </div>							 
                            <div id="tab3" class="tab">
                                <p>	Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi in dolorem blanditiis voluptatibus quidem nisi eaque, cupiditate minus omnis, voluptatum corporis neque placeat quod temporibus mollitia. Quod accusamus iure eveniet laboriosam laudantium.</p>
                            </div>
                            <div id="tab4" class="tab">
                                <p>	Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi in dolorem blanditiis voluptatibus quidem nisi eaque, cupiditate minus omnis, voluptatum corporis neque placeat quod temporibus mollitia. Quod accusamus iure eveniet laboriosam laudantium.</p>
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
                            <li><span>Make:</span>Audi</li>
                            <li><span>Fabrication Year:</span>2015-6-17</li>
                            <li><span>Fuel Type:</span>Gasoline Fuel</li>
                            <li><span>No. of Gears:</span>5</li>
                            <li><span>Transmission:</span>Automatic</li>
                            <li><span>Color:</span>Blue</li>
                            <li><span>Fuel Economy:</span>12l/City - 10l/hwy</li>
                            <li><span>Motor Capacity:</span>( 179KW / 400BHP )</li>
                            <li><span>Country of Origin:</span>Germany ( Munich )</li>
                            <li><span>Price:</span>$30,000</li>
                        </ul>
                    </div> 
                </div>
                <div class="enquiry">
                    <div class="head-side-bar">
                        <h4>Vehicle Enquiry</h4>
                    </div>
                    <div class="contact-form">
                        <p>The dealer's details will be emailed to you immediately after you submit your query.</p>
                        <input type="text" class="name" name="s" placeholder="Your Name" value="">
                        <input type="text" class="email" name="s" placeholder="Email Address" value="">
                        <input type="text" class="phone" name="s" placeholder="Your Phone Number" value="">
                        <textarea id="message" class="message" name="message" placeholder="Message..."></textarea>
                    </div>
                    <div class="subhead-side-bar">
                        <h4>Ask a question</h4>
                    </div>
                    <div class="check-boxes">
                        <ul>
                            <li>
                                <input type="checkbox" id="c1" name="cc"/>
                                <label for="c1">Can I book a test drive?</label>
                            </li>
                            <li>
                                <input type="checkbox" id="c2" name="cc"/>
                                <label for="c2">What is your adress and opening hours?</label>
                            </li>
                            <li>
                                <input type="checkbox" id="c3" name="cc"/>
                                <label for="c3">Other?</label>
                            </li>
                        </ul>
                        <div class="advanced-button">
                            <a href="#">Send enquiry <i class="fa fa-paper-plane"></i></a>
                        </div>
                    </div>
                    <div class="subhead-side-bar">
                        <h4>Contact the Seller</h4>
                    </div>
                    <div class="call-info">
                        <i class="fa fa-phone"></i>
                        <h6>816-819-0221</h6>
                        <p>Car code: <span>55637</span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@include('partials.featured-listing')


@include('partials.cta-2')


@endsection