<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $table = 'features';

    protected $fillable = [
        'name',
        'slug',
        'category',
    ];

    //relationship with cars
    public function cars()
    {
        return $this->belongsToMany(Car::class, null, null, null, 'features');
    }
}
