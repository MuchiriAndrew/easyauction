<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Auction;
use App\Models\Transaction;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //display the transaction date
                Tables\Columns\TextColumn::make('transaction_date')
                    ->dateTime('d-m-Y H:i:s')
                    ->label('Transaction Date')
                    //just show the date
                    ->date('d/m/Y')
                    ->sortable(),
                //get the user name from the user id
                Tables\Columns\TextColumn::make('user_id')
                    ->formatStateUsing(function ($state) {
                        return User::where('id', $state)->first()->name;
                    })
                    ->label('User'),
                
                //display the amount
                Tables\Columns\TextColumn::make('amount')
                    // ->numeric()
                    ->label('Amount'),

                //fetch the auction name from the auction id
                Tables\Columns\TextColumn::make('auction_id')
                    ->formatStateUsing(function ($state) {
                        return Auction::where('id', $state)->first()->name;
                    })
                    ->label('Auction'),


                //display the status in a badge
                Tables\Columns\BadgeColumn::make('payment_status')
                    ->colors([
                        'warning' => 'PENDING',
                        'danger' => 'FAILED',
                        'success' => 'PAID',
                    ])
                    ->label('Status'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
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
        } else {
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
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }    
}
