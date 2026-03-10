<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Home Page
|--------------------------------------------------------------------------
*/
Route::view('/', 'welcome');

/*
|--------------------------------------------------------------------------
| Registration Routes
|--------------------------------------------------------------------------
*/
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

/*
|--------------------------------------------------------------------------
| Login Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Logout Route
|--------------------------------------------------------------------------
*/
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

/*
|--------------------------------------------------------------------------
| User Dashboard (authenticated users)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard (admins only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

/*
|--------------------------------------------------------------------------
| Reports Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
});

/*
|--------------------------------------------------------------------------
| Settings Route
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
});