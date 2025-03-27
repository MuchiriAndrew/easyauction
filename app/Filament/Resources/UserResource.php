<?php

namespace App\Filament\Resources;

use App\Exports\UsersExport;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Actions\BulkAction;
use Maatwebsite\Excel\Facades\Excel;

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

                //add field for phone number(optional)
                Forms\Components\TextInput::make('phone_number')
                    ->label('Phone Number')
                    ->unique(ignoreRecord: true)
                    ->placeholder('0712345678')
                    ->maxLength(10)
                    ->minLength(10),


                Forms\Components\TextInput::make('password')
                    ->password()
                    ->label('Password')
                    ->dehydrateStateUsing(fn($state) => bcrypt($state))
                    ->required(),


                Forms\Components\Select::make('roles')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable(),



            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label('Name'),
                Tables\Columns\TextColumn::make('email')->label('Email'),
                Tables\Columns\TextColumn::make('phone_number')->label('Phone Number'),
                Tables\Columns\BadgeColumn::make('roles.name')
                    ->label('User Roles')
                    ->colors([
                        'danger' => 'Admin',
                        'warning' => 'Vendor',
                        'success' => 'Customer',
                    ]),
                // Tables\Columns\BadgeColumn::make('email_verified')
                //     ->label('Email Verified')
                //     ->enum([
                //         0 => 'No',
                //         1 => 'Yes',
                //     ])
                //     ->colors([
                //         'success' => 1,
                //         'danger' => 0,
                //     ]),
                Tables\Columns\TextColumn::make('created_at')->label('Created At')->dateTime(),
            ])
            ->filters([
                // Add table filters if needed

                // Date range filter for transaction_date
            Tables\Filters\Filter::make('created_at')
            ->form([
                Forms\Components\DatePicker::make('from')->label('From Date'),
                Forms\Components\DatePicker::make('to')->label('To Date'),
            ])
            ->query(function ($query, array $data) {
                return $query
                    ->when($data['from'], fn ($query) => $query->whereDate('created_at', '>=', $data['from']))
                    ->when($data['to'], fn ($query) => $query->whereDate('created_at', '<=', $data['to']));
            })
            ->label('User Creation Range'),


            //add a filter for user roles
            // Tables\Filters\SelectFilter::make('roles.name')
            // ->options([
            //     'Admin' => 'Admin',
            //     'Vendor' => 'Vendor',
            //     'Customer' => 'Customer',
            // ])
            // ->label('User Roles'),


            //make a filter for the user roles


            //add a filter for email verification


            
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),

                // BulkAction::make('export')
                //     ->label('Download Transaction Report')
                //     ->action(function ($records) {
                //         $transactions = $records instanceof \Illuminate\Database\Eloquent\Collection
                //             ? $records
                //             : Transaction::whereIn('id', $records)->get();
            
                //         return Excel::download(new TransactionsExport($transactions), 'selected-transactions.xlsx');
                //     })
                //     ->icon('heroicon-o-download'),
                // ]);

                //do an export for users
                BulkAction::make('export')
                    ->label('Download User Report')
                    ->action(function ($records) {
                        $users = $records instanceof \Illuminate\Database\Eloquent\Collection
                            ? $records
                            : User::whereIn('id', $records)->get();
            
                        return Excel::download(new UsersExport($users), 'users.xlsx');
                    })
                    ->icon('heroicon-o-download'),


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
            'show' => Pages\ShowUser::route('/{record}'),
        ];
    }
}
