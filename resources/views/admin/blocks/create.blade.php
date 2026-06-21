@extends('layouts.admin')

@section('title', 'Tambah Blok')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="font-headline-md text-headline-md font-bold text-primary dark:text-primary-fixed mb-2">Tambah Blok Baru</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">Tambahkan data blok properti baru ke sistem.</p>
    </div>
    <a href="{{ route('admin.blocks.index') }}" class="flex items-center gap-2 bg-surface-container-high hover:bg-surface-dim text-on-surface px-4 py-2 rounded-xl transition-all font-label-md font-bold">
        <span class="material-symbols-outlined text-sm">arrow_back</span>
        Kembali
    </a>
</div>

<div class="bg-surface-container dark:bg-surface-container-low rounded-3xl p-6 md:p-8 border border-outline-variant dark:border-outline">
    <form action="{{ route('admin.blocks.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label class="block font-label-md font-bold text-on-surface mb-2">
                Nama Blok <span class="text-error">*</span>
            </label>
            <input type="text" name="nama_blok" required
                   class="w-full bg-surface-bright dark:bg-surface-dim border border-outline-variant dark:border-outline rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                   placeholder="Contoh: Blok A">
            @error('nama_blok')
                <p class="text-error font-body-sm mt-1 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[16px]">error</span>
                    {{ $message }}
                </p>
            @enderror
        </div>
        
        <div>
            <label class="block font-label-md font-bold text-on-surface mb-2">
                Deskripsi
            </label>
            <textarea name="deskripsi" rows="4"
                      class="w-full bg-surface-bright dark:bg-surface-dim border border-outline-variant dark:border-outline rounded-xl px-4 py-3 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                      placeholder="Masukkan deskripsi blok (opsional)..."></textarea>
        </div>
        
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-outline-variant dark:border-outline">
            <a href="{{ route('admin.blocks.index') }}" 
               class="bg-surface-container-high hover:bg-surface-dim text-on-surface font-label-md font-bold px-6 py-3 rounded-xl transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">close</span>
                Batal
            </a>
            <button type="submit" class="bg-primary hover:opacity-90 text-on-primary font-label-md font-bold px-6 py-3 rounded-xl transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">save</span>
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection