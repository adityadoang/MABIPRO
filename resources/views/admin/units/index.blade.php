@extends('layouts.admin')

@section('title', 'Data Unit')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <span class="label-tag bg-rust">MASTER DATA</span>
        <h2 class="font-display text-3xl font-bold text-industrial-900 mt-2">DATA UNIT</h2>
    </div>
    <a href="{{ route('admin.units.create') }}" 
       class="btn-industrial bg-rust text-industrial-50 px-4 py-2">
        + TAMBAH UNIT
    </a>
</div>

<div class="card-industrial">
    <table class="w-full">
        <thead>
            <tr class="bg-industrial-900 text-industrial-50">
                <th class="px-4 py-3 text-left font-display tracking-wider">ID</th>
                <th class="px-4 py-3 text-left font-display tracking-wider">BLOK</th>
                <th class="px-4 py-3 text-left font-display tracking-wider">NO UNIT</th>
                <th class="px-4 py-3 text-left font-display tracking-wider">HARGA</th>
                <th class="px-4 py-3 text-center font-display tracking-wider">STATUS</th>
                <th class="px-4 py-3 text-center font-display tracking-wider">PROGRESS</th>
                <th class="px-4 py-3 text-center font-display tracking-wider">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($units as $unit)
                <tr class="border-t-2 border-industrial-700 hover:bg-industrial-100">
                    <td class="px-4 py-3 font-mono text-sm">{{ $unit->id }}</td>
                    <td class="px-4 py-3 font-display font-bold">
                        {{ strtoupper($unit->block->nama_blok) }}
                    </td>
                    <td class="px-4 py-3 font-mono text-sm">{{ $unit->unit_number }}</td>
                    <td class="px-4 py-3 font-mono text-sm">
                        {{ $unit->harga_unit ? 'Rp ' . number_format($unit->harga_unit, 0, ',', '.') : '-' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="label-tag 
                            @if($unit->status_penjualan == 'Terjual') bg-rust
                            @elseif($unit->status_penjualan == 'Sudah DP') bg-industrial-700
                            @else bg-industrial-300
                            @endif">
                            {{ strtoupper($unit->status_penjualan) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center font-mono font-bold text-rust">
                        {{ $unit->progres_pembangunan }}%
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex gap-2 justify-center">
                            <a href="{{ route('admin.units.edit', $unit->id) }}" 
                               class="text-industrial-900 hover:text-rust font-mono text-xs">
                                [EDIT]
                            </a>
                            <a href="{{ route('production.show', $unit->id) }}" 
                               class="text-industrial-900 hover:text-rust font-mono text-xs">
                                [DETAIL]
                            </a>
                            <form action="{{ route('admin.units.destroy', $unit->id) }}" 
                                  method="POST" class="inline"
                                  onsubmit="return confirm('Yakin hapus unit ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rust hover:text-rust-dark font-mono text-xs">
                                    [HAPUS]
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="px-4 py-8 text-center font-mono text-industrial-600">
                        // Belum ada data unit
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection