<?php

use App\Http\Controllers\ProductionController;
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