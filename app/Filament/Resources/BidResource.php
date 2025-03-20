<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BidResource\Pages;
use App\Filament\Resources\BidResource\RelationManagers;
use App\Models\Auction;
use App\Models\Bid;
use App\Models\Car;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Actions\Modal\Actions\Action;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BidResource extends Resource
{
    protected static ?string $model = Bid::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('amount')
                //     ->numeric()
                //     ->required()
                //     ->label('Amount'),
                // Forms\Components\TextInput::make('status')
                //     //if in view mode, set the value to be the current status of the record...check if the record is not null
                //     ->default(function ($record) {
                //         if ($record) {
                //             return $record->status;
                //         }
                //     })
                //     ->required()
                //     ->label('Status'),
                // Forms\Components\TextInput::make('user_id')
                //     ->default(function ($record) {
                //         if ($record) {
                //             $user = User::where('id', $record->user_id)->first();
                //             return $user->name;
                //             // return $record->user_id;
                //         }
                //     })
                //     ->required()
                //     ->label('User'),
                // Forms\Components\TextInput::make('car_id')
                //     ->default(function ($record) {
                //         if ($record) {
                //             $car = Car::where('id', $record->car_id)->first();
                //             return $car->make . ' ' . $car->model;
                //             // return $record->car_id;
                //         }
                //     })
                //     ->required()
                //     ->label('Car'),
                // Forms\Components\TextInput::make('auction_id')
                //     ->default(function ($record) {
                //         if ($record) {
                //             $auction = Auction::where('id', $record->auction_id)->first();
                //             return $auction->name;
                //             // return $record->auction_id;
                //         }
                //     })
                //     ->required()
                //     ->label('Auction'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //display the name of the user by getting the user id and then getting the name from the users table
                Tables\Columns\TextColumn::make('user_id')->formatStateUsing(function ($state) {
                    return User::where('id', $state)->first()->name;
                })->label('User'),

                //display the car bidded on by getting the car id and then getting the make and model from the cars table
                Tables\Columns\TextColumn::make('car_id')->formatStateUsing(function ($state) {
                    return Car::where('id', $state)->first()->make . ' ' . Car::where('id', $state)->first()->model;
                })->label('Car'),

                //display the name of the auction by getting the auction id and then getting the name from the auctions table
                Tables\Columns\TextColumn::make('auction_id')->formatStateUsing(function ($state) {
                    return Auction::where('id', $state)->first()->name;
                })->label('Auction'),

                //display the amount of the bid
                Tables\Columns\TextColumn::make('amount')->label('Amount'),

                //display the status of the bid
                Tables\Columns\TextColumn::make('status')->label('Status'),
                
                
                
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
                // Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    // Override the `getEloquentQuery` method to modify the query
    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $userId = $user->id;
        $role = $user->getRoleAttribute();
        
        
        if ($role === 'admin') {
            return parent::getEloquentQuery();
        } elseif ($role === 'vendor'){
            //get all cars owned by the vendor
            //a vendor should be able to see bids made by customers on their cars

            $cars = Car::where('vendor_id', $userId)->pluck('id');
            return parent::getEloquentQuery()
                ->whereIn('car_id', $cars)
                ->orWhere('user_id', $userId);

        }
        else{
            return parent::getEloquentQuery()
                ->where('user_id', $userId);
        }
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBids::route('/'),
            // 'create' => Pages\CreateBid::route('/create'),
            'edit' => Pages\EditBid::route('/{record}/edit'),
            'show' => Pages\ShowBid::route('/{record}'),
        ];
    }    

    //remove create action
    protected function getCreateFormAction(): ?Action
    {
        return null;
    }
}
