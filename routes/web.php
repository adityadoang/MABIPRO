<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Livewire\MarketingDashboard;
use App\Livewire\PaymentReport;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC ROUTES
// ============================================================

Route::get('/', function () {
    return redirect()->route('marketing.dashboard');
});

// ============================================================
// AUTH ROUTES
// ============================================================

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ============================================================
// MARKETING / PRODUKSI ROUTES (dari origin/main)
// ============================================================

Route::get('/dashboard', MarketingDashboard::class)->name('marketing.dashboard');
Route::get('/laporan-pembayaran', PaymentReport::class)->name('payment.report');

// ============================================================
// ADMIN ROUTES (dari ezra)
// ============================================================

Route::middleware(['auth', 'role:Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard Admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // CRUD Users
        Route::resource('users', UserController::class)
            ->except(['show']);
    });
