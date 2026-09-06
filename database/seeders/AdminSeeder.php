<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'name' => 'Hypeline Admin',
            'email' => 'admin@hypeline.com',
            'password' => Hash::make('admin12345'),
        ]);
    }
}