<?php

return [
    'widgets' => [
        'namespace' => 'App\\Filament\\Widgets',
        'path' => app_path('Filament/Widgets'),
        'register' => [
            \App\Filament\Widgets\StatsOverviewWidget::class ,
            \App\Filament\Widgets\UsersChart::class,
            \App\Filament\Widgets\AuctionsChart::class,
            // \App\Filament\Widgets\CarsChart::class,
        ],
    ],

    'dark_mode' => false,
    
    'columns' => 1,

]; 