@extends('layouts.admin')

@section('title', 'Update Progress - ' . $unit->unit_number)

@section('content')
<div class="container mx-auto max-w-4xl">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('production.show', $unit->id) }}" class="font-label-md text-sm text-primary hover:text-primary-fixed-dim flex items-center gap-1 transition-colors">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Back to Unit Detail
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-surface-container-low rounded-xl shadow-sm border border-outline-variant p-8">
        <div class="mb-8">
            <h1 class="font-headline-lg text-2xl font-bold text-on-surface">Update Progress</h1>
            <p class="font-body-md text-sm text-on-surface-variant mt-1">Unit {{ $unit->unit_number }} • {{ $unit->block->nama_blok }}</p>
        </div>
        
        <form action="{{ route('production.update', $unit->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Construction Stage -->
            <div class="mb-6">
                <label class="block font-label-sm text-sm text-on-surface mb-2">
                    [01] TAHAP PEMBANGUNAN *
                </label>
                <div class="relative">
                    <select name="tahap" required class="w-full bg-surface border border-outline-variant text-on-surface rounded-lg px-4 py-3 font-body-md focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all appearance-none pr-10">
                        <option value="">-- PILIH TAHAP --</option>
                        <option value="Persiapan Lahan">Persiapan Lahan</option>
                        <option value="Pondasi">Pondasi</option>
                        <option value="Struktur & Dinding">Struktur & Dinding</option>
                        <option value="Pengecatan">Pengecatan</option>
                        <option value="Finishing">Finishing</option>
                        <option value="Serah Terima">Serah Terima</option>
                    </select>
                    <span class="material-symbols-outlined absolute right-3 top-3.5 text-on-surface-variant pointer-events-none">arrow_drop_down</span>
                </div>
            </div>
            
            <!-- Progress Percentage -->
            <div class="mb-6">
                <label class="block font-label-sm text-sm text-on-surface mb-2">
                    [02] PERSENTASE (%) *
                </label>
                <div class="grid grid-cols-5 gap-3">
                    @foreach([0, 25, 50, 75, 100] as $pct)
                        <label class="cursor-pointer">
                            <input type="radio" name="persentase" value="{{ $pct }}" class="peer sr-only" required>
                            <div class="bg-surface border border-outline-variant rounded-lg py-3 text-center font-label-md text-sm text-on-surface peer-checked:bg-primary peer-checked:text-on-primary peer-checked:border-primary hover:bg-surface-container-high transition-all">
                                {{ $pct }}%
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
            
            <!-- Notes -->
            <div class="mb-6">
                <label class="block font-label-sm text-sm text-on-surface mb-2">
                    [03] CATATAN
                </label>
                <textarea name="catatan" rows="4" class="w-full bg-surface border border-outline-variant text-on-surface rounded-lg px-4 py-3 font-body-md focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all" placeholder="// Masukkan catatan progres..."></textarea>
            </div>
            
            <!-- Photo Upload -->
            <div class="mb-8">
                <label class="block font-label-sm text-sm text-on-surface mb-2">
                    [04] FOTO DOKUMENTASI
                </label>
                <div class="bg-surface border-2 border-dashed border-outline-variant rounded-lg p-8 text-center hover:bg-surface-container-high hover:border-primary transition-all cursor-pointer group" onclick="document.getElementById('foto').click()">
                    <input type="file" name="foto" id="foto" accept="image/*" class="hidden">
                    <span class="material-symbols-outlined text-[48px] text-on-surface-variant group-hover:text-primary transition-colors mb-3">add_photo_alternate</span>
                    <p class="font-label-md text-sm text-on-surface mb-1">CHOOSE FILE &nbsp; <span class="font-body-sm text-on-surface-variant font-normal">No file chosen</span></p>
                    <p class="font-body-sm text-xs text-on-surface-variant mt-2">// Format: JPG, PNG (Maks. 5MB)</p>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex justify-end gap-3 pt-6 border-t border-outline-variant">
                <a href="{{ route('production.show', $unit->id) }}" class="bg-surface-container-high hover:bg-surface-container-highest text-on-surface font-label-md px-6 py-3 rounded-lg transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                    BATAL
                </a>
                <button type="submit" class="bg-primary hover:bg-on-primary-fixed-variant text-on-primary font-label-md px-6 py-3 rounded-lg shadow-sm transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">check</span>
                    SIMPAN PROGRESS
                </button>
            </div>
        </form>
    </div>
</div>
@endsection