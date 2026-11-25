<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Create (or promote) the default administrator account.
     *
     * is_admin is intentionally not mass-assignable, so it is set with forceFill.
     */
    public function run(): void
    {
        $admin = User::firstOrNew(['email' => config('app.admin.email')]);

        if (! $admin->exists) {
            $admin->forceFill([
                'name' => 'Administrator',
                'password' => config('app.admin.password'),
                'email_verified_at' => now(),
            ]);
        }

        $admin->forceFill(['is_admin' => true])->save();
    }
}
