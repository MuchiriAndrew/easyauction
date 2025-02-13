<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';

    protected $fillable = [
        'make',
        'model',
        'year',
        'mileage',
        'vin',
        'price',
        'description',
        'photo_path',
        'status',
    ];

    //define relationship with auction
    public function auction()
    {
        return $this->hasOne(Auction::class);
    }
}
