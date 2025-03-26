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
            <!-- Add Navigation -->
            {{-- <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div> --}}
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div>
        <div class="down-content rounded-b-md">
            <a href="{{ $link }}">
                <h2>{{ $title }}</h2>
            </a>
            {{-- <span>{{ $price }}</span> --}}
            <div class="light-line"></div>
            <p>{{ $description }}</p>
            {{-- <div>Auction Ends In: <div class="text-green-400 ">
                {{ $end_time }} 
            </div>
            </div> --}}
            <span id="countdown-timer"></span>


            {{-- @if ($end_time < now())
                <div class="text-red-400 font-bold text-end auction-status">Auction Ended</div>
            @else
                <div class="text-green-400 font-bold text-end auction-status">Auction Open</div>
            @endif --}}
        </div>

    </div>
</a>

<style>
    .down-content {
        /* background: aqua; */
        height: auto !important;
        min-height: 200px !important;
        border-bottom: 2px solid #f1f1f1;

    }

    .auction-status {
        margin-top: 10px !important;
    }
</style>


<script>
    // Parse the end time from the server
    const endTime = new Date("{{ $end_time }}").getTime();
    var status = "{{ $auction->status }}";
    console.log(status);

    // Function to update the countdown timer
    function updateCountdown() {
        const now = new Date().getTime();
        const timeLeft = endTime - now;

        if (timeLeft <= 0 || status == 'closed') {
            document.getElementById('countdown-timer').innerText = "Auction Ended";
            clearInterval(timerInterval); // Stop the timer
            return;
        }

        // Calculate days, hours, minutes, and seconds
        const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
        const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

        // Update the countdown display
        document.getElementById('countdown-timer').innerText =
            `ENDS IN ${days}d ${hours}h ${minutes}m ${seconds}s`;
    }

    // Update the countdown every second
    const timerInterval = setInterval(updateCountdown, 1000);

    // Initialize the countdown immediately
    updateCountdown();
</script>
