<?php

namespace App\Filament\Resources;

use App\Filament\Forms\Components\CommaDelimitedTextInput;
use App\Filament\Resources\AuctionResource\Pages;
use App\Models\Auction;
use Filament\Forms;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;


class AuctionResource extends Resource
{
    protected static ?string $model = Auction::class;
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationLabel = 'Auctions';
    protected static ?string $pluralLabel = 'Auctions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('car_id')
                    ->options(\App\Models\Car::all()->mapWithKeys(function ($car) {
                        $status = $car->status;
                        if ($status == 'approved') {
                            return [$car->id => "{$car->id}: {$car->make}-{$car->model}"];
                        } else {
                            return [];
                        }
                    }))
                    ->required()
                    ->label('Car (Only approved vehicles will be available for auction)' ),
                    
                Forms\Components\TextInput::make('start_price')
                    ->mask(fn(Mask $mask) => $mask
                        ->patternBlocks([
                            'money' => fn(Mask $mask) => $mask
                                ->numeric()
                                ->thousandsSeparator(',')
                                ->decimalSeparator('.'),
                        ])
                        ->pattern('money'))
                    ->required()
                    ->placeholder('Initial price at which the bidding starts')
                    ->label('Starting Price'),


                Forms\Components\TextInput::make('reserve_price')
                    ->mask(fn(Mask $mask) => $mask
                        ->patternBlocks([
                            'money' => fn(Mask $mask) => $mask
                                ->numeric()
                                ->thousandsSeparator(',')
                                ->decimalSeparator('.'),
                        ])
                        ->pattern('money'))
                    ->placeholder('Minimum price that the seller is willing to accept for the item')
                    ->label('Reserve Price'),


                Forms\Components\Select::make('status')
                    ->options([
                        'open' => 'Open',
                        'closed' => 'Closed',
                        'pending' => 'Pending',
                    ])
                    ->required()
                    ->label('Status'),
                Forms\Components\DateTimePicker::make('start_time')
                    ->required()
                    ->label('Start Time'),
                Forms\Components\DateTimePicker::make('end_time')
                    ->required()
                    ->label('End Time'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('car_id')->label('Vehicle'),
                Tables\Columns\TextColumn::make('car.make')->label('Make'),
                Tables\Columns\TextColumn::make('car.model')->label('Model'),
                // Tables\Columns\TextColumn::make('start_price')->label('Starting Price'),
                // Tables\Columns\TextColumn::make('reserve_price')->label('Reserve Price'),

                Tables\Columns\TextColumn::make('start_price')
                    ->label('Starting Price')
                    ->formatStateUsing(fn($state) => number_format($state, 2)),

                Tables\Columns\TextColumn::make('reserve_price')
                    ->label('Reserve Price')
                    ->formatStateUsing(fn($state) => number_format($state, 2)),


                Tables\Columns\TextColumn::make('status')->label('Status'),
                Tables\Columns\TextColumn::make('start_time')->label('Start Time'),
                Tables\Columns\TextColumn::make('end_time')->label('End Time'),
                Tables\Columns\TextColumn::make('car.photo_path')
                    ->label('Vehicle Image')
                    ->url(fn($record) => asset('storage/' . $record->car->photo_path))
                    //url very long, just show text of 'Image Link'
                    ->formatStateUsing(fn($state) => 'Image Link'),

            ])->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'open' => 'Open',
                        'closed' => 'Closed',
                        'pending' => 'Pending',
                    ])
                    ->label('Auction Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }


    public static function getRelations(): array
    {
        return [
            // Add relation managers if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuctions::route('/'),
            'create' => Pages\CreateAuction::route('/create'),
            'edit' => Pages\EditAuction::route('/{record}/edit'),
            'show' => Pages\ShowAuction::route('/{record}'),

        ];
    }
}
