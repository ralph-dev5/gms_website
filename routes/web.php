<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Test Route
|--------------------------------------------------------------------------
*/
Route::get('/test', function () {
    return 'Laravel is working!';
});

/*
|--------------------------------------------------------------------------
| Home Page
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome');

/*
|--------------------------------------------------------------------------
| Social Login (OAuth)
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {

    Route::get('{provider}/redirect', [SocialiteController::class, 'redirectToProvider'])
        ->name('social.redirect');

    Route::get('{provider}/callback', [SocialiteController::class, 'handleProviderCallback'])
        ->name('social.callback');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Register
Route::get('/register', [RegisteredUserController::class, 'create'])
    ->name('register');

Route::post('/register', [RegisteredUserController::class, 'store']);

// Login
Route::get('/login', [LoginController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login']);

// Logout
Route::post('/logout', function () {

    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login');

})->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | User Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */
    Route::prefix('reports')->group(function () {

        Route::get('/', [ReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/create', [ReportController::class, 'create'])
            ->name('reports.create');

        Route::post('/', [ReportController::class, 'store'])
            ->name('reports.store');

        Route::delete('/{id}', [ReportController::class, 'destroy'])
            ->name('reports.destroy');

        Route::get('/deleted', [ReportController::class, 'deleted'])
            ->name('reports.deleted');

    });

    /*
    |--------------------------------------------------------------------------
    | User Settings
    |--------------------------------------------------------------------------
    */
    Route::get('/settings', [SettingsController::class, 'index'])
        ->name('settings');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Admin Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('admin.dashboard');

        Route::get('/analytics', [AdminController::class, 'analytics'])
            ->name('analytics');

        Route::get('/deleted-reports', [AdminController::class, 'deletedReports'])
            ->name('deleted-reports');

        Route::get('/settings', [AdminController::class, 'settings'])
            ->name('admin.settings');

        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');

        Route::get('/users/{id}/reports', [AdminController::class, 'userReports'])
            ->name('admin.users.reports');

        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])
            ->name('admin.users.delete');

        Route::put('/reports/{id}/status', [AdminController::class, 'updateReportStatus'])
            ->name('admin.reports.updateStatus');

        Route::delete('/reports/{id}', [AdminController::class, 'destroyReport'])
            ->name('admin.reports.destroy');

    });

Route::middleware('auth')->group(function () {

    // Show Settings Page
    Route::get('/settings', [\App\Http\Controllers\UserController::class, 'settings'])
        ->name('settings');

    // Update Profile Info
    Route::put('/settings/profile', [\App\Http\Controllers\UserController::class, 'updateProfile'])
        ->name('settings.updateProfile');

    // Update Password
    Route::put('/settings/password', [\App\Http\Controllers\UserController::class, 'updatePassword'])
        ->name('settings.updatePassword');

});