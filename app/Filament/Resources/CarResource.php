<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Models\Car;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\TemporaryUploadedFile;
use Illuminate\Database\Eloquent\Builder;


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
                        'pending' => 'Pending',
                        'inactive' => 'Inactive',
                        'approved' => 'Approved',
                    ])
                    ->default('pending')
                    ->label('Status')
                    ->visible(fn() => Auth::user()->getRoleAttribute() === 'admin'),


                Forms\Components\Textarea::make('description')
                    ->label('Description'),


                Forms\Components\FileUpload::make('photo_path')
                    ->label('Car Image')
                    ->directory('car-images')
                    ->image()
                    ->visibility('public')
                    ->preserveFilenames()
                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file) {
                        return (string) str($file->getClientOriginalName())->prepend('car-images/');
                    })
                    ->getUploadedFileUrlUsing(function ($file) {
                        return Storage::url($file);
                    }),






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
                    ->formatStateUsing(fn($record) => $record->vendor->name),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'approved',
                        'warning' => 'pending',
                        'danger' => 'inactive',
                    ]),

                Tables\Columns\TextColumn::make('photo_path')
                    ->label('Vehicle Image')
                    ->url(fn($record) => asset('storage/' . $record->photo_path))
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
