<?php
namespace App\Livewire;
use Livewire\Component;
use App\Models\Unit;
use App\Models\InstallmentPayment;
use Livewire\Attributes\Layout;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
#[Layout('layouts.app')]
class PaymentReport extends Component
{
    public ?int $activeTrackerUnitId = null;
    public bool $isEditModalOpen    = false;
    public ?string $editMonth       = null;   
    public ?string $editMonthLabel  = null;   
    public string  $editNote        = '';
    public string  $editAmount      = '';
    public bool    $editIsPaid      = false;
    public function openTracker(int $unitId): void
    {
        if ($this->activeTrackerUnitId === $unitId) {
            $this->activeTrackerUnitId = null;  
        } else {
            $this->activeTrackerUnitId = $unitId;
        }
        $this->closeEditModal();
    }
    public function togglePaid(int $unitId, string $month): void
    {
        $existing = InstallmentPayment::where('unit_id', $unitId)
            ->where('installment_month', $month)
            ->first();
        if ($existing) {
            $newStatus = !$existing->is_paid;
            $existing->update([
                'is_paid'     => $newStatus,
                'paid_at'     => $newStatus ? now() : null,
                'recorded_by' => Auth::id(),
            ]);
        } else {
            InstallmentPayment::create([
                'unit_id'           => $unitId,
                'installment_month' => $month,
                'is_paid'           => true,
                'amount_paid'       => Unit::find($unitId)?->monthly_installment,
                'paid_at'           => now(),
                'recorded_by'       => Auth::id(),
            ]);
        }
    }
    public function openEditModal(int $unitId, string $month): void
    {
        $this->activeTrackerUnitId = $unitId;
        $this->editMonth     = $month;
        $this->editMonthLabel = Carbon::parse($month)->translatedFormat('F Y');
        $existing = InstallmentPayment::where('unit_id', $unitId)
            ->where('installment_month', $month)
            ->first();
        $unit = Unit::find($unitId);
        $this->editIsPaid  = $existing?->is_paid  ?? false;
        $rawAmount = $existing?->amount_paid ?? ($unit?->monthly_installment ?? null);
        $this->editAmount  = $rawAmount !== null ? (string)(int)round((float)$rawAmount) : '';
        $this->editNote    = $existing?->note ?? '';
        $this->isEditModalOpen = true;
    }
    public function closeEditModal(): void
    {
        $this->isEditModalOpen = false;
        $this->reset(['editMonth', 'editMonthLabel', 'editNote', 'editAmount', 'editIsPaid']);
    }
    public function saveInstallment(): void
    {
        $this->validate([
            'editAmount' => 'nullable|numeric|min:0|max:999999999999',
            'editNote'   => 'nullable|string|max:500',
        ]);
        $amountRaw = preg_replace('/[^0-9.]/', '', (string) $this->editAmount);
        $amount    = $amountRaw !== '' ? (float) $amountRaw : null;
        InstallmentPayment::updateOrCreate(
            [
                'unit_id'           => $this->activeTrackerUnitId,
                'installment_month' => $this->editMonth,
            ],
            [
                'is_paid'     => $this->editIsPaid,
                'amount_paid' => ($this->editIsPaid && $amount !== null) ? $amount : null,
                'note'        => $this->editNote ?: null,
                'paid_at'     => $this->editIsPaid ? now() : null,
                'recorded_by' => Auth::id(),
            ]
        );
        $this->closeEditModal();
    }
    public function getInstallmentMonths(Unit $unit): array
    {
        if (!$unit->akad_date || !$unit->kpr_duration_months) {
            return [];
        }
        $start  = Carbon::parse($unit->akad_date)->startOfMonth()->addMonth();
        $months = [];
        for ($i = 0; $i < $unit->kpr_duration_months; $i++) {
            $months[] = $start->copy()->addMonths($i)->format('Y-m-d');
        }
        return $months;
    }
    public function render()
    {
        $units = Unit::whereNotNull('payment_method')
            ->with(['block'])
            ->withCount(['installmentPayments as paid_installments_count' => function ($query) {
                $query->where('is_paid', true);
            }])
            ->get();
        $totalRevenue            = $units->sum('amount_paid');
        $totalCashUnits          = $units->where('payment_method', 'Cash')->count();
        $totalKprUnits           = $units->where('payment_method', 'KPR')->count();
        $totalMonthlyInstallment = $units->where('payment_method', 'KPR')->sum('monthly_installment');
        $activeUnit         = null;
        $installmentMonths  = [];
        $paidMap            = [];
        $trackerSummary     = null;
        if ($this->activeTrackerUnitId) {
            $activeUnit = $units->firstWhere('id', $this->activeTrackerUnitId);
            if ($activeUnit) {
                $activeUnit->load('installmentPayments');
                $installmentMonths = $this->getInstallmentMonths($activeUnit);
                $paidMap = $activeUnit->installmentPayments
                    ->keyBy(fn($p) => Carbon::parse($p->installment_month)->format('Y-m-d'))
                    ->toArray();
                $paidCount    = collect($paidMap)->where('is_paid', true)->count();
                $totalPaid    = collect($paidMap)->where('is_paid', true)->sum('amount_paid');
                $totalTenor   = count($installmentMonths);
                $remaining    = max(0, $totalTenor - $paidCount);
                $sisaNominal  = max(0, ($activeUnit->monthly_installment * $remaining));
                $trackerSummary = [
                    'paid_count'   => $paidCount,
                    'total_tenor'  => $totalTenor,
                    'remaining'    => $remaining,
                    'total_paid'   => $totalPaid,
                    'sisa_nominal' => $sisaNominal,
                    'pct'          => $totalTenor > 0 ? round(($paidCount / $totalTenor) * 100, 1) : 0,
                ];
            }
        }
        return view('livewire.payment-report', [
            'units'                  => $units,
            'totalRevenue'           => $totalRevenue,
            'totalCashUnits'         => $totalCashUnits,
            'totalKprUnits'          => $totalKprUnits,
            'totalMonthlyInstallment' => $totalMonthlyInstallment,
            'activeUnit'             => $activeUnit,
            'installmentMonths'      => $installmentMonths,
            'paidMap'                => $paidMap,
            'trackerSummary'         => $trackerSummary,
        ]);
    }
}
