<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class VehicleCard extends Component
{
    public $image;
    public $link;
    public $title;
    public $price;
    public $description;
    public $fuel;
    public $type;
    public $mileage;

    public function __construct(
        $image = 'assets/images/featured-vehicle.jpg',
        $link = '/single-view',
        $title = 'Default Title',
        $price = '0',
        $description = 'Default description.',
        $fuel = 'Fuel Type',
        $type = 'Car Type',
        $mileage = '0'
    ) {
        $this->image = $image;
        $this->link = $link;
        $this->title = $title;
        $this->price = $price;
        $this->description = $description;
        $this->fuel = $fuel;
        $this->type = $type;
        $this->mileage = $mileage;
    }

    public function render()
    {
        return view('components.vehicle-card');
    }
}