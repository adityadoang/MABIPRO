<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Block;
use App\Models\Unit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class MarketingDashboard extends Component
{
    use WithFileUploads;

    // ─────────────────────────────────────────────────────────────
    // UI State (new sidebar layout)
    // ─────────────────────────────────────────────────────────────
    public $selectedBlockId = null;
    public $searchUnit      = '';

    // Modal state
    public $isPaymentModalOpen = false;
    public $selectedUnitId;

    // Metode pembayaran
    public $paymentMethod;

    // ── Cash ──
    public $amountPaid;       // Nominal yang sudah diterima developer (DP / Lunas)
    public $paymentProof;

    // ── KPR: Info Unit ──
    public $hargaUnit;
    public $kprType        = 'non_subsidi';   // subsidi / non_subsidi
    public $bankName;
    public $akadDate;

    // ── KPR: Down Payment ──
    public $dpAmount;
    public $dpPercentage;

    // ── KPR: Kredit ──
    public $kprDurationMonths;
    public $interestRate;
    public $interestType   = 'anuitas';       // anuitas / flat

    // ── KPR: Read-only computed display ──
    public $pokokKredit         = 0;
    public $monthlyInstallment  = 0;
    public $totalPayment        = 0;
    public $totalInterest       = 0;
    public $sisaTagihan         = 0;

    // ─────────────────────────────────────────────────────────────
    // Mount — default pilih blok pertama
    // ─────────────────────────────────────────────────────────────

    public function mount()
    {
        $firstBlock = Block::first();
        if ($firstBlock) {
            $this->selectedBlockId = $firstBlock->id;
        }
    }

    // ─────────────────────────────────────────────────────────────
    // Block Selection
    // ─────────────────────────────────────────────────────────────

    public function selectBlock($blockId)
    {
        $this->selectedBlockId = $blockId;
        $this->searchUnit = '';
    }

    // ─────────────────────────────────────────────────────────────
    // Statistics Helpers
    // ─────────────────────────────────────────────────────────────

    public function getStatsProperty(): array
    {
        $allUnits   = Unit::all();
        $total      = $allUnits->count();
        $terjual    = $allUnits->where('status_penjualan', 'Terjual')->count();
        $sudahDp    = $allUnits->where('status_penjualan', 'Sudah DP')->count();
        $belum      = $allUnits->where('status_penjualan', 'Belum Terjual')->count();

        return [
            'total'      => $total,
            'terjual'    => $terjual,
            'sudah_dp'   => $sudahDp,
            'belum'      => $belum,
            'pct_terjual' => $total > 0 ? round(($terjual / $total) * 100, 1) : 0,
            'pct_dp'      => $total > 0 ? round(($sudahDp / $total) * 100, 1) : 0,
            'pct_belum'   => $total > 0 ? round(($belum / $total) * 100, 1) : 0,
        ];
    }

    // ─────────────────────────────────────────────────────────────
    // Status Update
    // ─────────────────────────────────────────────────────────────

    public function updateStatus($unitId, $newStatus)
    {
        $validStatuses = ['Belum Terjual', 'Sudah DP', 'Terjual'];

        if (in_array($newStatus, $validStatuses)) {
            $unit = Unit::findOrFail($unitId);

            if ($newStatus === 'Belum Terjual') {
                $unit->update([
                    'status_penjualan'    => $newStatus,
                    'payment_method'      => null,
                    'kpr_duration_months' => null,
                    'amount_paid'         => null,
                    'payment_proof_path'  => null,
                    'harga_unit'          => null,
                    // Reset KPR fields
                    'kpr_type'            => null,
                    'bank_name'           => null,
                    'akad_date'           => null,
                    'dp_amount'           => null,
                    'dp_percentage'       => null,
                    'pokok_kredit'        => null,
                    'interest_rate'       => null,
                    'interest_type'       => null,
                    'monthly_installment' => null,
                ]);
            } else {
                $unit->update(['status_penjualan' => $newStatus]);
            }

            session()->flash('message', "Status unit {$unit->unit_number} berhasil diperbarui menjadi {$newStatus}.");
        }
    }

    // ─────────────────────────────────────────────────────────────
    // Modal
    // ─────────────────────────────────────────────────────────────

    public function openPaymentModal($unitId)
    {
        $unit = Unit::findOrFail($unitId);
        $this->selectedUnitId     = $unit->id;
        $this->paymentMethod      = $unit->payment_method;
        $this->amountPaid         = $unit->amount_paid;
        $this->paymentProof       = null;

        // KPR fields
        $this->hargaUnit          = $unit->harga_unit;
        $this->kprType            = $unit->kpr_type       ?? 'non_subsidi';
        $this->bankName           = $unit->bank_name;
        $this->akadDate           = $unit->akad_date;
        $this->dpAmount           = $unit->dp_amount;
        $this->dpPercentage       = $unit->dp_percentage;
        $this->kprDurationMonths  = $unit->kpr_duration_months;
        $this->interestRate       = $unit->interest_rate;
        $this->interestType       = $unit->interest_type  ?? 'anuitas';

        $this->recalculate();
        $this->isPaymentModalOpen = true;
    }

    public function closePaymentModal()
    {
        $this->isPaymentModalOpen = false;
        $this->reset([
            'selectedUnitId', 'paymentMethod', 'amountPaid', 'paymentProof',
            'hargaUnit', 'kprType', 'bankName', 'akadDate',
            'dpAmount', 'dpPercentage',
            'kprDurationMonths', 'interestRate', 'interestType',
            'pokokKredit', 'monthlyInstallment', 'totalPayment', 'totalInterest', 'sisaTagihan'
        ]);
        $this->kprType      = 'non_subsidi';
        $this->interestType = 'anuitas';
        $this->resetValidation();
    }

    // ─────────────────────────────────────────────────────────────
    // Live Watchers — kalkulasi otomatis saat field berubah
    // ─────────────────────────────────────────────────────────────

    public function updatedHargaUnit($value)
    {
        // Jika sudah ada dp_percentage, hitung ulang dp_amount dari persentase
        if ($this->dpPercentage && $value > 0) {
            $this->dpAmount = round(($this->dpPercentage / 100) * $value, 0);
        }
        $this->recalculate();
    }

    public function updatedAmountPaid($value)
    {
        $this->recalculate();
    }

    public function updatedPaymentMethod($value)
    {
        $this->recalculate();
    }

    public function updatedDpAmount($value)
    {
        if ($this->hargaUnit > 0 && $value >= 0) {
            $this->dpPercentage = round(($value / $this->hargaUnit) * 100, 2);
        }
        $this->recalculate();
    }

    public function updatedDpPercentage($value)
    {
        if ($this->hargaUnit > 0 && $value >= 0) {
            $this->dpAmount = round(($value / 100) * $this->hargaUnit, 0);
        }
        $this->recalculate();
    }

    public function updatedKprDurationMonths() { $this->recalculate(); }
    public function updatedInterestRate()       { $this->recalculate(); }
    public function updatedInterestType()       { $this->recalculate(); }
    public function updatedKprType()            { $this->recalculate(); }

    // ─────────────────────────────────────────────────────────────
    // Kalkulasi Cicilan KPR
    // ─────────────────────────────────────────────────────────────

    public function recalculate()
    {
        $harga   = (float) ($this->hargaUnit         ?? 0);
        $dp      = (float) ($this->dpAmount          ?? 0);
        $paid    = (float) ($this->amountPaid        ?? 0);
        $n       = (int)   ($this->kprDurationMonths ?? 0);
        $rTahunan = (float) ($this->interestRate      ?? 0);

        if ($this->paymentMethod === 'Cash') {
            $this->sisaTagihan = max(0, $harga - $paid);
        }

        // Pokok kredit
        $pokok = max(0, $harga - $dp);
        $this->pokokKredit = $pokok;

        if ($pokok <= 0 || $n <= 0 || $rTahunan <= 0) {
            $this->monthlyInstallment = 0;
            $this->totalPayment       = 0;
            $this->totalInterest      = 0;
            return;
        }

        if ($this->interestType === 'flat') {
            // Cicilan Flat: pokok rata + bunga flat dari pokok awal
            $bungaBulanan  = ($rTahunan / 100 / 12) * $pokok;
            $pokokBulanan  = $pokok / $n;
            $cicilan       = $pokokBulanan + $bungaBulanan;
        } else {
            // Cicilan Anuitas (efektif): M = P × r(1+r)^n / [(1+r)^n − 1]
            $r       = $rTahunan / 100 / 12;
            $cicilan = $pokok * ($r * pow(1 + $r, $n)) / (pow(1 + $r, $n) - 1);
        }

        $totalBayar  = $cicilan * $n;
        $totalBunga  = $totalBayar - $pokok;

        $this->monthlyInstallment = round($cicilan, 0);
        $this->totalPayment       = round($totalBayar, 0);
        $this->totalInterest      = round($totalBunga, 0);
    }

    // ─────────────────────────────────────────────────────────────
    // Save
    // ─────────────────────────────────────────────────────────────

    public function savePaymentDetails()
    {
        $isKpr = $this->paymentMethod === 'KPR';

        $rules = [
            'paymentMethod' => 'required|in:Cash,KPR',
            'paymentProof'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'hargaUnit'     => 'required|numeric|min:1',
        ];

        if ($isKpr) {
            $rules = array_merge($rules, [
                'kprType'            => 'required|in:subsidi,non_subsidi',
                'bankName'           => 'required|string|max:100',
                'akadDate'           => 'nullable|date',
                'dpAmount'           => 'required|numeric|min:0',
                'dpPercentage'       => 'required|numeric|min:0|max:100',
                'kprDurationMonths'  => 'required|integer|min:12|max:360',
                'interestRate'       => 'required|numeric|min:0.1|max:30',
                'interestType'       => 'required|in:anuitas,flat',
            ]);
        } else {
            $rules['amountPaid'] = 'required|numeric|min:0';
        }

        $this->validate($rules);

        $unit = Unit::findOrFail($this->selectedUnitId);
        $path = $unit->payment_proof_path;

        if ($this->paymentProof) {
            $path = $this->paymentProof->store('payment_proofs', 'public');
        }

        $this->recalculate();

        if ($isKpr) {
            $unit->update([
                'payment_method'      => 'KPR',
                'amount_paid'         => $this->dpAmount,   // DP yang sudah diterima developer
                'payment_proof_path'  => $path,
                'harga_unit'          => $this->hargaUnit,
                'kpr_type'            => $this->kprType,
                'bank_name'           => $this->bankName,
                'akad_date'           => $this->akadDate ?: null,
                'dp_amount'           => $this->dpAmount,
                'dp_percentage'       => $this->dpPercentage,
                'pokok_kredit'        => $this->pokokKredit,
                'kpr_duration_months' => $this->kprDurationMonths,
                'interest_rate'       => $this->interestRate,
                'interest_type'       => $this->interestType,
                'monthly_installment' => $this->monthlyInstallment,
            ]);
        } else {
            $unit->update([
                'payment_method'      => 'Cash',
                'amount_paid'         => $this->amountPaid,
                'payment_proof_path'  => $path,
                'harga_unit'          => $this->hargaUnit,
                // Reset KPR-specific fields
                'kpr_type'            => null,
                'bank_name'           => null,
                'akad_date'           => null,
                'dp_amount'           => null,
                'dp_percentage'       => null,
                'pokok_kredit'        => null,
                'kpr_duration_months' => null,
                'interest_rate'       => null,
                'interest_type'       => null,
                'monthly_installment' => null,
            ]);
        }

        $this->closePaymentModal();
        session()->flash('message', "Detail pembayaran unit {$unit->unit_number} berhasil disimpan.");
    }

    // ─────────────────────────────────────────────────────────────
    // Render
    // ─────────────────────────────────────────────────────────────

    public function render()
    {
        $blocks = Block::with('units')->get();

        // Unit dari blok yang dipilih, dengan filter pencarian
        $selectedBlock = null;
        $filteredUnits = collect();

        if ($this->selectedBlockId) {
            $selectedBlock = Block::with('units')->find($this->selectedBlockId);
            if ($selectedBlock) {
                $filteredUnits = $selectedBlock->units->filter(function ($unit) {
                    if (!$this->searchUnit) return true;
                    $search = strtolower($this->searchUnit);
                    return str_contains(strtolower($unit->unit_number ?? ''), $search)
                        || str_contains(strtolower($unit->tipe_unit   ?? ''), $search)
                        || str_contains(strtolower($unit->facing      ?? ''), $search);
                })->values();
            }
        }

        return view('livewire.marketing-dashboard', [
            'blocks'        => $blocks,
            'selectedBlock' => $selectedBlock,
            'filteredUnits' => $filteredUnits,
            'stats'         => $this->getStatsProperty(),
        ]);
    }
}