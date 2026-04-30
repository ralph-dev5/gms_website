<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/clear-cache', function() {
    \Artisan::call('view:clear');
    \Artisan::call('cache:clear');
    \Artisan::call('config:clear');
    return 'All cache cleared!';
});

/*
|--------------------------------------------------------------------------
| Test Route
|--------------------------------------------------------------------------
*/
Route::get('/test', function () {
    try {
        return response()->json([
            'status' => 'ok',
            'php' => PHP_VERSION,
            'env' => app()->environment(),
            'debug' => config('app.debug'),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500);
    }
});

/*
|--------------------------------------------------------------------------
| Temp: Fix Admin Account
|--------------------------------------------------------------------------
*/
Route::get('/create-admin', function () {
    \DB::table('users')->where('email', 'admin@example.com')->update(['is_admin' => 1]);
    $user = \DB::table('users')->where('email', 'admin@example.com')->first();
    return 'is_admin = ' . $user->is_admin;
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
| OTP Login
|--------------------------------------------------------------------------
*/
Route::get('/login/otp', [OtpController::class, 'showOtpLogin'])->name('otp.login');
Route::post('/login/otp/send', [OtpController::class, 'sendOtp'])->name('otp.send');
Route::get('/login/otp/verify', [OtpController::class, 'showVerifyForm'])->name('otp.verify.form');
Route::post('/login/otp/verify', [OtpController::class, 'verifyOtp'])->name('otp.verify');

/*
|--------------------------------------------------------------------------
| Phone Registration
|--------------------------------------------------------------------------
*/
Route::get('/register/phone', [OtpController::class, 'showPhoneRegister'])->name('otp.register');
Route::post('/register/phone/send', [OtpController::class, 'sendRegisterOtp'])->name('otp.register.send');
Route::get('/register/phone/verify', [OtpController::class, 'showRegisterVerifyForm'])->name('otp.register.verify.form');
Route::post('/register/phone/verify', [OtpController::class, 'verifyRegisterOtp'])->name('otp.register.verify');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Register
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Login
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
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

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/create', [ReportController::class, 'create'])->name('reports.create');
        Route::post('/', [ReportController::class, 'store'])->name('reports.store');
        Route::delete('/{id}', [ReportController::class, 'destroy'])->name('reports.destroy');
        Route::get('/deleted', [ReportController::class, 'deleted'])->name('reports.deleted');
    });

    // Settings
    Route::get('/settings', [\App\Http\Controllers\UserController::class, 'settings'])->name('settings');
    Route::put('/settings/profile', [\App\Http\Controllers\UserController::class, 'updateProfile'])->name('settings.updateProfile');
    Route::put('/settings/password', [\App\Http\Controllers\UserController::class, 'updatePassword'])->name('settings.updatePassword');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Admin Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/analytics', [AdminController::class, 'analytics'])->name('analytics');
        Route::get('/deleted-reports', [AdminController::class, 'deletedReports'])->name('deleted-reports');
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
        Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
        Route::get('/users/{id}/reports', [AdminController::class, 'userReports'])->name('admin.users.reports');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
        Route::put('/reports/{id}/status', [AdminController::class, 'updateReportStatus'])->name('admin.reports.updateStatus');
        Route::delete('/reports/{id}', [AdminController::class, 'destroyReport'])->name('admin.reports.destroy');
    });