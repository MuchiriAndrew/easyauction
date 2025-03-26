@php
    // $photoPaths = $cars->pluck('photo_path');
    // dd($photoPaths);
    // dd($cars);
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
                        <img src="{{ asset('storage/' . $photoPath) }}" alt="Auction Image" class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>
            <!-- Add Navigation -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
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
            <p class="text-green-400">Auction Ends In: {{ $end_time }} </p>

            @if($end_time < now())
                <p class="text-red-400">Auction Ended</p>
                @else
                {{-- <p class="text-green-400">Auction Open</p> --}}
                <div class="text-green-400">Auction Open</div>
                @endif
    
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

   
</style>
