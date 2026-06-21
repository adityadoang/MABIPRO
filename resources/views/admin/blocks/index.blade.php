@extends('layouts.admin')

@section('title', 'Kelola Blok')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="font-headline-md text-headline-md font-bold text-primary">Kelola Blok</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">Daftar blok perumahan yang tersedia</p>
    </div>
    <a href="{{ route('admin.blocks.create') }}" class="bg-primary text-on-primary hover:bg-primary/90 font-label-md font-bold py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
        <span class="material-symbols-outlined">add</span>
        Tambah Blok
    </a>
</div>

<div class="bg-surface-container-low rounded-2xl border border-outline-variant overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left font-body-sm text-on-surface">
            <thead class="bg-surface-container border-b border-outline-variant text-on-surface-variant font-label-md">
                <tr>
                    <th class="py-3 px-4 font-bold">NAMA BLOK</th>
                    <th class="py-3 px-4 font-bold">DESKRIPSI</th>
                    <th class="py-3 px-4 font-bold text-center">JUMLAH UNIT</th>
                    <th class="py-3 px-4 font-bold text-right">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($blocks as $block)
                <tr class="hover:bg-surface-container-high transition-colors">
                    <td class="py-3 px-4 font-bold text-primary">{{ strtoupper($block->nama_blok) }}</td>
                    <td class="py-3 px-4">{{ $block->deskripsi ?? '-' }}</td>
                    <td class="py-3 px-4 text-center">
                        <span class="bg-secondary-container text-on-secondary-container py-1 px-2 rounded-md font-bold text-xs">{{ $block->units_count }}</span>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.blocks.edit', $block->id) }}" class="text-secondary hover:text-primary transition-colors p-1" title="Edit">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <form action="{{ route('admin.blocks.destroy', $block->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus blok ini? Semua unit di dalamnya juga akan terhapus!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-error hover:text-error/80 transition-colors p-1" title="Hapus">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-8 px-4 text-center text-on-surface-variant flex flex-col items-center gap-2 justify-center">
                        <span class="material-symbols-outlined text-4xl text-outline">domain_disabled</span>
                        <span>Belum ada data blok</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection