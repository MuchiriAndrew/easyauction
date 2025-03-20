<?php
namespace App\Filament\Pages;

use App\Filament\Widgets\AuctionsChart;
use App\Filament\Widgets\AnotherWidget;
use Filament\Pages\Dashboard as BaseDashboard;

// class Dashboard extends BaseDashboard
class Dashboard 
{
    
    protected function getColumns(): int
    {
        return 2; // Set the number of columns to 2
    }
}