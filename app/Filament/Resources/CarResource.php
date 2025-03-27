<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Models\Car;
use Filament\Forms;
use Filament\Forms\Components\TextInput\Mask;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\TemporaryUploadedFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

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
                        'station-wagon' => 'Station Wagon',
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
                    //VALIDATE TO THIS FORMAT KCD 536T
                    // ->pattern('[A-Z]{3}\s\d{3}[A-Z]{1}')
                    ->required()
                    ->label('VIN (Vehicle Identification Number)'),
                //ADD PLACEHOLDER FOR MILEAGE
                Forms\Components\TextInput::make('mileage')
                    ->numeric()
                    ->required()
                    ->mask(fn(Mask $mask) => $mask
                        ->patternBlocks([
                            'money' => fn(Mask $mask) => $mask
                                ->numeric()
                                ->thousandsSeparator(',')
                                ->decimalSeparator('.'),
                        ])
                        ->pattern('money'))
                    ->placeholder('Insert mileage in kilometers')
                    ->extraAttributes(['oninput' => 'formatNumber(this)'])
                    ->label('Mileage (in KM)'),


                Forms\Components\TextInput::make('price')
                    ->numeric()
                    ->mask(fn(Mask $mask) => $mask
                        ->patternBlocks([
                            'money' => fn(Mask $mask) => $mask
                                ->numeric()
                                ->thousandsSeparator(',')
                                ->decimalSeparator('.'),
                        ])
                        ->pattern('money'))
                    ->required()
                    ->label('Starting Price (A bid can not go lower than this)')
                    ->extraAttributes(['oninput' => 'formatNumber(this)']),

                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'inactive' => 'Inactive',
                        'approved' => 'Approved',
                        'SOLD' => 'Sold',
                    ])
                    ->default('pending')
                    ->label('Status')
                    ->visible(fn() => Auth::user()->getRoleAttribute() === 'admin'),



                    //do a multiple select form for car features
                Forms\Components\Select::make('features')
                ->multiple()
                ->options([
                    'air_conditioning' => 'Air Conditioning',
                    'power_steering' => 'Power Steering',
                    'power_windows' => 'Power Windows',
                    'power_locks' => 'Power Locks',
                    'anti_lock_brakes' => 'Anti-Lock Brakes',
                    'driver_airbag' => 'Driver Airbag',
                    'passenger_airbag' => 'Passenger Airbag',
                    'side_airbags' => 'Side Airbags',
                    'alarm' => 'Alarm',
                    'immobilizer' => 'Immobilizer',
                    'cruise_control' => 'Cruise Control',
                    'navigation_system' => 'Navigation System',
                    'cd_player' => 'CD Player',
                    'mp3_player' => 'MP3 Player',
                    'bluetooth' => 'Bluetooth',
                    'back_up_camera' => 'Back-up Camera',
                    'sunroof' => 'Sunroof',
                    'moonroof' => 'Moonroof',
                    'power_seats' => 'Power Seats',
                    'heated_seats' => 'Heated Seats',
                    'leather_seats' => 'Leather Seats',
                    'keyless_entry' => 'Keyless Entry',
                    'keyless_start' => 'Keyless Start',
                    'remote_start' => 'Remote Start',
                    'tow_package' => 'Tow Package',
                    'roof_rack' => 'Roof Rack',
                    'alloy_wheels' => 'Alloy Wheels',
                    'third_row_seats' => 'Third Row Seats',
                    'parking_sensors' => 'Parking Sensors',
                    'blind_spot_monitor' => 'Blind Spot Monitor',
                    'lane_departure_warning' => 'Lane Departure Warning',
                    'adaptive_cruise_control' => 'Adaptive Cruise Control',
                    'apple_carplay' => 'Apple CarPlay',
                    'android_auto' => 'Android Auto',
                    'wifi_hotspot' => 'WiFi Hotspot',
                    'wireless_phone_charging' => 'Wireless Phone Charging',
                    'hands_free_liftgate' => 'Hands-Free Liftgate',
                    'power_sliding_doors' => 'Power Sliding Doors',
                    'power_liftgate' => 'Power Liftgate',
                    'heated_steering_wheel' => 'Heated Steering Wheel',
                    'heated_mirrors' => 'Heated Mirrors',
                ])
                ->label('Features')
                ->default([]),


                
                Forms\Components\FileUpload::make('photo_path')
                    ->label('Car Image')
                    ->required()
                    ->directory('car-images')
                    ->image()
                    ->multiple()
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->visibility('public')
                    ->preserveFilenames()
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file) {
                        return (string) str($file->getClientOriginalName())->prepend('car-images/');
                    })
                    ->getUploadedFileUrlUsing(function ($file) {
                        return Storage::url($file);
                    }),

                    Forms\Components\Textarea::make('description')
                    ->label('Description'),



                //create a hidden field that will store the vendor id from the authenticated user
                Forms\Components\Hidden::make('vendor_id')
                    ->default(auth()->id()),


                
            
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

                // Add a column to display the vendor id and link to the vendor profile
                Tables\Columns\TextColumn::make('vendor_id')
                    ->label('Vendor')
                    ->formatStateUsing(fn($record) => $record->vendor->name ?? ''),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'approved',
                        'warning' => 'pending',
                        'danger' => 'SOLD',
                        'warning' => 'inactive',
                    ]),

                Tables\Columns\TextColumn::make('photo_path')
                    ->label('Vehicle Image')
                    ->url(fn($record) => asset('storage/' . Arr::first($record->photo_path))) // Use Arr::first to get the first element
                    ->formatStateUsing(fn($state) => 'View Image'),

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

    // Override the `getEloquentQuery` method to modify the query
    public static function getEloquentQuery(): Builder
    {
        $user = auth()->user();
        $userId = $user->id;
        $role = $user->getRoleAttribute();


        if ($role === 'admin') {
            return parent::getEloquentQuery();
        } elseif ($role === 'vendor') {
            return parent::getEloquentQuery()
                ->where('vendor_id', $userId);
        }
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
