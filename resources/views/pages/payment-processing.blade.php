@extends('layouts.app')

@section('title', 'EasyAuction- Account Confirmation')

@section('content')

    @php
    @endphp



    <section class="mt-30 text-center flex justify-center items-center">
        @if ($errors->any() || session('error'))
            @php
                $error = session('error') ?? $errors->first();
            @endphp
            <div class="alert alert-danger error-messages col-md-12 mb-5 flex justify-center items-center">
                <p class="text-center text-white">
                    {{ $error }}
                </p>
            </div>
        @endif
        @if (session('success'))
            @php
                $message = session('success');
            @endphp
            <div class="alert alert-green success-messages col-md-12 mb-5 flex justify-center items-center">
                <p class="text-center text-white">
                    {{ $message }}
                </p>
            </div>
        @endif



        {{-- SPINNER --}}
        {{-- @if($payment_status != "PAID") --}}
        <span class="loader"></span>
        
        {{-- @else  --}}
        
        <svg class="hidden" id="success_check" xmlns="http://www.w3.org/2000/svg" width="220" height="220" fill="#77BC22"
            class="bi bi-check-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
            <path
                d="m10.97 4.97-.02.022-3.473 4.425-2.093-2.094a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05" />
        </svg>
        {{-- @endif --}}




        <br>
        <br>


        {{-- ADD A KINDLY WAIT AS WE PROCESS YOUR PAYMENT --}}
        @if($payment_status != "PAID")
        <div class="">
            Kindly wait as we process your payment..
        </div>
        @else 
        <div class="">
            We have received your payment. Thank you for using our platform!
        </div>
        @endif


    </section>


    <style>
        .loader {
            width: 220px;
            height: 220px;
            border: 10px solid #E8E8E8;
            border-bottom-color: #77BC22;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }


        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    @php
    // dd($payment_status);
    @endphp

    <script>
        //in this page i want to listen for a callback until i get a success => true
        //if success is true, setIsSuccessful to true
        //if success is false, setIsSuccessful to false


        var payment_status = "{{ $payment_status }}";
        console.log(payment_status);
        


        if (payment_status != "PAID") {

            document.addEventListener('DOMContentLoaded', function() {
                var url = "/poll-transaction-status/{{ $id }}";
                var intervalId;
                var elapsedTime = 0;
                // var intervalTime = 60000; // Interval time in milliseconds
                var intervalTime = 5000;

                intervalId = setInterval(() => {
                    elapsedTime += intervalTime;
                    if (elapsedTime >= 60000 ) { // 60 seconds
                        clearInterval(intervalId);
                        console.log("Stopped listening after 60 seconds.");

                        //redirect back to the previous page
                        // window.history.back();
                        //redirect to /mpesa-payment-failed


                        window.location.href = "/mpesa-payment-failed/{{$id}}";

                        //return back
                        
                    } else {
                        console.log("fetching callback");
                        
                        fetchCallback();
                    }
                }, intervalTime);



                function fetchCallback() {
                    fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);
                            if (data.status == 'success') {
                                console.log('success');
                                clearInterval(intervalId); // Stop the interval if success
 
                                //clear all sessionStorage
                                // sessionStorage.clear();

                                //reload the page after a 2 second delay
                                setTimeout(() => {
                                    window.location.reload();//reload so as to fetch the documents and attachments and display them in the payment-successful page
                                }, 2000);



                                //set loader to hidden and set display none and remove the keyframes rotation
                                document.querySelector('.loader').classList.add('hidden');
                                document.querySelector('.loader').style.display = 'none';
                                document.querySelector('.loader').style.animation = 'none';



                                document.getElementById('success_check').classList.remove('hidden');
                                document.querySelector('.loader').classList.add('hidden');
                                document.getElementById('success_check').classList.remove('hidden');

                                
                                setTimeout(() => {
                                    //hide the processing id div and show the success div
                                    if(document.getElementById('processing')) {
                                        document.getElementById('processing').classList.add('hidden');
                                    }
                                    // document.getElementById('processing').classList.add('hidden');
                                    document.getElementById('success').classList.remove('hidden');

                                }, 3000);

                                //redirect to /admin
                                window.location.href = "/admin";


                            } else if(data.status == 'failed') {
                                console.log('failed');
                                clearInterval(intervalId); // Stop the interval if failed

                                //redirect back to the payment-plan with a message
                                window.location.href = "/mpesa-payment-failed/{{$id}}";
                            } else {
                                console.log('pending');
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                }
            });
            
        } else {

        }
        document.addEventListener('DOMContentLoaded', function() {
            // console.log("contentloaded");
            
            //check if the payment status is paid
            if (payment_status == "PAID") {
                console.log('payment status is paid');
                
                //hide the processing id div and show the success div
                // document.getElementById('processing').classList.add('hidden');
                // document.getElementById('success').classList.remove('hidden');

                console.log('success');

                //set loader to hidden and set display none and remove the keyframes rotation
                document.querySelector('.loader').classList.add('hidden');
                document.querySelector('.loader').style.display = 'none';
                document.querySelector('.loader').style.animation = 'none';

                document.getElementById('success_check').classList.remove('hidden');

                document.querySelector('.loader').classList.add('hidden');
                document.getElementById('success_check').classList.remove('hidden');

                //clear all sessionStorage
                sessionStorage.clear();


                setTimeout(() => {
                    //hide the processing id div and show the success div
                    document.getElementById('processing').classList.add('hidden');
                    document.getElementById('success').classList.remove('hidden');
                    


                }, 3000);
            } else if(payment_status == "FAILED") {
                console.log('payment status is failed');

                //redirect back to the payment-plan with a message
                window.location.href = "/mpesa-payment-failed/{{$id}}";
            }
        });


    </script>


@endsection
