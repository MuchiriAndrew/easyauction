<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Models\Car;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;
    protected static ?string $navigationIcon = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Cars';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('make')
                    ->required()
                    ->label('Make'),
                Forms\Components\TextInput::make('model')
                    ->required()
                    ->label('Model'),
                //add a form for car style: saloon, suv , etc  
                Forms\Components\Select::make('style')
                    ->options([
                        'saloon' => 'Saloon',
                        'suv' => 'SUV',
                        'hatchback' => 'Hatchback',
                        'coupe' => 'Coupe',
                        'convertible' => 'Convertible',
                        'estate' => 'Estate',
                        'mpv' => 'MPV',
                        'pickup' => 'Pickup',
                        'van' => 'Van',
                        'minibus' => 'Minibus',
                        'campervan' => 'Campervan',
                        'limousine' => 'Limousine',
                        'other' => 'Other',
                    ])
                    ->label('Style'),
                //add a form for car transmission: automatic, manual, etc
                Forms\Components\Select::make('transmission')
                    ->options([
                        'automatic' => 'Automatic',
                        'manual' => 'Manual',
                    ])
                    ->label('Transmission'),
                //add a form for car fuel type: petrol, diesel, etc
                Forms\Components\Select::make('fuel_type')
                    ->options([
                        'petrol' => 'Petrol',
                        'diesel' => 'Diesel',
                        'electric' => 'Electric',
                        'hybrid' => 'Hybrid',
                        'other' => 'Other',
                    ])
                    ->label('Fuel Type'),
                //add a form for car color: red, blue, etc
                Forms\Components\Select::make('color')
                    ->options([
                        'red' => 'Red',
                        'blue' => 'Blue',
                        'green' => 'Green',
                        'yellow' => 'Yellow',
                        'black' => 'Black',
                        'white' => 'White',
                        'silver' => 'Silver',
                        'grey' => 'Grey',
                        'brown' => 'Brown',
                        'orange' => 'Orange',
                        'purple' => 'Purple',
                        'pink' => 'Pink',
                        'other' => 'Other',
                    ])
                    ->label('Color'),
                Forms\Components\TextInput::make('year')
                    ->numeric()
                    ->required()
                    ->label('Year'),
                Forms\Components\TextInput::make('vin')
                    ->unique(ignoreRecord: true)
                    ->label('VIN (Vehicle Identification Number)'),
                    //ADD PLACEHOLDER FOR MILEAGE
                Forms\Components\TextInput::make('mileage')
                    ->numeric()
                    ->required()
                    ->placeholder('Insert mileage in kilometers')
                    ->extraAttributes(['oninput' => 'formatNumber(this)'])
                    ->label('Mileage'),


                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->required()
                    ->label('Price')
                    ->extraAttributes(['oninput' => 'formatNumber(this)']),
            
                Forms\Components\Select::make('status')
                    ->options([
                        'approved' => 'Approved',
                        'pending' => 'Pending',
                        'inactive' => 'Inactive',
                    ])
                    ->default('pending')
                    ->label('Status'),
                Forms\Components\FileUpload::make('photo_path')
                    ->label('Car Image')
                    ->directory('car-images'),
                Forms\Components\Textarea::make('description')
                    ->label('Description'),
                //add for mileage
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('make')->label('Make'),
                Tables\Columns\TextColumn::make('model')->label('Model'),
                Tables\Columns\TextColumn::make('year')->label('Year'),
                Tables\Columns\TextColumn::make('vin')->label('VIN'),
                Tables\Columns\TextColumn::make('status')->label('Status'),
                // Tables\Columns\ImageColumn::make('image')->label('Image'),
                Tables\Columns\TextColumn::make('photo_path')
                    ->label('Vehicle Image')
                    ->url(fn ($record) => asset('storage/' . $record->photo_path))
                    //url very long, just show text of 'Image Link'
                    ->formatStateUsing(fn ($state) => 'Image Link'),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')->dateTime(),
                
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'approved' => 'Approved',
                        'pending' => 'Pending',
                        'inactive' => 'Inactive',
                    ])
                    ->label('Car Status'),
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
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
            'show' => Pages\ShowCar::route('/{record}'),
        ];
    }
}
