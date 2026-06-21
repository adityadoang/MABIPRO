@extends('layouts.production')

@section('title', 'Update Progres ' . $unit->unit_number)

@section('content')
<div class="mb-6">
    <a href="{{ route('production.show', $unit->id) }}" 
       class="font-mono text-sm text-rust hover:text-rust-dark inline-flex items-center gap-2">
        ← KEMBALI KE DETAIL UNIT
    </a>
</div>

<div class="card-industrial max-w-2xl mx-auto relative">
    <span class="corner-rivet" style="top: 8px; left: 8px;"></span>
    <span class="corner-rivet" style="top: 8px; right: 8px;"></span>
    <span class="corner-rivet" style="bottom: 8px; left: 8px;"></span>
    <span class="corner-rivet" style="bottom: 8px; right: 8px;"></span>
    
    <div class="bg-industrial-900 text-industrial-50 px-6 py-4 flex items-center gap-2">
        <span class="rivet"></span>
        <div>
            <span class="label-tag bg-rust">FORM INPUT</span>
            <h1 class="font-display text-2xl font-bold tracking-wider mt-1">
                UPDATE PROGRESS // UNIT {{ $unit->unit_number }}
            </h1>
        </div>
    </div>
    
    <form action="{{ route('production.update', $unit->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-5 bg-industrial-50">
        @csrf
        @method('PUT')
        
        <!-- Tahap -->
        <div>
            <label class="block font-mono text-xs text-industrial-900 tracking-wider mb-2">
                [01] TAHAP PEMBANGUNAN <span class="text-rust">*</span>
            </label>
            <select name="tahap" required
                    class="w-full border-2 border-industrial-900 bg-industrial-50 px-3 py-2 font-mono text-sm focus:outline-none focus:border-rust focus:shadow-[3px_3px_0px_#BF360C]">
                <option value="">-- PILIH TAHAP --</option>
                <option value="Persiapan Lahan">PERSIAPAN LAHAN</option>
                <option value="Pondasi">PONDASI</option>
                <option value="Struktur & Dinding">STRUKTUR & DINDING</option>
                <option value="Pengecatan">PENGE CATAN</option>
                <option value="Finishing">FINISHING</option>
                <option value="Serah Terima">SERAH TERIMA</option>
            </select>
        </div>
        
        <!-- Persentase -->
        <div>
            <label class="block font-mono text-xs text-industrial-900 tracking-wider mb-2">
                [02] PERSENTASE (%) <span class="text-rust">*</span>
            </label>
            <input type="number" name="persentase" min="0" max="100" required
                   class="w-full border-2 border-industrial-900 bg-industrial-50 px-3 py-2 font-mono text-sm focus:outline-none focus:border-rust focus:shadow-[3px_3px_0px_#BF360C]"
                   placeholder="0 - 100">
        </div>
        
        <!-- Catatan -->
        <div>
            <label class="block font-mono text-xs text-industrial-900 tracking-wider mb-2">
                [03] CATATAN
            </label>
            <textarea name="catatan" rows="4"
                      class="w-full border-2 border-industrial-900 bg-industrial-50 px-3 py-2 font-mono text-sm focus:outline-none focus:border-rust focus:shadow-[3px_3px_0px_#BF360C]"
                      placeholder="// Masukkan catatan progres..."></textarea>
        </div>
        
        <!-- Foto -->
        <div>
            <label class="block font-mono text-xs text-industrial-900 tracking-wider mb-2">
                [04] FOTO DOKUMENTASI
            </label>
            <input type="file" name="foto" accept="image/*"
                   class="w-full border-2 border-industrial-900 bg-industrial-50 px-3 py-2 font-mono text-sm file:mr-3 file:border-0 file:bg-industrial-900 file:text-industrial-50 file:px-3 file:py-1 file:font-display file:uppercase file:tracking-wider file:cursor-pointer">
            <p class="font-mono text-xs text-industrial-700 mt-1">
                // Format: JPG, PNG (Maks. 2MB)
            </p>
        </div>
        
        <!-- Actions -->
        <div class="flex gap-3 pt-4 border-t-2 border-dashed border-industrial-700">
            <button type="submit" 
                    class="btn-industrial bg-rust text-industrial-50 px-6 py-3 hover:bg-rust-dark">
                ✓ SIMPAN PROGRESS
            </button>
            <a href="{{ route('production.show', $unit->id) }}" 
               class="btn-industrial bg-industrial-100 text-industrial-900 px-6 py-3 hover:bg-industrial-200">
                ✗ BATAL
            </a>
        </div>
    </form>
</div>
@endsection