<?php

namespace App\Livewire;

use App\Models\Block;
use App\Models\Unit;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
class MarketingDashboard extends Component
{
    use WithFileUploads;

    public $selectedBlockId = null;

    public $searchUnit = '';

    public $isPaymentModalOpen = false;

    public $selectedUnitId;

    public $customerName;

    public $paymentMethod;

    public $amountPaid;

    public $paymentProof;

    public $hargaUnit;

    public $kprType = 'non_subsidi';

    public $bankName;

    public $akadDate;

    public $dpAmount;

    public $dpPercentage;

    public $dpKonsumenAmount;

    public $dpKonsumenPercentage;

    public $sbumAmount;

    public $sbumPercentage;

    public $sbumStatus = 'Belum Cair';

    public $sbumCairDate;

    public $sbumFilter = '';

    public $kprDurationMonths;

    public $pokokKredit = 0;

    public $monthlyInstallment = 0;

    public $sisaTagihan = 0;

    public function mount()
    {
        $firstBlock = Block::first();
        if ($firstBlock) {
            $this->selectedBlockId = $firstBlock->id;
        }
    }

    public function selectBlock($blockId)
    {
        $this->selectedBlockId = $blockId;
        $this->searchUnit = '';
    }

    public function updateStatus($unitId, $newStatus)
    {
        $validStatuses = ['Belum Terjual', 'Sudah DP', 'Terjual'];
        if (in_array($newStatus, $validStatuses)) {
            $unit = Unit::findOrFail($unitId);
            if ($newStatus === 'Belum Terjual') {
                $this->resetPaymentData($unit);
            } else {
                $unit->update(['status_penjualan' => $newStatus]);
            }            session()->flash('message', "Status unit {$unit->unit_number} berhasil diperbarui menjadi {$newStatus}.");
        }
    }

    public function updateSbumStatus($unitId, $newStatus)
    {
        if (! auth()->check() || (! auth()->user()->isAdmin() && ! auth()->user()->isMarketing())) {
            session()->flash('error', 'Hanya divisi Marketing dan Admin yang dapat mengubah status SBUM.');

            return;
        }

        $validStatuses = ['Belum Cair', 'Cair'];
        if (! in_array($newStatus, $validStatuses)) {
            return;
        }

        $unit = Unit::findOrFail($unitId);
        $unit->update([
            'sbum_status' => $newStatus,
            'sbum_cair_date' => $newStatus === 'Cair' ? now()->toDateString() : null,
        ]);

        session()->flash('message', "Status SBUM unit {$unit->unit_number} berhasil diperbarui menjadi {$newStatus}.");
    }

    public function openPaymentModal($unitId)
    {
        $unit = Unit::findOrFail($unitId);
        $this->selectedUnitId = $unit->id;
        $this->customerName = $unit->customer_name;
        $this->paymentMethod = $unit->payment_method;
        $this->amountPaid = $unit->amount_paid;
        $this->paymentProof = null;
        $this->hargaUnit = $unit->harga_unit;
        $this->kprType = $unit->kpr_type ?? 'non_subsidi';
        $this->bankName = $unit->bank_name;
        $this->akadDate = $unit->akad_date;
        $this->kprDurationMonths = $unit->kpr_duration_months;

        $this->dpKonsumenAmount = $unit->dp_konsumen_amount;
        $this->dpKonsumenPercentage = $unit->dp_konsumen_percentage;
        $this->sbumAmount = $unit->sbum_amount;
        $this->sbumPercentage = $unit->sbum_percentage;
        $this->sbumStatus = $unit->sbum_status ?? 'Belum Cair';
        $this->sbumCairDate = $unit->sbum_cair_date;

        // Fallback jika unit sebelumnya hanya mengisi dp_amount
        if ($this->dpKonsumenAmount === null && $unit->dp_amount !== null && $this->sbumAmount === null) {
            $this->dpKonsumenAmount = $unit->dp_amount;
            $this->dpKonsumenPercentage = $unit->dp_percentage;
            $this->sbumAmount = 0;
            $this->sbumPercentage = 0;
        }

        $this->dpAmount = $unit->dp_amount;
        $this->dpPercentage = $unit->dp_percentage;

        $this->recalculate();
        $this->isPaymentModalOpen = true;
    }

    public function closePaymentModal()
    {
        $this->isPaymentModalOpen = false;
        $this->reset([
            'selectedUnitId', 'customerName', 'paymentMethod', 'amountPaid', 'paymentProof',
            'hargaUnit', 'kprType', 'bankName', 'akadDate',
            'dpAmount', 'dpPercentage',
            'dpKonsumenAmount', 'dpKonsumenPercentage',
            'sbumAmount', 'sbumPercentage', 'sbumStatus', 'sbumCairDate',
            'kprDurationMonths', 'monthlyInstallment',
            'pokokKredit', 'sisaTagihan',
        ]);
        $this->kprType = 'non_subsidi';
        $this->sbumStatus = 'Belum Cair';
        $this->resetValidation();
    }

    public function updated($propertyName, $value)
    {
        $harga = (float) ($this->hargaUnit ?? 0);

        if ($propertyName === 'hargaUnit' && $harga > 0) {
            if ((float) $this->dpKonsumenPercentage > 0) {
                $this->dpKonsumenAmount = round(((float) $this->dpKonsumenPercentage / 100) * $harga, 0);
            } elseif ((float) $this->dpKonsumenAmount > 0) {
                $this->dpKonsumenPercentage = round(((float) $this->dpKonsumenAmount / $harga) * 100, 2);
            }

            if ((float) $this->sbumPercentage > 0) {
                $this->sbumAmount = round(((float) $this->sbumPercentage / 100) * $harga, 0);
            } elseif ((float) $this->sbumAmount > 0) {
                $this->sbumPercentage = round(((float) $this->sbumAmount / $harga) * 100, 2);
            }
        }

        // DP Konsumen: Nominal <-> Persentase
        if ($propertyName === 'dpKonsumenAmount') {
            $this->dpKonsumenAmount = $value;
            $num = is_numeric($value) ? (float) $value : 0;
            if ($harga > 0) {
                $this->dpKonsumenPercentage = round(($num / $harga) * 100, 2);
            }
        }
        if ($propertyName === 'dpKonsumenPercentage') {
            $this->dpKonsumenPercentage = $value;
            $pct = is_numeric($value) ? (float) $value : 0;
            if ($harga > 0) {
                $this->dpKonsumenAmount = round(($pct / 100) * $harga, 0);
            }
        }

        // SBUM: Nominal <-> Persentase
        if ($propertyName === 'sbumAmount') {
            $this->sbumAmount = $value;
            $num = is_numeric($value) ? (float) $value : 0;
            if ($harga > 0) {
                $this->sbumPercentage = round(($num / $harga) * 100, 2);
            }
        }
        if ($propertyName === 'sbumPercentage') {
            $this->sbumPercentage = $value;
            $pct = is_numeric($value) ? (float) $value : 0;
            if ($harga > 0) {
                $this->sbumAmount = round(($pct / 100) * $harga, 0);
            }
        }

        // Backward compatibility jika field legacy terpicu
        if ($propertyName === 'dpAmount') {
            if ($harga > 0 && is_numeric($value) && $value >= 0) {
                $this->dpPercentage = round(((float) $value / $harga) * 100, 2);
            }
            if ((float) ($this->dpKonsumenAmount ?? 0) == 0 && is_numeric($value)) {
                $this->dpKonsumenAmount = $value;
                if ($harga > 0 && $value >= 0) {
                    $this->dpKonsumenPercentage = $this->dpPercentage;
                }
            }
        }
        if ($propertyName === 'dpPercentage') {
            if ($harga > 0 && is_numeric($value) && $value >= 0 && $value <= 100) {
                $this->dpAmount = round(((float) $value / 100) * $harga, 0);
            }
            if ((float) ($this->dpKonsumenPercentage ?? 0) == 0 && is_numeric($value)) {
                $this->dpKonsumenPercentage = $value;
                if ($value >= 0 && $value <= 100) {
                    $this->dpKonsumenAmount = $this->dpAmount;
                }
            }
        }

        $this->recalculate();
    }

    public function recalculate()
    {
        $harga = (float) ($this->hargaUnit ?? 0);

        if ($this->paymentMethod === 'Cash') {
            // Nominal Cash diisi oleh Marketing. Nilai parsial membuat status
            // unit menjadi "Sudah DP"; nilai lunas menjadi "Terjual".
            $this->sisaTagihan = max(0, $harga - (float) ($this->amountPaid ?? 0));
            $this->pokokKredit = 0;
            $this->monthlyInstallment = 0;

            return;
        }

        if ($this->paymentMethod === 'KPR') {
            $dpKonsumen = (float) ($this->dpKonsumenAmount ?? 0);
            $sbum = (float) ($this->sbumAmount ?? 0);

            if ($dpKonsumen == 0 && (float) ($this->dpAmount ?? 0) != 0) {
                $dpKonsumen = (float) $this->dpAmount;
                $this->dpKonsumenAmount = $dpKonsumen;
            }

            if ((float) ($this->dpAmount ?? 0) >= 0) {
                $totalDp = max(0, $dpKonsumen) + max(0, $sbum);
                $this->dpAmount = $totalDp;
                $this->dpPercentage = ($harga > 0) ? round(($totalDp / $harga) * 100, 2) : 0;
            } else {
                $totalDp = 0;
            }

            $pokok = max(0, $harga - $totalDp);
            $this->pokokKredit = $pokok;

            $n = (int) ($this->kprDurationMonths ?? 0);
            $this->monthlyInstallment = ($pokok > 0 && $n > 0) ? (int) round($pokok / $n) : 0;
        }
    }

    public function savePaymentDetails()
    {
        if (! auth()->check() || (! auth()->user()->isAdmin() && ! auth()->user()->isMarketing())) {
            abort(403, 'Akses ditolak. Hanya Marketing dan Admin yang dapat menyimpan pembayaran.');
        }

        $isAdmin = auth()->user()->isAdmin();
        $this->validatePaymentData($isAdmin);
        $unit = Unit::findOrFail($this->selectedUnitId);
        if (! $isAdmin) {
            $this->hargaUnit = $unit->harga_unit;
        }
        $path = $unit->payment_proof_path;
        if ($this->paymentProof) {
            $path = $this->paymentProof->store('payment_proofs', 'public');
        }
        $this->recalculate();
        if ($this->paymentMethod === 'KPR') {
            $this->saveKprData($unit, $path);
        } else {
            $this->saveCashData($unit, $path);
        }
        $this->closePaymentModal();
        session()->flash('message', "Detail pembayaran unit {$unit->unit_number} berhasil disimpan.");
    }

    public function getStatsProperty($blocks): array
    {
        $total = $blocks->sum('units_count');
        $terjual = $blocks->sum('units_terjual_count');
        $sudahDp = $blocks->sum('units_dp_count');
        $belum = $total - $terjual - $sudahDp;

        return [
            'total' => $total,
            'terjual' => $terjual,
            'sudah_dp' => $sudahDp,
            'belum' => $belum,
            'pct_terjual' => $total > 0 ? round(($terjual / $total) * 100, 1) : 0,
            'pct_dp' => $total > 0 ? round(($sudahDp / $total) * 100, 1) : 0,
            'pct_belum' => $total > 0 ? round(($belum / $total) * 100, 1) : 0,
        ];
    }

    private function validatePaymentData(bool $isAdmin)
    {
        $rules = [
            'paymentMethod' => 'required|in:Cash,KPR',
            'customerName' => 'nullable|string|max:255',
            'paymentProof' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];
        if ($isAdmin) {
            $rules['hargaUnit'] = 'required|numeric|min:1';
        }
        if ($this->paymentMethod === 'KPR') {
            $rules = array_merge($rules, [
                'kprType' => 'required|in:subsidi,non_subsidi',
                'bankName' => 'required|string|max:100',
                'akadDate' => 'nullable|date',
                'dpAmount' => 'nullable|numeric|min:0',
                'dpPercentage' => 'nullable|numeric|min:0|max:100',
                'dpKonsumenAmount' => 'nullable|numeric|min:0',
                'dpKonsumenPercentage' => 'nullable|numeric|min:0|max:100',
                'sbumAmount' => 'nullable|numeric|min:0',
                'sbumPercentage' => 'nullable|numeric|min:0|max:100',
                'sbumStatus' => 'nullable|in:Belum Cair,Cair',
                'kprDurationMonths' => 'required|integer|min:12|max:360',
            ]);
        } elseif ($this->paymentMethod === 'Cash') {
            $rules = array_merge($rules, [
                'amountPaid' => 'required|numeric|min:1',
            ]);
        }
        $this->validate($rules);
    }

    private function saveKprData(Unit $unit, ?string $path)
    {
        $totalDp = (float) ($this->dpKonsumenAmount ?? 0) + (float) ($this->sbumAmount ?? 0);
        $status = ($totalDp >= $this->hargaUnit) ? 'Terjual' : 'Sudah DP';
        $sbumStatus = $this->sbumStatus ?: 'Belum Cair';

        $unit->update([
            'status_penjualan' => $status,
            'customer_name' => $this->customerName ?: null,
            'payment_method' => 'KPR',
            'amount_paid' => $totalDp,
            'payment_proof_path' => $path,
            'harga_unit' => $this->hargaUnit,
            'kpr_type' => $this->kprType,
            'bank_name' => $this->bankName,
            'akad_date' => $this->akadDate ?: null,
            'dp_amount' => $totalDp,
            'dp_percentage' => $this->dpPercentage,
            'dp_konsumen_amount' => (float) ($this->dpKonsumenAmount ?? 0),
            'dp_konsumen_percentage' => (float) ($this->dpKonsumenPercentage ?? 0),
            'sbum_amount' => (float) ($this->sbumAmount ?? 0),
            'sbum_percentage' => (float) ($this->sbumPercentage ?? 0),
            'sbum_status' => $sbumStatus,
            'sbum_cair_date' => ($sbumStatus === 'Cair') ? ($unit->sbum_cair_date ?: now()->toDateString()) : null,
            'pokok_kredit' => $this->pokokKredit,
            'kpr_duration_months' => $this->kprDurationMonths,
            'monthly_installment' => $this->monthlyInstallment,
            'interest_rate' => null,
            'interest_type' => null,
        ]);
    }

    private function saveCashData(Unit $unit, ?string $path)
    {
        $status = ($this->amountPaid >= $this->hargaUnit) ? 'Terjual' : 'Sudah DP';
        $unit->update([
            'status_penjualan' => $status,
            'customer_name' => $this->customerName ?: null,
            'payment_method' => 'Cash',
            'amount_paid' => $this->amountPaid,
            'payment_proof_path' => $path,
            'harga_unit' => $this->hargaUnit,
            'kpr_type' => null,
            'bank_name' => null,
            'akad_date' => null,
            'dp_amount' => null,
            'dp_percentage' => null,
            'dp_konsumen_amount' => null,
            'dp_konsumen_percentage' => null,
            'sbum_amount' => null,
            'sbum_percentage' => null,
            'sbum_status' => null,
            'sbum_cair_date' => null,
            'pokok_kredit' => null,
            'kpr_duration_months' => null,
            'interest_rate' => null,
            'interest_type' => null,
            'monthly_installment' => null,
        ]);
    }

    private function resetPaymentData(Unit $unit)
    {
        $unit->update([
            'status_penjualan' => 'Belum Terjual',
            'customer_name' => null,
            'payment_method' => null,
            'kpr_duration_months' => null,
            'amount_paid' => null,
            'payment_proof_path' => null,
            'kpr_type' => null,
            'bank_name' => null,
            'akad_date' => null,
            'dp_amount' => null,
            'dp_percentage' => null,
            'dp_konsumen_amount' => null,
            'dp_konsumen_percentage' => null,
            'sbum_amount' => null,
            'sbum_percentage' => null,
            'sbum_status' => null,
            'sbum_cair_date' => null,
            'pokok_kredit' => null,
            'interest_rate' => null,
            'interest_type' => null,
            'monthly_installment' => null,
        ]);
    }

    public function render()
    {
        $blocks = Block::withCount(['units', 'unitsTerjual', 'unitsDp'])->get();
        $selectedBlock = null;
        $filteredUnits = collect();
        if ($this->selectedBlockId) {
            $selectedBlock = Block::find($this->selectedBlockId);
            if ($selectedBlock) {
                $query = Unit::where('block_id', $this->selectedBlockId);
                if ($this->searchUnit != '') {
                    $kataKunci = '%'.strtolower($this->searchUnit).'%';
                    $query->where(function ($q) use ($kataKunci) {
                        $q->whereRaw('LOWER(unit_number) LIKE ?', [$kataKunci])
                            ->orWhereRaw('LOWER(COALESCE(customer_name, "")) LIKE ?', [$kataKunci]);
                    });
                }
                if ($this->sbumFilter === 'Cair') {
                    $query->where('payment_method', 'KPR')->where('sbum_status', 'Cair');
                } elseif ($this->sbumFilter === 'Belum Cair') {
                    $query->where('payment_method', 'KPR')->where(function ($q) {
                        $q->where('sbum_status', 'Belum Cair')
                            ->orWhereNull('sbum_status');
                    });
                }
                $filteredUnits = $query->get();
            }
        }

        // Stats SBUM untuk KPR
        $sbumStats = [
            'total_kpr' => Unit::where('payment_method', 'KPR')->count(),
            'cair' => Unit::where('payment_method', 'KPR')->where('sbum_status', 'Cair')->count(),
            'belum_cair' => Unit::where('payment_method', 'KPR')->where(function ($q) {
                $q->where('sbum_status', 'Belum Cair')->orWhereNull('sbum_status');
            })->count(),
        ];

        return view('livewire.marketing-dashboard', [
            'blocks' => $blocks,
            'selectedBlock' => $selectedBlock,
            'filteredUnits' => $filteredUnits,
            'stats' => $this->getStatsProperty($blocks),
            'sbumStats' => $sbumStats,
        ]);
    }
}
