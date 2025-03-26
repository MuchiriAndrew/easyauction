<?php

namespace App\Filament\Resources\BidResource\Pages;

use App\Filament\Resources\BidResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBid extends CreateRecord
{
    protected static string $resource = BidResource::class;

    //remove create action
    //hook into the before save method
    protected function beforeSave(): void
    {
        return;
    }
}
