<?php

namespace App\Filament\Widgets;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Car;
use App\Models\Transaction;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverviewWidget extends BaseWidget
{
    protected function getCards(): array
    {
        $user = auth()->user();
        $role = $user->getRoleAttribute();

        if ($role === 'admin') {
            return [
                Card::make('Total Auctions', Auction::count())
                    ->descriptionColor('success'),

                Card::make('Total Cars', Car::count())
                    ->descriptionColor('info'),

                Card::make('Total Users', User::count())
                    ->descriptionIcon('heroicon-o-user'),

                Card::make('Total Bids', Bid::count()),
                    
                Card::make('Total Transactions', Transaction::count()),

            ];
        } elseif ($role === 'vendor') {
            $cars = Car::where('vendor_id', $user->id)->pluck('id');
            return [
                Card::make('Total Cars Registered', Car::where('vendor_id', $user->id)->count())
                    ->descriptionColor('info'),


                Card::make('Total Bids on Cars Registered', Bid::whereIn('car_id', $cars)->count())
                    ->descriptionColor('success'),
            ];
        } else {
            return [
                Card::make('Total Bids Made', Bid::where('user_id', $user->id)->count()),

                //a card on the total cars bidded on
                Card::make('Total Cars Bidded On', Bid::where('user_id', $user->id)->pluck('car_id')->unique()->count())
                    ->descriptionColor('success'),

                Card::make('Total Transactions', Transaction::where('user_id', $user->id)->count())
                    ->descriptionColor('info'),
            ];
        }
    }
}
