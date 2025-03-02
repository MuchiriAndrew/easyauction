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
    public $end_time;
    public $highest;

    public function __construct(
        $image = 'assets/images/featured-vehicle.jpg',
        $link = '/single-view',
        $title = 'Default Title',
        $price = '0',
        $description = 'Default description.',
        $fuel = 'Fuel Type',
        $type = 'Car Type',
        $mileage = '0',
        $end_time = '2021-12-31 23:59:59',
        $highest = '0'
    ) {
        $this->image = $image;
        $this->link = $link;
        $this->title = $title;
        $this->price = $price;
        $this->description = $description;
        $this->fuel = $fuel;
        $this->type = $type;
        $this->mileage = $mileage;
        $this->end_time = $end_time;
        $this->highest = $highest;
    }

    public function render()
    {
        return view('components.vehicle-card');
    }
}