<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    protected $fillable = [
        'auction_id',
        'bid_id',
        'user_id',
        'car_id',
        'transaction_details',
        'amount',
        'payment_status',
        'merchant_request_id',
        'callback',
        'transaction_date',
    ];

    //relationship with auction
    public function auction() {
        return $this->belongsTo(Auction::class, 'auction_id', 'id');
    }

    //relationship with bid
    public function bid() {
        return $this->belongsTo(Bid::class, 'bid_id', 'id');
    }

    //relationship with user
    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    //relationship with car
    public function car() {
        return $this->belongsTo(Car::class, 'car_id', 'id');
    }
}
