<a href="{{ $link }}">
<div class="featured-item col-md-4">
    {{-- @php
        dd($auction);
    @endphp --}}

    {{-- {{$image}} --}}
    {{-- {{$auction->name}} --}}
        <img src="{{ asset($image) }}" alt="">
        <div class="down-content">
            <a href="{{ $link }}"><h2>{{ $title }}</h2></a>
            {{-- <span>{{ $price }}</span> --}}
            <div class="light-line"></div>
            {{-- <p>{{ $description }}</p> --}}
            {{-- limit the description to 150 characters and if not completed add a read more link --}}
            <p>{{ Str::limit($description, 150) }}</p>
            
            
            {{-- <span class="countdown-timer" ></span> --}}
            
            <span class="highest">Highest Bid: KSH {{$highest}}  </span>
            {{-- <div class="car-info">
                <ul>
                    <li><i class="icon-gaspump"></i>{{ $fuel }}</li>
                    <li><i class="icon-car"></i>{{ $type }}</li>
                    <li><i class="icon-road2"></i>{{ $mileage }}</li>
                </ul>
            </div> --}}
        </div>

</div>
    </a>

<style>
    .highest {
        background-color: #f4c23d !important;
        margin-top: 10px
    }
   
    .down-content {
        /* background: aqua; */
        height: auto !important;
        min-height: 200px !important;
        border-bottom: 2px solid #f1f1f1;

    }
</style>


{{-- <script>
    (function () {
        // Parse the end time and status from the server
        const endTime = new Date("{{ $auction->end_time }}").getTime();
        const status = "{{ $auction->status }}";
        const timerElementId = `countdown-timer-{{$auction->id}}`; // Use auction ID for unique element IDs

        console.log("Auction Status:", status);
        console.log("End Time:", endTime);

        // Function to update the countdown timer
        function updateCountdown() {
            const now = new Date().getTime();
            const timeLeft = endTime - now;

            // Handle auction closed or expired
            if (timeLeft <= 0 || status === 'closed') {
                const timerElement = document.getElementById(timerElementId);
                if (timerElement) {
                    timerElement.innerText = "Auction Ended";
                }
                clearInterval(timerInterval); // Stop the timer
                return;
            }

            // Handle auction pending
            if (status === 'pending') {
                const timerElement = document.getElementById(timerElementId);
                if (timerElement) {
                    timerElement.innerText = "Auction Pending";
                }
                clearInterval(timerInterval); // Stop the timer
                return;
            }

            // Calculate days, hours, minutes, and seconds
            const days = Math.floor(timeLeft / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            // Update the countdown display
            const timerElement = document.getElementById(timerElementId);
            if (timerElement) {
                timerElement.innerText = `ENDS IN ${days}d ${hours}h ${minutes}m ${seconds}s`;
            }
        }

        // Update the countdown every second
        const timerInterval = setInterval(updateCountdown, 1000);

        // Initialize the countdown immediately
        updateCountdown();
    })();
</script> --}}