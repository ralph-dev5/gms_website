<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Check if admin already exists
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => Hash::make('password123'), // change to a strong password
                'is_admin' => true, // optional: if you have an is_admin column
            ]);
        }
    }
}