<?php

use App\Livewire\MarketingDashboard;
use App\Livewire\PaymentReport;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', MarketingDashboard::class)->name('marketing.dashboard');
Route::get('/laporan-pembayaran', PaymentReport::class)->name('payment.report');