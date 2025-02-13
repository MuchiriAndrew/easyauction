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
        ];
    }
}
