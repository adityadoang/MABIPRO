<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\LegalitasController;
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
// MARKETING ROUTES (dari origin/main)
// ============================================================

Route::get('/dashboard', MarketingDashboard::class)->name('marketing.dashboard');
Route::get('/laporan-pembayaran', PaymentReport::class)->name('payment.report');

// ============================================================
// LEGALITAS ROUTES (dari pasha)
// ============================================================

Route::prefix('legalitas')->group(function () {
    Route::get('/dashboard', [LegalitasController::class, 'index'])->name('legalitas.dashboard');
    Route::post('/upload/{unit_id}', [LegalitasController::class, 'upload'])->name('legalitas.upload');
    Route::get('/download/{document_id}', [LegalitasController::class, 'download'])->name('legalitas.download');
    Route::get('/preview/{document_id}', [LegalitasController::class, 'preview'])->name('legalitas.preview');
    Route::post('/complete/{unit_id}', [LegalitasController::class, 'markAsComplete'])->name('legalitas.complete');
});

// ============================================================
// ADMIN ROUTES (dari origin/main — ezra)
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
