@extends('layouts.admin')

@section('title', 'Edit Unit')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="font-headline-md text-headline-md font-bold text-primary dark:text-primary-fixed mb-2">Edit Unit: {{ strtoupper($unit->unit_number) }}</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">Perbarui informasi data unit properti.</p>
    </div>
    <a href="{{ route('admin.units.index') }}" class="flex items-center gap-2 bg-surface-container-high hover:bg-surface-dim text-on-surface px-4 py-2 rounded-xl transition-all font-label-md font-bold">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        Kembali
    </a>
</div>

<div class="bg-surface-container dark:bg-surface-container-low rounded-3xl p-6 md:p-8 border border-outline-variant dark:border-outline">
    <form action="{{ route('admin.units.update', $unit->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label class="block font-label-md font-bold text-on-surface mb-2">
                Blok <span class="text-error">*</span>
            </label>
            <select name="block_id" required
                    class="w-full bg-surface-bright dark:bg-surface-dim border border-outline-variant dark:border-outline rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                <option value="">-- PILIH BLOK --</option>
                @foreach($blocks as $block)
                    <option value="{{ $block->id }}" {{ old('block_id', $unit->block_id) == $block->id ? 'selected' : '' }}>
                        {{ strtoupper($block->nama_blok) }}
                    </option>
                @endforeach
            </select>
            @error('block_id')
                <p class="text-error font-body-sm mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">error</span>
                    {{ $message }}
                </p>
            @enderror
        </div>
        
        <div>
            <label class="block font-label-md font-bold text-on-surface mb-2">
                Nomor Unit <span class="text-error">*</span>
            </label>
            <input type="text" name="unit_number" value="{{ old('unit_number', $unit->unit_number) }}" required
                   class="w-full bg-surface-bright dark:bg-surface-dim border border-outline-variant dark:border-outline rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                   placeholder="Contoh: A-01">
            @error('unit_number')
                <p class="text-error font-body-sm mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">error</span>
                    {{ $message }}
                </p>
            @enderror
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-label-md font-bold text-on-surface mb-2">
                    Nama Pembeli
                </label>
                <input type="text" name="customer_name" value="{{ old('customer_name', $unit->customer_name) }}"
                       class="w-full bg-surface-bright dark:bg-surface-dim border border-outline-variant dark:border-outline rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                       placeholder="Contoh: Budi Santoso">
            </div>
            
            <div>
                <label class="block font-label-md font-bold text-on-surface mb-2">
                    No. Telepon
                </label>
                <input type="text" name="customer_phone" value="{{ old('customer_phone', $unit->customer_phone) }}"
                       class="w-full bg-surface-bright dark:bg-surface-dim border border-outline-variant dark:border-outline rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                       placeholder="Contoh: 081234567890">
            </div>
        </div>
        
        <div>
            <label class="block font-label-md font-bold text-on-surface mb-2">
                Harga Unit (Rp)
            </label>
            <input type="number" name="harga_unit" value="{{ old('harga_unit', $unit->harga_unit) }}" min="0"
                   class="w-full bg-surface-bright dark:bg-surface-dim border border-outline-variant dark:border-outline rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                   placeholder="Contoh: 350000000">
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block font-label-md font-bold text-on-surface mb-2">
                    Luas Tanah (m²)
                </label>
                <input type="number" name="luas_tanah" value="{{ old('luas_tanah', $unit->luas_tanah) }}" min="0" step="0.01"
                       class="w-full bg-surface-bright dark:bg-surface-dim border border-outline-variant dark:border-outline rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                       placeholder="72">
            </div>
            <div>
                <label class="block font-label-md font-bold text-on-surface mb-2">
                    Luas Bangunan (m²)
                </label>
                <input type="number" name="luas_bangunan" value="{{ old('luas_bangunan', $unit->luas_bangunan) }}" min="0" step="0.01"
                       class="w-full bg-surface-bright dark:bg-surface-dim border border-outline-variant dark:border-outline rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                       placeholder="36">
            </div>
        </div>
        
        <div>
            <label class="block font-label-md font-bold text-on-surface mb-2">
                Status Penjualan <span class="text-error">*</span>
            </label>
            <select name="status_penjualan" required
                    class="w-full bg-surface-bright dark:bg-surface-dim border border-outline-variant dark:border-outline rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                <option value="Belum Terjual" {{ old('status_penjualan', $unit->status_penjualan) == 'Belum Terjual' ? 'selected' : '' }}>BELUM TERJUAL</option>
                <option value="Sudah DP" {{ old('status_penjualan', $unit->status_penjualan) == 'Sudah DP' ? 'selected' : '' }}>SUDAH DP</option>
                <option value="Terjual" {{ old('status_penjualan', $unit->status_penjualan) == 'Terjual' ? 'selected' : '' }}>TERJUAL</option>
            </select>
        </div>
        
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-outline-variant dark:border-outline">
            <a href="{{ route('admin.units.index') }}" 
               class="bg-surface-container-high hover:bg-surface-dim text-on-surface font-label-md font-bold px-6 py-3 rounded-xl transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">close</span>
                Batal
            </a>
            <button type="submit" class="bg-primary hover:opacity-90 text-on-primary font-label-md font-bold px-6 py-3 rounded-xl transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">check</span>
                Update
            </button>
        </div>
    </form>
</div>
@endsection