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
        'amount',
        'payment_status',
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
}
