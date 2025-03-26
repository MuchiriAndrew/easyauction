<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $table = 'cars';

    protected $fillable = [
        'vendor_id',
        'make',
        'model',
        'color',
        'year',
        'mileage',
        'vin',
        'price',
        'description',
        'photo_path',
        'status',
        'features',
    ];

    protected $casts = [
        'features' => 'array',
        'photo_path' => 'array',
    ];

    //define relationship with auction
    public function auction()
    {
        return $this->hasOne(Auction::class);
    }

    //relationsip with vendor
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id', 'id');
    }

    //relationship with features
    public function features()
    {
        return $this->hasMany(Feature::class);
    }
}
