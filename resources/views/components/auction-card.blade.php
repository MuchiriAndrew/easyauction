@php
    // $photoPaths = $cars->pluck('photo_path');
    // dd($photoPaths);
    // dd($cars, $auction->status);
    $title = $auction->name;
    $description = $auction->description;
    $end_time = $auction->end_time;
    $highest = $auction->current_bid ?? '0.00';
    $photoPaths = [];
    foreach ($cars as $car) {
        $photoPaths[] = $car->photo_path;
    }
    // dd($photoPaths, $link);
    
@endphp
<a href="{{ $link }}">
    <div class="featured-item col-md-4">

        <style>
            .featured-item {
                /* background: red; */
                height: auto !important;
                min-height: 400px !important;
                max-height: 400px !important;
               
               
                /* margin: 10px !important;
                padding: 10px; */
            }
        </style>


        {{-- {{$image}} --}}
        {{-- do a photo carousel here --}}
        <div class="swiper auction-carousel rounded-t-md">
            <div class="swiper-wrapper">
                @foreach ($photoPaths as $photoPath)
                    <div class="swiper-slide">
                        <img src="{{ asset('storage/' . $photoPath[0]) }}" alt="Auction Image"
                            class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>
            
            <div class="swiper-pagination"></div>
        </div>
        <div class="down-content rounded-b-md">
            <a href="{{ $link }}">
                <h2>{{ $title }}</h2>
            </a>
            {{-- <span>{{ $price }}</span> --}}
            <div class="light-line"></div>
            {{-- <p>{{ $description }}</p> --}}
            <p>{{ \Illuminate\Support\Str::limit($description, 120) }}</p>
            {{-- <div>Auction Ends In: <div class="text-green-400 ">
                {{ $end_time }} 
            </div>
            </div> --}}
            <span id="countdown-timer-{{$auction->name}}"></span>


            
        </div>

    </div>
</a>

<style>
    .down-content {
        /* background: aqua !important; */
        height: auto !important;
        min-height: 200px !important;
        border-bottom: 2px solid #f1f1f1 !important;
        

    }

    .auction-status {
        margin-top: 10px !important;
    }
</style>


<script>
    (function () {
        const endTime = new Date("{{ $end_time }}").getTime();
        const status = "{{ $auction->status }}";
        const timerElementId = `countdown-timer-{{$auction->name}}`;

        console.log("Auction Status:", status);
        console.log("End Time:", endTime);

        function updateCountdown() {
            const now = new Date().getTime();
            const timeLeft = endTime - now;

            if (timeLeft <= 0 && status === 'closed') {
                const timerElement = document.getElementById(timerElementId);
                if (timerElement) {
                    timerElement.innerText = "Auction Ended";
                }
                clearInterval(timerInterval);
                return;
            }

            if (status === 'pending') {
                const timerElement = document.getElementById(timerElementId);
                if (timerElement) {
                    timerElement.innerText = "Auction Pending";
                }
                clearInterval(timerInterval);
                return;
            }

            const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            const timerElement = document.getElementById(timerElementId);
            if (timerElement) {
                timerElement.innerText = `ENDS IN ${days}d ${hours}h ${minutes}m ${seconds}s`;
            }
        }

        const timerInterval = setInterval(updateCountdown, 1000);
        updateCountdown();
    })();
</script>