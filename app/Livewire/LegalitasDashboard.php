<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Layout;
use App\Models\Unit;
use App\Models\LegalDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

/**
 * Komponen Livewire untuk Dashboard Legalitas.
 * Menangani manajemen dokumen legalitas unit perumahan secara reaktif
 * tanpa perlu reload halaman.
 */
#[Layout('layouts.app')]
class LegalitasDashboard extends Component
{
    use WithFileUploads;

    // ─────────────────────────────────────────────────────────────
    // STATE / PROPERTI
    // ─────────────────────────────────────────────────────────────

    /** Kata kunci pencarian unit atau dokumen */
    public string $search = '';

    /** State untuk form upload — per unit (key = unit_id) */
    public $uploadDocumentName = '';
    public $uploadDocumentNumber = '';
    public $uploadFile = null;

    /** ID unit yang sedang aktif di form upload */
    public ?int $uploadingUnitId = null;

    // ─────────────────────────────────────────────────────────────
    // FUNGSI INTERAKSI PENGGUNA
    // ─────────────────────────────────────────────────────────────

    /**
     * Membuka form upload untuk unit tertentu.
     * Menutup form lain yang mungkin sedang terbuka.
     */
    public function openUploadForm(int $unitId): void
    {
        $this->uploadingUnitId = ($this->uploadingUnitId === $unitId) ? null : $unitId;
        $this->resetUploadForm();
    }

    /**
     * FR-010: Upload Dokumen + NFR-S01: Enkripsi AES-256
     * Dipanggil via wire:submit.prevent="uploadDocument"
     */
    public function uploadDocument(): void
    {
        $this->validate([
            'uploadDocumentName'   => 'required|string|max:255',
            'uploadDocumentNumber' => 'required|string|max:100',
            'uploadFile'           => 'required|file|mimes:pdf|max:5120', // NFR-P03: Maks 5MB
        ]);

        $unit = Unit::findOrFail($this->uploadingUnitId);

        // NFR-S01: Enkripsi AES-256
        $fileContent      = file_get_contents($this->uploadFile->getRealPath());
        $encryptedContent = Crypt::encryptString($fileContent);

        $filename = time() . '_' . $this->uploadFile->getClientOriginalName() . '.enc';
        $path     = 'legal_docs/' . $filename;

        Storage::put($path, $encryptedContent);

        LegalDocument::create([
            'unit_id'         => $unit->id,
            'document_name'   => $this->uploadDocumentName,
            'document_number' => $this->uploadDocumentNumber,
            'file_path'       => $path,
            'uploaded_by'     => Auth::id(),
        ]);

        $this->resetUploadForm();
        $this->uploadingUnitId = null;

        session()->flash('message', 'Dokumen "' . $this->uploadDocumentName . '" berhasil diunggah.');
    }

    /**
     * Menandai unit sebagai Lengkap secara manual.
     * Dipanggil via wire:click="markAsComplete(unitId)"
     */
    public function markAsComplete(int $unitId): void
    {
        $unit = Unit::findOrFail($unitId);
        $unit->update(['status_legalitas' => 'Lengkap']);

        session()->flash('message', 'Unit ' . $unit->unit_number . ' berhasil ditandai sebagai Lengkap.');
    }

    /**
     * FR-012: Hapus Dokumen — hanya Admin
     * Dipanggil via wire:click="deleteDocument(docId)"
     */
    public function deleteDocument(int $documentId): void
    {
        if (Auth::user()->role !== 'Admin') {
            session()->flash('error', 'Hanya Admin yang dapat menghapus dokumen.');
            return;
        }

        $document = LegalDocument::findOrFail($documentId);

        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }

        $document->delete();

        session()->flash('message', 'Dokumen berhasil dihapus.');
    }

    // ─────────────────────────────────────────────────────────────
    // HELPER METHODS
    // ─────────────────────────────────────────────────────────────

    private function resetUploadForm(): void
    {
        $this->uploadDocumentName   = '';
        $this->uploadDocumentNumber = '';
        $this->uploadFile           = null;
        $this->resetValidation();
    }

    // ─────────────────────────────────────────────────────────────
    // RENDER
    // ─────────────────────────────────────────────────────────────

    public function render()
    {
        // Query semua unit dengan relasi, filter berdasarkan search
        $unitsQuery = Unit::with(['legalDocuments', 'block']);

        if ($this->search !== '') {
            $keyword = '%' . strtolower($this->search) . '%';
            $unitsQuery->where(function ($q) use ($keyword) {
                $q->where('unit_number', 'like', $keyword)
                  ->orWhereHas('legalDocuments', function ($q2) use ($keyword) {
                      $q2->where('document_name', 'like', $keyword)
                         ->orWhere('document_number', 'like', $keyword);
                  });
            });
        }

        $units = $unitsQuery->get();

        // Hitung statistik
        $totalUnits     = $units->count();
        $compliant      = $units->where('status_legalitas', 'Lengkap')->count();
        $inProgress     = $units->filter(fn($u) => $u->status_legalitas !== 'Lengkap' && $u->legalDocuments->count() > 0)->count();
        $missing        = $units->filter(fn($u) => $u->status_legalitas !== 'Lengkap' && $u->legalDocuments->count() === 0)->count();
        $completionRate = $totalUnits > 0 ? round(($compliant / $totalUnits) * 100) : 0;

        $stats = compact('totalUnits', 'compliant', 'inProgress', 'missing', 'completionRate');

        // Kelompokkan unit per blok
        $groupedUnits = $units->groupBy(fn($u) => $u->block?->nama_blok ?? 'Unknown Block');

        return view('livewire.legalitas-dashboard', [
            'stats'        => $stats,
            'groupedUnits' => $groupedUnits,
        ]);
    }
}
