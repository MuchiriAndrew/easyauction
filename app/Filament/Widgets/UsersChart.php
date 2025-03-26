<?php

namespace App\Filament\Widgets;

use App\Models\Auction;
use App\Models\User;
use Filament\Widgets\BarChartWidget;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;

class UsersChart extends BarChartWidget
{
    protected static ?string $heading = 'Users registered per month';
    protected static ?string $minWidth = '100%';

    // protected $user = auth()->user();


    //if the user is not an admin, dont display the chart
    public function shouldDisplay(): bool
    {
        $user = auth()->user();
        $role = $user->getRoleAttribute();
        return $role === 'admin';
    }



    protected function getData(): array
    {
        $user = auth()->user();
        $role = $user->getRoleAttribute();

        if ($role === 'admin'){
            return [

            //lets do a bar chart for the number of users registered per month
            'datasets' => [
                [
                    'label' => 'Users registered',
                    'data' => User::select(DB::raw('COUNT(*) as count'))
                        ->whereYear('created_at', now()->year)
                        ->groupBy(DB::raw('MONTH(created_at)'))
                        ->pluck('count'),
                        'backgroundColor' => '#36A2EB',
                        'borderColor' => '#9BD0F5',
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

    // protected function getFilters(): ?array
    // {
    //     return [
    //         'today' => 'Today',
    //         'week' => 'Last week',
    //         'month' => 'Last month',
    //         'year' => 'This year',
    //     ];
    // }
}
