<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $table = 'auctions';

    protected $fillable = [
        'name',
        'car_ids',
        'description',
        'start_price',
        'reserve_price',
        'status',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'car_ids' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    // Define relationship with cars
    public function cars()
    {
        return $this->belongsToMany(Car::class, null, null, null, 'car_ids');
    }
}
