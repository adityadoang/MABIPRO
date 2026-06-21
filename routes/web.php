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
    if (auth()->check()) {
        $role = auth()->user()->role;
        return match ($role) {
            'Admin' => redirect()->route('admin.dashboard'),
            'Legalitas' => redirect()->route('legalitas.dashboard'),
            'Produksi' => redirect()->route('production.dashboard'),
            'Marketing' => redirect()->route('marketing.dashboard'),
            default => redirect()->route('marketing.dashboard'),
        };
    }
    return redirect()->route('login');
});

// ============================================================
// MARKETING ROUTES
// ============================================================

Route::middleware('auth')->prefix('marketing')->name('marketing.')->group(function () {
    Route::get('/', function () { return redirect()->route('marketing.dashboard'); });
    Route::get('/dashboard', MarketingDashboard::class)->name('dashboard');
    Route::get('/laporan-pembayaran', PaymentReport::class)->name('payment.report');
});

// ============================================================
// LEGALITAS ROUTES (dari pasha)
// ============================================================

Route::middleware('auth')->prefix('legalitas')->name('legalitas.')->group(function () {
    Route::get('/', function () { return redirect()->route('legalitas.dashboard'); });
    Route::get('/dashboard', [LegalitasController::class, 'index'])->name('dashboard');
    Route::post('/upload/{unit_id}', [LegalitasController::class, 'upload'])->name('upload');
    Route::get('/download/{document_id}', [LegalitasController::class, 'download'])->name('download');
    Route::get('/preview/{document_id}', [LegalitasController::class, 'preview'])->name('preview');
    Route::post('/complete/{unit_id}', [LegalitasController::class, 'markAsComplete'])->name('complete');
});

// ============================================================
// PRODUKSI ROUTES (dari feature/modul-produksi)
// ============================================================

Route::middleware('auth')->prefix('production')->name('production.')->group(function () {
    Route::get('/', function () { return redirect()->route('production.dashboard'); });
    Route::get('/dashboard', [ProductionController::class, 'index'])->name('dashboard');
    Route::get('/{id}', [ProductionController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [ProductionController::class, 'edit'])->name('edit');
    Route::put('/{id}/update', [ProductionController::class, 'updateProgress'])->name('update');
    Route::get('/{id}/report', [ProductionController::class, 'generateReport'])->name('report');

    // API endpoint untuk ambil unit berdasarkan blok
    Route::get('/api/blocks/{blockId}/units', function($blockId) {
        $units = \App\Models\Unit::where('block_id', $blockId)
            ->select('id', 'unit_number')
            ->get();
        return response()->json($units);
    });
});

// ============================================================
// ADMIN ROUTES
// ============================================================

Route::middleware(['auth', 'role:Admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/', function () { return redirect()->route('admin.dashboard'); });

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

require __DIR__.'/auth.php';
