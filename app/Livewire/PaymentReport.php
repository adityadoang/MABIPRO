<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Unit;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class PaymentReport extends Component
{
    public function render()
    {
        // Get all units that have a payment method
        $units = Unit::whereNotNull('payment_method')->with('block')->get();

        $totalRevenue = $units->sum('amount_paid');
        $totalCashUnits = $units->where('payment_method', 'Cash')->count();
        $totalKprUnits = $units->where('payment_method', 'KPR')->count();

        return view('livewire.payment-report', [
            'units' => $units,
            'totalRevenue' => $totalRevenue,
            'totalCashUnits' => $totalCashUnits,
            'totalKprUnits' => $totalKprUnits,
        ]);
    }
}
