<?php

namespace App\Filament\Resources\AuctionResource\Pages;

use App\Filament\Resources\AuctionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAuction extends CreateRecord
{
    protected static string $resource = AuctionResource::class;

    //i want to dd the request after the form is submitted
    // protected function afterCreate(): void
    // {
    //     dd($this->data);
    // }


    protected function beforeCreate(): void
    {
        // Convert car_ids array to JSON string before saving
        if (isset($this->data['car_ids']) && is_array($this->data['car_ids'])) {
            $this->data['car_ids'] = json_encode($this->data['car_ids']);
        }
    }

    protected function afterCreate(): void
    {
        // You can add any post-creation logic here if needed
    }
}
