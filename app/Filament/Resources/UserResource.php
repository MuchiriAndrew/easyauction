<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationLabel = 'Users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Name'),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Email'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->label('Password')
                    ->dehydrateStateUsing(fn($state) => bcrypt($state))
                    ->required(fn($record) => $record === null), // Required on create, optional on edit

                // Forms\Components\Select::make('role')
                //     ->options([
                //         'admin' => 'Admin',
                //         'vendor' => 'Vendor',
                //         'bidder' => 'Customer',
                //     ])
                //     ->label('Role'),
                Forms\Components\Select::make('roles')
                        ->relationship('roles', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable(),
                    // ->options([
                    //     'admin' => 'Admin',
                    //     'vendor' => 'Vendor',
                    //     'bidder' => 'Bidder',
                    // ])
                    // ->label('Role'),

                // Forms\Components\CheckboxList::make('roles')
                //     ->relationship('roles', 'name')
                //     ->searchable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Name'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\BadgeColumn::make('roles.name')
                    ->label('User Roles')
                    ->colors([
                        'danger' => 'Super Admin',
                        'warning' => 'Vendor',
                        'success' => 'Customer',
                    ]),
                // Tables\Columns\TextColumn::make('roles.name')->label('User Role'),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')->dateTime(),
            ])
            ->filters([
                // Add table filters if needed
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
