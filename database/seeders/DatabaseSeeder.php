<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database. Safe to run more than once.
     */
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);

        $testUser = User::firstOrNew(['email' => 'test@example.com']);
        $testUser->forceFill([
            'name' => 'Test User',
            'password' => 'password123',
            'email_verified_at' => now(),
            'is_admin' => false,
        ])->save();
    }
}
