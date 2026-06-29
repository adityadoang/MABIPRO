<?php

namespace App\Livewire\Production;

use App\Models\Unit;
use App\Models\ConstructionProgress;
use App\Models\ProgressPhoto;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;

#[Layout('layouts.app')]
#[Title('Unit Detail - Production')]
class UnitDetail extends Component
{
    use WithFileUploads;

    public $unitId;
    public $tahap = 'Persiapan Lahan';
    public $persentase;
    public $catatan;
    public $foto;
    public $isModalOpen = false;

    protected $rules = [
        'tahap' => 'nullable|in:Persiapan Lahan,Pondasi,Struktur & Dinding,Pengecatan,Finishing,Serah Terima',
        'persentase' => 'required|integer|min:0|max:100',
        'catatan' => 'nullable|string|max:500',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
    ];

    public function mount($id)
    {
        $this->unitId = $id;
        $unit = Unit::findOrFail($id);
        $this->persentase = $unit->progres_pembangunan;
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->reset(['catatan', 'foto']);
    }

    public function saveProgress()
    {
        $this->validate();

        $unit = Unit::findOrFail($this->unitId);
        
        $progress = ConstructionProgress::create([
            'unit_id' => $unit->id,
            'tahap' => $this->tahap ?: 'Persiapan Lahan',
            'persentase' => $this->persentase,
            'catatan' => $this->catatan,
            'updated_by' => auth()->id() ?? 1,
        ]);

        $unit->update(['progres_pembangunan' => $this->persentase]);

        if ($this->foto) {
            try {
                $photoPath = $this->foto->storeAs('progress_photos', time() . '_' . $this->foto->getClientOriginalName(), 'public');
                ProgressPhoto::create([
                    'progress_id' => $progress->id,
                    'file_path' => $photoPath,
                    'keterangan' => $this->catatan,
                    'uploaded_by' => auth()->id() ?? 1,
                ]);
            } catch (\Exception $e) {
                Log::error('Upload foto error: ' . $e->getMessage());
                session()->flash('error', 'Gagal upload foto.');
                return;
            }
        }

        $this->closeModal();
        session()->flash('success', 'Progress berhasil diupdate!');
    }

    public function render()
    {
        $unit = Unit::with(['block', 'constructionProgress.updater', 'constructionProgress.photos'])->findOrFail($this->unitId);
        $progressHistory = $unit->constructionProgress()->orderBy('created_at', 'desc')->get();
        
        $chartData = $unit->constructionProgress()
            ->select('tahap', DB::raw('MAX(persentase) as persentase'))
            ->groupBy('tahap')
            ->pluck('persentase', 'tahap');

        $allTahap = ['Persiapan Lahan', 'Pondasi', 'Struktur & Dinding', 'Pengecatan', 'Finishing', 'Serah Terima'];
        $chartDataFormatted = collect($allTahap)->map(fn($tahap) => ['tahap' => $tahap, 'persentase' => $chartData[$tahap] ?? 0]);

        return view('livewire.production.unit-detail', compact('unit', 'progressHistory', 'chartDataFormatted'));
    }
}
