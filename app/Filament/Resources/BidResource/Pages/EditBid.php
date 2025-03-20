<?php

namespace App\Filament\Resources\BidResource\Pages;

use App\Filament\Resources\BidResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBid extends EditRecord
{
    protected static string $resource = BidResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
