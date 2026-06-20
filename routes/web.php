<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\LegalitasController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\UnitController;
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
// MARKETING ROUTES
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
// PRODUKSI ROUTES (dari feature/modul-produksi)
// ============================================================

Route::prefix('production')->name('production.')->group(function () {
    Route::get('/', [ProductionController::class, 'index'])->name('index');
    Route::get('/{id}', [ProductionController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [ProductionController::class, 'edit'])->name('edit');
    Route::put('/{id}/update', [ProductionController::class, 'updateProgress'])->name('update');
    Route::get('/{id}/report', [ProductionController::class, 'generateReport'])->name('report');
});

// ============================================================
// ADMIN ROUTES
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

        // Master Data Blok (dari feature/modul-produksi)
        Route::get('/blocks', [BlockController::class, 'index'])->name('blocks.index');
        Route::get('/blocks/create', [BlockController::class, 'create'])->name('blocks.create');
        Route::post('/blocks', [BlockController::class, 'store'])->name('blocks.store');
        Route::get('/blocks/{block}/edit', [BlockController::class, 'edit'])->name('blocks.edit');
        Route::put('/blocks/{block}', [BlockController::class, 'update'])->name('blocks.update');
        Route::delete('/blocks/{block}', [BlockController::class, 'destroy'])->name('blocks.destroy');

        // Master Data Unit (dari feature/modul-produksi)
        Route::get('/units', [UnitController::class, 'index'])->name('units.index');
        Route::get('/units/create', [UnitController::class, 'create'])->name('units.create');
        Route::post('/units', [UnitController::class, 'store'])->name('units.store');
        Route::get('/units/{unit}/edit', [UnitController::class, 'edit'])->name('units.edit');
        Route::put('/units/{unit}', [UnitController::class, 'update'])->name('units.update');
        Route::delete('/units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy');
    });
