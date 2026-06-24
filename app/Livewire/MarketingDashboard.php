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
    public $selectedBlockId = null; 
    public $searchUnit      = '';   
    public $isPaymentModalOpen = false; 
    public $selectedUnitId;             
    public $paymentMethod;              
    public $amountPaid;                 
    public $paymentProof;               
    public $hargaUnit;
    public $kprType = 'non_subsidi';    
    public $bankName;
    public $akadDate;
    public $dpAmount;                   
    public $dpPercentage;               
    public $kprDurationMonths;          
    public $pokokKredit        = 0;     
    public $monthlyInstallment = 0;     
    public $sisaTagihan        = 0;     
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
            }
            session()->flash('message', "Status unit {$unit->unit_number} berhasil diperbarui menjadi {$newStatus}.");
        }
    }
    public function openPaymentModal($unitId)
    {
        $unit = Unit::findOrFail($unitId);
        $this->selectedUnitId    = $unit->id;
        $this->paymentMethod     = $unit->payment_method;
        $this->amountPaid        = $unit->amount_paid;
        $this->paymentProof      = null; 
        $this->hargaUnit         = $unit->harga_unit;
        $this->kprType           = $unit->kpr_type ?? 'non_subsidi';
        $this->bankName          = $unit->bank_name;
        $this->akadDate          = $unit->akad_date;
        $this->dpAmount          = $unit->dp_amount;
        $this->dpPercentage      = $unit->dp_percentage;
        $this->kprDurationMonths = $unit->kpr_duration_months;
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
            'kprDurationMonths', 'monthlyInstallment',
            'pokokKredit', 'sisaTagihan'
        ]);
        $this->kprType = 'non_subsidi'; 
        $this->resetValidation();
    }
    public function updated($propertyName, $value)
    {
        if ($propertyName === 'hargaUnit' && $this->dpPercentage > 0 && $value > 0) {
            $this->dpAmount = round(($this->dpPercentage / 100) * $value, 0);
        }
        if ($propertyName === 'dpAmount' && $this->hargaUnit > 0 && $value >= 0) {
            $this->dpPercentage = round(($value / $this->hargaUnit) * 100, 2);
        }
        if ($propertyName === 'dpPercentage' && $this->hargaUnit > 0 && $value >= 0) {
            $this->dpAmount = round(($value / 100) * $this->hargaUnit, 0);
        }
        $this->recalculate();
    }
    public function recalculate()
    {
        $harga = (float) ($this->hargaUnit ?? 0);
        $dp    = (float) ($this->dpAmount  ?? 0);
        $paid  = (float) ($this->amountPaid ?? 0);
        $n     = (int)   ($this->kprDurationMonths ?? 0); 
        if ($this->paymentMethod === 'Cash') {
            $this->amountPaid = $harga;
            $paid = $harga;
            $this->sisaTagihan = 0;
        }
        $pokok = max(0, $harga - $dp);
        $this->pokokKredit = $pokok;
        $this->monthlyInstallment = ($pokok > 0 && $n > 0) ? (int) round($pokok / $n) : 0;
    }
    public function savePaymentDetails()
    {
        $isAdmin = auth()->check() && auth()->user()->isAdmin();
        $this->validatePaymentData($isAdmin);
        $unit = Unit::findOrFail($this->selectedUnitId);
        if (!$isAdmin) {
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
        $total    = $blocks->sum('units_count');
        $terjual  = $blocks->sum('units_terjual_count');
        $sudahDp  = $blocks->sum('units_dp_count');
        $belum    = $total - $terjual - $sudahDp;
        return [
            'total'       => $total,
            'terjual'     => $terjual,
            'sudah_dp'    => $sudahDp,
            'belum'       => $belum,
            'pct_terjual' => $total > 0 ? round(($terjual / $total) * 100, 1) : 0,
            'pct_dp'      => $total > 0 ? round(($sudahDp / $total) * 100, 1) : 0,
            'pct_belum'   => $total > 0 ? round(($belum / $total) * 100, 1) : 0,
        ];
    }
    private function validatePaymentData(bool $isAdmin)
    {
        $rules = [
            'paymentMethod' => 'required|in:Cash,KPR',
            'paymentProof'  => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ];
        if ($isAdmin) {
            $rules['hargaUnit'] = 'required|numeric|min:1';
        }
        if ($this->paymentMethod === 'KPR') {
            $rules = array_merge($rules, [
                'kprType'           => 'required|in:subsidi,non_subsidi',
                'bankName'          => 'required|string|max:100',
                'akadDate'          => 'nullable|date',
                'dpAmount'          => 'required|numeric|min:0',
                'dpPercentage'      => 'required|numeric|min:0|max:100',
                'kprDurationMonths' => 'required|integer|min:12|max:360',
            ]);
        } elseif ($this->paymentMethod === 'Cash') {
            $rules = array_merge($rules, [
                'amountPaid' => 'required|numeric|min:0',
            ]);
        }
        $this->validate($rules);
    }
    private function saveKprData(Unit $unit, ?string $path)
    {
        $status = ($this->dpAmount >= $this->hargaUnit) ? 'Terjual' : 'Sudah DP';
        $unit->update([
            'status_penjualan'    => $status,
            'payment_method'      => 'KPR',
            'amount_paid'         => $this->dpAmount, 
            'payment_proof_path'  => $path,
            'harga_unit'          => $this->hargaUnit,
            'kpr_type'            => $this->kprType,
            'bank_name'           => $this->bankName,
            'akad_date'           => $this->akadDate ?: null,
            'dp_amount'           => $this->dpAmount,
            'dp_percentage'       => $this->dpPercentage,
            'pokok_kredit'        => $this->pokokKredit,
            'kpr_duration_months' => $this->kprDurationMonths,
            'monthly_installment' => $this->monthlyInstallment,
            'interest_rate'       => null,
            'interest_type'       => null,
        ]);
    }
    private function saveCashData(Unit $unit, ?string $path)
    {
        $status = ($this->amountPaid >= $this->hargaUnit) ? 'Terjual' : 'Sudah DP';
        $unit->update([
            'status_penjualan'    => $status,
            'payment_method'      => 'Cash',
            'amount_paid'         => $this->amountPaid, 
            'payment_proof_path'  => $path,
            'harga_unit'          => $this->hargaUnit,
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
    private function resetPaymentData(Unit $unit)
    {
        $unit->update([
            'status_penjualan'    => 'Belum Terjual',
            'payment_method'      => null,
            'kpr_duration_months' => null,
            'amount_paid'         => null,
            'payment_proof_path'  => null,
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
                    $kataKunci = '%' . strtolower($this->searchUnit) . '%';
                    $query->where(function($q) use ($kataKunci) {
                        $q->where('unit_number', 'like', $kataKunci)
                          ->orWhere('customer_name', 'like', $kataKunci);
                    });
                }
                $filteredUnits = $query->get();
            }
        }
        return view('livewire.marketing-dashboard', [
            'blocks'        => $blocks,
            'selectedBlock' => $selectedBlock,
            'filteredUnits' => $filteredUnits,
            'stats'         => $this->getStatsProperty($blocks),
        ]);
    }
}