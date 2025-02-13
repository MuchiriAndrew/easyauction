<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'Muchiri',
            'email' => 'kariukia225@gmail.com',
            'password' => Hash::make('password'), // Always hash passwords
            'role' => 'admin', // Set the role to 'admin', 'vendor' or 'bidder'
        ]);
    }
}
