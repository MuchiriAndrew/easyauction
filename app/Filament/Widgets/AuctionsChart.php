<?php

namespace App\Filament\Widgets;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Car;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Facades\DB;

class AuctionsChart extends BarChartWidget
{
    protected static ?string $minWidth = '100%'; // Ensures the widget spans full width

    protected function getHeading(): ?string
    {
        $user = auth()->user();

        if (!$user) {
            return 'Auctions'; // Default heading if user is not authenticated
        }

        $role = $user->getRoleAttribute();

        if ($role === 'admin'){
            return 'Auctions created per month';
        } elseif ($role === 'customer'){
            return 'Auctions bidded on';
        } elseif ($role === 'vendor'){
            return 'Auctions sold on in the year ' . now()->year;
        }
    }


    protected function getData(): array
    {
        $user = auth()->user(); 
        $role = $user->getRoleAttribute();

        if ($role === 'admin'){
            return [
                'datasets' => [
                [
                    'label' => 'Auctions created',
                    'data' => Auction::select(DB::raw('COUNT(*) as count'))
                        ->whereYear('created_at', now()->year)
                        ->groupBy(DB::raw('MONTH(created_at)'))
                        ->pluck('count'),
                    'backgroundColor' => '#64ffda',
                    'borderColor' => '#0000',
                ],
            ],
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            'options' => [
                'scales' => [
                    'y' => [
                        'beginAtZero' => true,
                    ],
                ],
            ],
        ];
        } 
        elseif ($role === 'customer'){
            //do a bar chart for the number of auctions the customer has bidded on
            // dd(Bid::where('user_id', $user->id)->pluck('auction_id')->unique()->count());
            return [
                'datasets' => [
                    [
                        'label' => 'Auctions bidded on',
                        'data' => Bid::where('user_id', $user->id)->pluck('auction_id')->unique(),
                        'backgroundColor' => '#64ffda',
                        'borderColor' => '#0000',
                    ],
                ],
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'options' => [
                    'scales' => [
                        'y' => [
                            'beginAtZero' => true,
                        ],
                    ],
                ],
                
            ];
        } 
        elseif ($role === 'vendor'){
            //do a bar chart for the number of auctions the vendors cars have been in
            $cars = Car::where('vendor_id', $user->id)->pluck('id');
            $auctions = [];
            foreach ($cars as $car) {
                // dd($car);
                $auctions[] = Auction::where(function($query) use ($car) {
                    $query->whereRaw("JSON_CONTAINS(car_ids, '\"$car\"')");
                })->pluck('id')->unique()->count();
            }
            
            return [
                'datasets' => [
                    [
                        'label' => 'Auctions sold in ' . now()->year,
                        'data' => $auctions,
                        'backgroundColor' => '#54ffda',
                        'borderColor' => '#0000',
                    ],
                ],
                'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'options' => [
                    'scales' => [
                        'y' => [
                            'beginAtZero' => true,
                        ],
                    ],
                ],
            ];
        }
    }
}
