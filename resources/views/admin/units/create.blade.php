@extends('layouts.admin')

@section('title', 'Tambah Unit')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.units.index') }}" 
       class="font-mono text-sm text-rust hover:text-rust-dark">← KEMBALI</a>
</div>

<div class="card-industrial max-w-xl relative">
    <span class="corner-rivet" style="top: 8px; left: 8px;"></span>
    <span class="corner-rivet" style="top: 8px; right: 8px;"></span>
    <span class="corner-rivet" style="bottom: 8px; left: 8px;"></span>
    <span class="corner-rivet" style="bottom: 8px; right: 8px;"></span>
    
    <div class="bg-industrial-900 text-industrial-50 px-6 py-4 flex items-center gap-2">
        <span class="rivet"></span>
        <h1 class="font-display text-2xl font-bold tracking-wider">TAMBAH UNIT BARU</h1>
    </div>
    
    <form action="{{ route('admin.units.store') }}" method="POST" class="p-6 space-y-5 bg-industrial-50">
        @csrf
        
        <div>
            <label class="block font-mono text-xs text-industrial-900 tracking-wider mb-2">
                [01] BLOK <span class="text-rust">*</span>
            </label>
            <select name="block_id" required
                    class="w-full border-2 border-industrial-900 bg-industrial-50 px-3 py-2 font-mono text-sm focus:outline-none focus:border-rust">
                <option value="">-- PILIH BLOK --</option>
                @foreach($blocks as $block)
                    <option value="{{ $block->id }}">{{ strtoupper($block->nama_blok) }}</option>
                @endforeach
            </select>
            @error('block_id')
                <p class="text-rust font-mono text-xs mt-1">// {{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label class="block font-mono text-xs text-industrial-900 tracking-wider mb-2">
                [02] NOMOR UNIT <span class="text-rust">*</span>
            </label>
            <input type="text" name="unit_number" required
                   class="w-full border-2 border-industrial-900 bg-industrial-50 px-3 py-2 font-mono text-sm focus:outline-none focus:border-rust"
                   placeholder="Contoh: A-01">
            @error('unit_number')
                <p class="text-rust font-mono text-xs mt-1">// {{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label class="block font-mono text-xs text-industrial-900 tracking-wider mb-2">
                [03] HARGA UNIT (Rp)
            </label>
            <input type="number" name="harga_unit" min="0"
                   class="w-full border-2 border-industrial-900 bg-industrial-50 px-3 py-2 font-mono text-sm focus:outline-none focus:border-rust"
                   placeholder="Contoh: 350000000">
        </div>
        
        <div class="flex gap-3 pt-4 border-t-2 border-dashed border-industrial-700">
            <button type="submit" class="btn-industrial bg-rust text-industrial-50 px-6 py-3">
                ✓ SIMPAN
            </button>
            <a href="{{ route('admin.units.index') }}" 
               class="btn-industrial bg-industrial-100 text-industrial-900 px-6 py-3">
                ✗ BATAL
            </a>
        </div>
    </form>
</div>
@endsection