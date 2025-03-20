<?php

namespace App\Filament\Resources\AuctionResource\Widgets;

use App\Models\Auction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Auctions', Auction::count())
                ->descriptionColor('success'),
            ];
    } 
}
