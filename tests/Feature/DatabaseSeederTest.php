<?php

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('seeder creates a real admin and a regular test user', function () {
    $this->seed(DatabaseSeeder::class);

    expect(User::where('email', 'admin@example.com')->first()->is_admin)->toBeTrue()
        ->and(User::where('email', 'test@example.com')->first()->is_admin)->toBeFalse();
});

test('seeder can be run more than once', function () {
    $this->seed(DatabaseSeeder::class);
    $this->seed(DatabaseSeeder::class);

    expect(User::count())->toBe(2);
});

test('seeded admin can log in and reach the admin dashboard', function () {
    $this->seed(DatabaseSeeder::class);

    $this->post('/login', ['email' => 'admin@example.com', 'password' => 'password123']);

    $this->assertAuthenticated();
    $this->get(route('admin.dashboard'))->assertOk();
});

test('is_admin cannot be mass assigned', function () {
    $user = User::create([
        'name' => 'Sneaky',
        'email' => 'sneaky@example.com',
        'password' => 'secret123',
        'is_admin' => true,
    ]);

    expect($user->fresh()->is_admin)->toBeFalse();
});

test('social provider ids are saved on the user', function () {
    $user = User::factory()->create();

    $user->update(['facebook_id' => 'fb-123', 'github_id' => 'gh-456']);

    expect($user->fresh())
        ->facebook_id->toBe('fb-123')
        ->github_id->toBe('gh-456');
});
