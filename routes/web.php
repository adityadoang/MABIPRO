<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LegalitasController;

// Sementara middleware role kita matikan dulu agar kamu bisa langsung test tampilannya
Route::prefix('legalitas')->group(function () {
    Route::get('/dashboard', [LegalitasController::class, 'index'])->name('legalitas.dashboard');
    Route::post('/upload/{unit_id}', [LegalitasController::class, 'upload'])->name('legalitas.upload');
    Route::get('/download/{document_id}', [LegalitasController::class, 'download'])->name('legalitas.download');
    Route::get('/preview/{document_id}', [LegalitasController::class, 'preview'])->name('legalitas.preview');
    Route::post('/complete/{unit_id}', [LegalitasController::class, 'markAsComplete'])->name('legalitas.complete');
});