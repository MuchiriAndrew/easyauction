<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $table = 'auctions';

    protected $fillable = [
        'car_id',
        'start_price',
        'reserve_price',
        'status',
        'start_time',
        'end_time',
    ];

    //define rlationship with car
    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
