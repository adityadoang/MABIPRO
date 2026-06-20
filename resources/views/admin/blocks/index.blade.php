@extends('layouts.admin')

@section('title', 'Data Blok')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <span class="label-tag bg-rust">MASTER DATA</span>
        <h2 class="font-display text-3xl font-bold text-industrial-900 mt-2">DATA BLOK</h2>
    </div>
    <a href="{{ route('admin.blocks.create') }}" 
       class="btn-industrial bg-rust text-industrial-50 px-4 py-2">
        + TAMBAH BLOK
    </a>
</div>

<div class="card-industrial">
    <table class="w-full">
        <thead>
            <tr class="bg-industrial-900 text-industrial-50">
                <th class="px-4 py-3 text-left font-display tracking-wider">ID</th>
                <th class="px-4 py-3 text-left font-display tracking-wider">NAMA BLOK</th>
                <th class="px-4 py-3 text-left font-display tracking-wider">DESKRIPSI</th>
                <th class="px-4 py-3 text-center font-display tracking-wider">JUMLAH UNIT</th>
                <th class="px-4 py-3 text-center font-display tracking-wider">AKSI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($blocks as $block)
                <tr class="border-t-2 border-industrial-700 hover:bg-industrial-100">
                    <td class="px-4 py-3 font-mono text-sm">{{ $block->id }}</td>
                    <td class="px-4 py-3 font-display font-bold text-industrial-900">
                        {{ strtoupper($block->nama_blok) }}
                    </td>
                    <td class="px-4 py-3 font-mono text-sm text-industrial-700">
                        {{ $block->deskripsi ?? '-' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="label-tag bg-rust">{{ $block->units_count }}</span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex gap-2 justify-center">
                            <a href="{{ route('admin.blocks.edit', $block->id) }}" 
                               class="text-industrial-900 hover:text-rust font-mono text-xs">
                                [EDIT]
                            </a>
                            <form action="{{ route('admin.blocks.destroy', $block->id) }}" 
                                  method="POST" class="inline"
                                  onsubmit="return confirm('Yakin hapus blok ini? Semua unit di dalamnya juga akan terhapus!')">
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
                    <td colspan="5" class="px-4 py-8 text-center font-mono text-industrial-600">
                        // Belum ada data blok
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection