<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Block;
use App\Models\Unit;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class MarketingDashboard extends Component
{
    use WithFileUploads;

    public $isPaymentModalOpen = false;
    public $selectedUnitId;
    public $paymentMethod;
    public $kprDurationMonths;
    public $amountPaid;
    public $paymentProof;

    public function updateStatus($unitId, $newStatus)
    {
        $validStatuses = ['Belum Terjual', 'Sudah DP', 'Terjual'];
        
        if (in_array($newStatus, $validStatuses)) {
            $unit = Unit::findOrFail($unitId);
            
            // Jika dikembalikan ke 'Belum Terjual', reset data pembayaran agar tidak nyangkut
            if ($newStatus === 'Belum Terjual') {
                $unit->update([
                    'sales_status' => $newStatus,
                    'payment_method' => null,
                    'kpr_duration_months' => null,
                    'amount_paid' => null,
                    'payment_proof_path' => null,
                ]);
            } else {
                $unit->update(['sales_status' => $newStatus]);
            }
            
            session()->flash('message', "Status unit {$unit->unit_number} berhasil diperbarui menjadi {$newStatus}.");
        }
    }

    public function openPaymentModal($unitId)
    {
        $unit = Unit::findOrFail($unitId);
        $this->selectedUnitId = $unit->id;
        $this->paymentMethod = $unit->payment_method;
        $this->kprDurationMonths = $unit->kpr_duration_months;
        $this->amountPaid = $unit->amount_paid;
        $this->paymentProof = null; 
        $this->isPaymentModalOpen = true;
    }

    public function closePaymentModal()
    {
        $this->isPaymentModalOpen = false;
        $this->reset(['selectedUnitId', 'paymentMethod', 'kprDurationMonths', 'amountPaid', 'paymentProof']);
        $this->resetValidation();
    }

    public function savePaymentDetails()
    {
        $this->validate([
            'paymentMethod' => 'nullable|in:Cash,KPR',
            'kprDurationMonths' => 'nullable|integer|min:0',
            'amountPaid' => 'nullable|numeric|min:0',
            'paymentProof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $unit = Unit::findOrFail($this->selectedUnitId);
        $path = $unit->payment_proof_path;

        if ($this->paymentProof) {
            $path = $this->paymentProof->store('payment_proofs', 'public');
        }

        $duration = $this->paymentMethod === 'Cash' ? null : $this->kprDurationMonths;

        $unit->update([
            'payment_method' => $this->paymentMethod,
            'kpr_duration_months' => $duration,
            'amount_paid' => $this->amountPaid,
            'payment_proof_path' => $path,
        ]);

        $this->closePaymentModal();
        session()->flash('message', "Detail pembayaran unit {$unit->unit_number} berhasil disimpan.");
    }

    public function render()
    {
        $blocks = Block::with('units')->get();
        
        return view('livewire.marketing-dashboard', [
            'blocks' => $blocks
        ]);
    }
}