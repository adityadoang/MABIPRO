<?php

use App\Http\Controllers\Auth\LoginController;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\UserManager;
use App\Livewire\Admin\BlockManager;
use App\Livewire\Admin\UnitManager;
use App\Livewire\Production\Dashboard as ProductionDashboard;
use App\Livewire\Production\UnitDetail;
use App\Http\Controllers\LegalitasController;
use App\Http\Controllers\ProductionController;
use App\Livewire\MarketingDashboard;
use App\Livewire\PaymentReport;
use App\Livewire\LegalitasDashboard;
use Illuminate\Support\Facades\Route;

// ============================================================
// PUBLIC ROUTES
// ============================================================


Route::get('/', function () {
    if (auth()->check()) {
        $role = strtolower(auth()->user()->role);
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'legalitas' => redirect()->route('legalitas.dashboard'),
            'produksi' => redirect()->route('production.dashboard'),
            'marketing' => redirect()->route('marketing.dashboard'),
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
// LEGALITAS ROUTES
// ============================================================

Route::middleware('auth')->prefix('legalitas')->name('legalitas.')->group(function () {
    Route::get('/', function () { return redirect()->route('legalitas.dashboard'); });

    // Dashboard — dihandle oleh Livewire (upload, delete, markAsComplete ada di dalam komponen)
    Route::get('/dashboard', LegalitasDashboard::class)->name('dashboard');

    // Download & Preview tetap di controller karena mengembalikan file binary response
    Route::get('/download/{document_id}', [LegalitasController::class, 'download'])->name('download');
    Route::get('/preview/{document_id}',  [LegalitasController::class, 'preview'])->name('preview');
});

// ============================================================
// PRODUKSI ROUTES (dari feature/modul-produksi)
// ============================================================

Route::middleware('auth')->prefix('production')->name('production.')->group(function () {
    Route::get('/', function () { return redirect()->route('production.dashboard'); });
    Route::get('/dashboard', ProductionDashboard::class)->name('dashboard');
    
    Route::get('/{id}', UnitDetail::class)->name('show');
    // Keep generate report in controller
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
        Route::get('/dashboard', Dashboard::class)->name('dashboard');

        // CRUD Users
        Route::get('/users', UserManager::class)->name('users.index');

        // Master Data Blok (dari feature/modul-produksi)
        Route::get('/blocks', BlockManager::class)->name('blocks.index');

        // Master Data Unit (dari feature/modul-produksi)
        Route::get('/units', UnitManager::class)->name('units.index');
    });

require __DIR__.'/auth.php';