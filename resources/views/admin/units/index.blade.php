@extends('layouts.admin')

@section('title', 'Kelola Unit')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
    <div>
        <h2 class="font-headline-md text-headline-md font-bold text-primary">Kelola Unit</h2>
        <p class="font-body-md text-body-md text-on-surface-variant">Daftar semua unit di seluruh blok perumahan</p>
    </div>
    <a href="{{ route('admin.units.create') }}" class="bg-primary text-on-primary hover:bg-primary/90 font-label-md font-bold py-2 px-4 rounded-lg flex items-center gap-2 transition-colors">
        <span class="material-symbols-outlined">add</span>
        Tambah Unit
    </a>
</div>

<div class="bg-surface-container-low rounded-2xl border border-outline-variant overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left font-body-sm text-on-surface">
            <thead class="bg-surface-container border-b border-outline-variant text-on-surface-variant font-label-md">
                <tr>
                    <th class="py-3 px-4 font-bold">BLOK</th>
                    <th class="py-3 px-4 font-bold">NO UNIT</th>
                    <th class="py-3 px-4 font-bold">HARGA</th>
                    <th class="py-3 px-4 font-bold text-center">STATUS</th>
                    <th class="py-3 px-4 font-bold text-center">PROGRESS</th>
                    <th class="py-3 px-4 font-bold text-right">AKSI</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant">
                @forelse($units as $unit)
                <tr class="hover:bg-surface-container-high transition-colors">
                    <td class="py-3 px-4 font-bold">{{ strtoupper($unit->block->nama_blok) }}</td>
                    <td class="py-3 px-4 font-bold text-primary">{{ $unit->unit_number }}</td>
                    <td class="py-3 px-4">{{ $unit->harga_unit ? 'Rp ' . number_format($unit->harga_unit, 0, ',', '.') : '-' }}</td>
                    <td class="py-3 px-4 text-center">
                        <span class="py-1 px-2 rounded-md font-bold text-[10px] uppercase tracking-wider
                            @if($unit->status_penjualan == 'Terjual') bg-primary text-on-primary
                            @elseif($unit->status_penjualan == 'Sudah DP') bg-secondary-container text-on-secondary-container
                            @else bg-surface-container-highest text-on-surface-variant
                            @endif">
                            {{ $unit->status_penjualan }}
                        </span>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <div class="flex items-center gap-2 justify-center">
                            <div class="w-16 h-2 bg-surface-container-highest rounded-full overflow-hidden">
                                <div class="h-full bg-tertiary" style="width: {{ $unit->progres_pembangunan }}%"></div>
                            </div>
                            <span class="text-xs font-bold text-tertiary">{{ $unit->progres_pembangunan }}%</span>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('production.show', $unit->id) }}" class="text-tertiary hover:text-tertiary/80 transition-colors p-1" title="Detail Progress">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </a>
                            <a href="{{ route('admin.units.edit', $unit->id) }}" class="text-secondary hover:text-primary transition-colors p-1" title="Edit">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <form action="{{ route('admin.units.destroy', $unit->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus unit ini?')">
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
                    <td colspan="6" class="py-8 px-4 text-center text-on-surface-variant">
                        <div class="flex flex-col items-center gap-2 justify-center">
                            <span class="material-symbols-outlined text-4xl text-outline">house</span>
                            <span>Belum ada data unit</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection