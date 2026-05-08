<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OtpController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public / Utility Routes
|--------------------------------------------------------------------------
*/

Route::view('/', 'welcome');

Route::get('/test', function () {
    try {
        return response()->json([
            'status' => 'ok',
            'php'    => PHP_VERSION,
            'env'    => app()->environment(),
            'debug'  => config('app.debug'),
        ]);
    } catch (Throwable $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file'  => $e->getFile(),
            'line'  => $e->getLine(),
        ], 500);
    }
});

Route::get('/create-admin', function () {
    \DB::table('users')->where('email', 'admin@example.com')->update(['is_admin' => 1]);
    $user = \DB::table('users')->where('email', 'admin@example.com')->first();
    return 'is_admin = ' . $user->is_admin;
});

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
| Google OAuth
|--------------------------------------------------------------------------
*/

Route::get('auth/google', [GoogleController::class, 'redirect'])->name('google.redirect');
Route::get('auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

/*
|--------------------------------------------------------------------------
| Generic Social Login (Socialite)
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

Route::prefix('login/otp')->group(function () {
    Route::get('/',         [OtpController::class, 'showOtpLogin'])->name('otp.login');
    Route::post('/send',    [OtpController::class, 'sendOtp'])->name('otp.send');
    Route::get('/verify',   [OtpController::class, 'showVerifyForm'])->name('otp.verify.form');
    Route::post('/verify',  [OtpController::class, 'verifyOtp'])->name('otp.verify');
});

/*
|--------------------------------------------------------------------------
| Phone Registration
|--------------------------------------------------------------------------
*/

Route::prefix('register/phone')->group(function () {
    Route::get('/',         [OtpController::class, 'showPhoneRegister'])->name('otp.register');
    Route::post('/send',    [OtpController::class, 'sendRegisterOtp'])->name('otp.register.send');
    Route::get('/verify',   [OtpController::class, 'showRegisterVerifyForm'])->name('otp.register.verify.form');
    Route::post('/verify',  [OtpController::class, 'verifyRegisterOtp'])->name('otp.register.verify');
});

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Reports
    Route::prefix('reports')->group(function () {
        Route::get('/',         [ReportController::class, 'index'])->name('reports.index');
        Route::get('/create',   [ReportController::class, 'create'])->name('reports.create');
        Route::post('/',        [ReportController::class, 'store'])->name('reports.store');
        Route::delete('/{id}',  [ReportController::class, 'destroy'])->name('reports.destroy');
        Route::get('/deleted',  [ReportController::class, 'deleted'])->name('reports.deleted');
    });

    // Settings
    Route::prefix('settings')->group(function () {
        Route::get('/',             [UserController::class, 'settings'])->name('settings');
        Route::put('/profile',      [UserController::class, 'updateProfile'])->name('settings.updateProfile');
        Route::put('/password',     [UserController::class, 'updatePassword'])->name('settings.updatePassword');
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard',    [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/analytics',    [AdminController::class, 'analytics'])->name('analytics');
    Route::get('/deleted-reports', [AdminController::class, 'deletedReports'])->name('deleted-reports');

    // Admin Settings
    Route::prefix('settings')->group(function () {
        Route::get('/',         [AdminController::class, 'settings'])->name('admin.settings');
        Route::put('/profile',  [AdminController::class, 'updateProfile'])->name('admin.settings.updateProfile');
        Route::put('/password', [AdminController::class, 'updatePassword'])->name('admin.settings.updatePassword');
    });

    // User Management
    Route::prefix('users')->group(function () {
        Route::get('/',                 [AdminController::class, 'users'])->name('admin.users');
        Route::get('/{id}/reports',     [AdminController::class, 'userReports'])->name('admin.users.reports');
        Route::delete('/{id}',          [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    });

    // Report Management
    Route::prefix('reports')->group(function () {
        Route::put('/{id}/status',  [AdminController::class, 'updateReportStatus'])->name('admin.reports.updateStatus');
        Route::delete('/{id}',      [AdminController::class, 'destroyReport'])->name('admin.reports.destroy');
    });
});