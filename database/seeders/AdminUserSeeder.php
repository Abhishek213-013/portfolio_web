<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => env('ADMIN_EMAIL', 'admin@portfolio.com'),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'password123')),
            'role' => 'admin',
            'is_active' => true,
        ]);
    }
}