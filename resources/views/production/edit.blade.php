@extends('layouts.production')

@section('title', 'Update Progress - ' . $unit->unit_number)

@section('content')
{{-- ✅ EMERALD GREEN THEME - CSS Inline --}}
<style>
/* ==========================================================================
   MABIPRO Production Module - Emerald Green Theme
   Override Material Design ke Hijau Emerald (Edit Form)
   ========================================================================== */

/* Primary Colors Override */
.bg-primary { background-color: #10B981 !important; }
.bg-primary-dark { background-color: #059669 !important; }
.bg-primary-container { background-color: #D1FAE5 !important; }

.text-primary { color: #10B981 !important; }
.text-primary-600 { color: #10B981 !important; }
.text-primary-700 { color: #047857 !important; }
.text-on-primary { color: white !important; }

.border-primary { border-color: #10B981 !important; }

/* Hover States */
.hover\:bg-primary-dark:hover { background-color: #059669 !important; }
.hover\:text-primary-700:hover { color: #047857 !important; }

/* ==========================================================================
   Form Container
   ========================================================================== */
.form-container {
    background: white !important;
    border: 1px solid #E5E7EB !important;
    border-radius: 12px !important;
    padding: 24px !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
    max-width: 800px !important;
    margin: 0 auto !important;
}

.form-group {
    margin-bottom: 20px !important;
}

.form-label {
    display: block !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    color: #374151 !important;
    margin-bottom: 8px !important;
    letter-spacing: 0.5px !important;
}

/* ==========================================================================
   Form Inputs
   ========================================================================== */
.form-input,
.form-select,
.form-textarea {
    width: 100% !important;
    border: 1px solid #D1D5DB !important;
    border-radius: 8px !important;
    padding: 10px 12px !important;
    font-size: 14px !important;
    transition: all 0.2s !important;
    background-color: white !important;
    color: #111827 !important;
}

.form-input:focus,
.form-select:focus,
.form-textarea:focus {
    outline: none !important;
    border-color: #10B981 !important;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
}

.form-textarea {
    resize: vertical !important;
    min-height: 100px !important;
}

/* ==========================================================================
   Radio Button Group
   ========================================================================== */
.radio-group {
    display: grid !important;
    grid-template-columns: repeat(5, 1fr) !important;
    gap: 12px !important;
}

.radio-option {
    cursor: pointer !important;
}

.radio-option input[type="radio"] {
    position: absolute !important;
    opacity: 0 !important;
}

.radio-option .radio-label {
    display: block !important;
    border: 2px solid #D1D5DB !important;
    border-radius: 8px !important;
    padding: 12px !important;
    text-align: center !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    color: #374151 !important;
    transition: all 0.2s !important;
    background-color: white !important;
}

.radio-option input[type="radio"]:checked + .radio-label {
    background-color: #10B981 !important;
    color: white !important;
    border-color: #10B981 !important;
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3) !important;
}

.radio-option .radio-label:hover {
    background-color: #ECFDF5 !important;
    border-color: #10B981 !important;
}

/* ==========================================================================
   Drop Zone Upload
   ========================================================================== */
.drop-zone {
    border: 2px dashed #D1D5DB !important;
    transition: all 0.2s !important;
    background-color: #F9FAFB !important;
}

.drop-zone:hover {
    border-color: #10B981 !important;
    background-color: #ECFDF5 !important;
}

/* ==========================================================================
   Buttons
   ========================================================================== */
.btn {
    padding: 10px 20px !important;
    border-radius: 8px !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    transition: all 0.2s !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 8px !important;
}

.btn-primary {
    background-color: #10B981 !important;
    color: white !important;
    border: none !important;
}

.btn-primary:hover {
    background-color: #059669 !important;
    box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3) !important;
}

.btn-secondary {
    background-color: white !important;
    color: #374151 !important;
    border: 1px solid #D1D5DB !important;
}

.btn-secondary:hover {
    background-color: #F9FAFB !important;
    border-color: #9CA3AF !important;
}

/* ==========================================================================
   Form Actions
   ========================================================================== */
.form-actions {
    display: flex !important;
    justify-content: flex-end !important;
    gap: 12px !important;
    padding-top: 20px !important;
    border-top: 1px solid #E5E7EB !important;
    margin-top: 20px !important;
}

/* ==========================================================================
   Links
   ========================================================================== */
a.text-primary-600 {
    color: #10B981 !important;
}

a.text-primary-600:hover {
    color: #047857 !important;
}

/* ==========================================================================
   Responsive
   ========================================================================== */
@media (max-width: 768px) {
    .radio-group {
        grid-template-columns: repeat(3, 1fr) !important;
    }
    
    .form-actions {
        flex-direction: column !important;
    }
    
    .btn {
        width: 100% !important;
    }
}
</style>

<div class="mb-6">
    <a href="{{ route('production.show', $unit->id) }}" class="text-sm text-primary-600 hover:text-primary-700 flex items-center gap-1">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
        Back to Unit Detail
    </a>
</div>

<div class="max-w-4xl mx-auto">
    <div class="form-container">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Update Progress</h1>
            <p class="text-sm text-gray-500 mt-1">Unit {{ $unit->unit_number }} • {{ $unit->block->nama_blok }}</p>
        </div>
        
        <form action="{{ route('production.update', $unit->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">[01] TAHAP PEMBANGUNAN *</label>
                <select name="tahap" required class="form-select">
                    <option value="">-- PILIH TAHAP --</option>
                    <option value="Persiapan Lahan">Persiapan Lahan</option>
                    <option value="Pondasi">Pondasi</option>
                    <option value="Struktur & Dinding">Struktur & Dinding</option>
                    <option value="Pengecatan">Pengecatan</option>
                    <option value="Finishing">Finishing</option>
                    <option value="Serah Terima">Serah Terima</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">[02] PERSENTASE (%) *</label>
                <div class="radio-group">
                    @foreach([0, 25, 50, 75, 100] as $pct)
                        <label class="radio-option">
                            <input type="radio" name="persentase" value="{{ $pct }}" required>
                            <div class="radio-label">{{ $pct }}%</div>
                        </label>
                    @endforeach
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">[03] CATATAN</label>
                <textarea name="catatan" rows="3" class="form-textarea" placeholder="// Masukkan catatan progres..."></textarea>
            </div>
            
            <div class="form-group">
                <label class="form-label">[04] FOTO DOKUMENTASI</label>
                <div class="drop-zone rounded-lg p-8 text-center cursor-pointer" onclick="document.getElementById('foto').click()">
                    <input type="file" name="foto" id="foto" accept="image/*" class="hidden">
                    <svg class="w-10 h-10 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="text-sm text-gray-700 font-medium">CHOOSE FILE &nbsp; No file chosen</p>
                    <p class="text-xs text-gray-500 mt-2">// Format: JPG, PNG (Maks. 5MB)</p>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="{{ route('production.show', $unit->id) }}" class="btn btn-secondary">
                    ✕ BATAL
                </a>
                <button type="submit" class="btn btn-primary">
                    ✓ SIMPAN PROGRESS
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Tampilkan nama file saat dipilih
document.getElementById('foto').addEventListener('change', function(e) {
    const fileName = e.target.files[0] ? e.target.files[0].name : 'No file chosen';
    const label = document.querySelector('.drop-zone p.text-sm');
    if (label) {
        label.textContent = 'CHOOSE FILE  ' + fileName;
    }
});
</script>
@endsection