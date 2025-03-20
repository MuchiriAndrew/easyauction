<a href="{{ $link }}">
<div class="featured-item col-md-4">
    

    {{-- {{$image}} --}}
        <img src="{{ asset($image) }}" alt="">
        <div class="down-content">
            <a href="{{ $link }}"><h2>{{ $title }}</h2></a>
            {{-- <span>{{ $price }}</span> --}}
            <div class="light-line"></div>
            {{-- <p>{{ $description }}</p> --}}
            {{-- limit the description to 150 characters and if not completed add a read more link --}}
            <p>{{ Str::limit($description, 150) }}</p>
            <p class="text-green-400">Auction Ends In: {{$end_time}} </p>
            <p class="text-green-400">Highest Bid: {{$highest}}  </p>
            
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
   
    .down-content {
        /* background: aqua; */
        height: auto !important;
        min-height: 200px !important;
        border-bottom: 2px solid #f1f1f1;

    }
</style>