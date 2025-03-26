<?php

namespace App\Filament\Resources\CarResource\Pages;

use App\Filament\Resources\CarResource;
use App\Models\Car;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCars extends ListRecords
{
    protected static string $resource = CarResource::class;

    protected function getActions(): array
    {
        //add a filter that will return only cars that are owned by the logged in user
        $user = auth()->user();
        $userId = $user->id;
        $cars = Car::where('vendor_id', $userId)->get();
        $carIds = $cars->pluck('id')->toArray();
        // dd($carIds);

        



        return [
            Actions\CreateAction::make(),
        ];
    }
}
