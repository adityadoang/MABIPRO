@extends('layouts.production')

@section('title', 'Update Progress - ' . $unit->unit_number)

@section('content')
<div class="container mx-auto px-4 py-8 max-w-4xl">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('production.show', $unit->id) }}" class="text-primary-600 hover:text-primary-700 flex items-center gap-1 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Unit Detail
        </a>
    </div>

    <!-- Form Container -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Update Progress</h1>
            <p class="text-sm text-gray-500 mt-1">Unit {{ $unit->unit_number }} • {{ $unit->block->nama_blok }}</p>
        </div>
        
        <form action="{{ route('production.update', $unit->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <!-- Construction Stage -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    [01] TAHAP PEMBANGUNAN *
                </label>
                <select name="tahap" required class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                    <option value="">-- PILIH TAHAP --</option>
                    <option value="Persiapan Lahan">Persiapan Lahan</option>
                    <option value="Pondasi">Pondasi</option>
                    <option value="Struktur & Dinding">Struktur & Dinding</option>
                    <option value="Pengecatan">Pengecatan</option>
                    <option value="Finishing">Finishing</option>
                    <option value="Serah Terima">Serah Terima</option>
                </select>
            </div>
            
            <!-- Progress Percentage -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    [02] PERSENTASE (%) *
                </label>
                <div class="grid grid-cols-5 gap-3">
                    @foreach([0, 25, 50, 75, 100] as $pct)
                        <label class="cursor-pointer">
                            <input type="radio" name="persentase" value="{{ $pct }}" class="peer sr-only" required>
                            <div class="border-2 border-gray-300 rounded-lg py-3 text-center text-sm font-medium text-gray-700 peer-checked:bg-primary-600 peer-checked:text-white peer-checked:border-primary-600 hover:bg-gray-50 transition">
                                {{ $pct }}%
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
            
            <!-- Notes -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    [03] CATATAN
                </label>
                <textarea name="catatan" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="// Masukkan catatan progres..."></textarea>
            </div>
            
            <!-- Photo Upload -->
            <div class="mb-6">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    [04] FOTO DOKUMENTASI
                </label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-primary-500 transition cursor-pointer" onclick="document.getElementById('foto').click()">
                    <input type="file" name="foto" id="foto" accept="image/*" class="hidden">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <p class="text-sm text-gray-600 font-medium">CHOOSE FILE &nbsp; No file chosen</p>
                    <p class="text-xs text-gray-500 mt-2">// Format: JPG, PNG (Maks. 2MB)</p>
                </div>
            </div>
            
            <!-- Form Actions -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                <a href="{{ route('production.show', $unit->id) }}" class="px-6 py-3 border-2 border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    ✕ BATAL
                </a>
                <button type="submit" class="px-6 py-3 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 transition">
                    ✓ SIMPAN PROGRESS
                </button>
            </div>
        </form>
    </div>
</div>
@endsection