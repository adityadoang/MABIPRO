<?php

namespace App\Livewire\Production;

use App\Models\ConstructionProgress;
use App\Models\ProgressPhoto;
use App\Models\Unit;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Unit Detail - Production')]
class UnitDetail extends Component
{
    use WithFileUploads;

    public $unitId;

    public $tahap = 'Persiapan Lahan';

    public $kategoriProduk = 'Utama';

    public $kategoriManual;

    public $persentase;

    public $catatan;

    public $foto;

    public $isModalOpen = false;

    public const TAHAPAN_UTAMA = ['Persiapan Lahan', 'Pondasi', 'Struktur & Dinding', 'Pengecatan', 'Finishing', 'Serah Terima'];

    public const PRODUK_SAMPINGAN = ['Jalan', 'Masjid', 'Taman', 'Fasilitas Umum'];

    protected $rules = [
        'tahap' => 'nullable|in:Persiapan Lahan,Pondasi,Struktur & Dinding,Pengecatan,Finishing,Serah Terima',
        'kategoriProduk' => 'required|string|in:Utama,Jalan,Masjid,Taman,Fasilitas Umum,Lainnya',
        'kategoriManual' => 'required_if:kategoriProduk,Lainnya|nullable|string|max:100',
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
        $this->reset(['catatan', 'foto', 'kategoriProduk', 'kategoriManual']);
        $this->kategoriProduk = 'Utama';
    }

    public function saveProgress()
    {
        $this->validate();

        $unit = Unit::findOrFail($this->unitId);

        $kategori = null;
        $tahap = $this->tahap ?: 'Persiapan Lahan';
        if ($this->kategoriProduk !== 'Utama') {
            $kategori = ($this->kategoriProduk === 'Lainnya')
                ? trim($this->kategoriManual)
                : $this->kategoriProduk;
            $tahap = 'Lainnya';

            $exists = $unit->constructionProgress()
                ->where('tahap', 'Lainnya')
                ->where('kategori', $kategori)
                ->exists();
            if ($exists) {
                $this->addError('kategoriProduk', "Progres untuk kategori \"{$kategori}\" sudah ada.");

                return;
            }
        }

        $progress = ConstructionProgress::create([
            'unit_id' => $unit->id,
            'tahap' => $tahap,
            'kategori' => $kategori,
            'persentase' => $this->persentase,
            'catatan' => $this->catatan,
            'updated_by' => auth()->id() ?? 1,
        ]);

        $unit->update(['progres_pembangunan' => $this->persentase]);

        if ($this->foto) {
            try {
                $photoPath = $this->foto->storeAs('progress_photos', time().'_'.$this->foto->getClientOriginalName(), 'public');
                ProgressPhoto::create([
                    'unit_id' => $unit->id,
                    'progress_id' => $progress->id,
                    'file_path' => $photoPath,
                    'keterangan' => $this->catatan,
                    'uploaded_by' => auth()->id() ?? 1,
                ]);
            } catch (\Exception $e) {
                Log::error('Upload foto error: '.$e->getMessage());
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

        $allTahap = self::TAHAPAN_UTAMA;
        $chartDataFormatted = collect($allTahap)->map(fn ($tahap) => ['tahap' => $tahap, 'persentase' => $chartData[$tahap] ?? 0]);

        return view('livewire.production.unit-detail', compact('unit', 'progressHistory', 'chartDataFormatted'));
    }
}
