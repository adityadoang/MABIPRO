<?php

use App\Http\Controllers\BlockController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\UnitController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route Modul Produksi
Route::prefix('production')->name('production.')->group(function () {
    Route::get('/', [ProductionController::class, 'index'])->name('index');
    Route::get('/{id}', [ProductionController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [ProductionController::class, 'edit'])->name('edit');
    Route::put('/{id}/update', [ProductionController::class, 'updateProgress'])->name('update');
    Route::get('/{id}/report', [ProductionController::class, 'generateReport'])->name('report');
});

// Route Admin - Master Data
Route::prefix('admin')->name('admin.')->group(function () {
    // Blok
    Route::get('/blocks', [BlockController::class, 'index'])->name('blocks.index');
    Route::get('/blocks/create', [BlockController::class, 'create'])->name('blocks.create');
    Route::post('/blocks', [BlockController::class, 'store'])->name('blocks.store');
    Route::get('/blocks/{block}/edit', [BlockController::class, 'edit'])->name('blocks.edit');
    Route::put('/blocks/{block}', [BlockController::class, 'update'])->name('blocks.update');
    Route::delete('/blocks/{block}', [BlockController::class, 'destroy'])->name('blocks.destroy');
    
    // Unit
    Route::get('/units', [UnitController::class, 'index'])->name('units.index');
    Route::get('/units/create', [UnitController::class, 'create'])->name('units.create');
    Route::post('/units', [UnitController::class, 'store'])->name('units.store');
    Route::get('/units/{unit}/edit', [UnitController::class, 'edit'])->name('units.edit');
    Route::put('/units/{unit}', [UnitController::class, 'update'])->name('units.update');
    Route::delete('/units/{unit}', [UnitController::class, 'destroy'])->name('units.destroy');
});