@extends('layouts.production')

@section('title', 'Dashboard Produksi')

@section('content')
<div class="mb-8">
    <div class="flex items-center gap-3 mb-2">
        <span class="label-tag">MODULE</span>
        <span class="label-tag bg-rust">DASHBOARD</span>
    </div>
    <h2 class="font-display text-4xl font-bold text-industrial-900 tracking-wide">
        PRODUCTION OVERVIEW
    </h2>
    <p class="font-mono text-sm text-industrial-700 mt-2">
        // Monitoring progres pembangunan seluruh unit
    </p>
</div>

@if(session('success'))
    <div class="bg-rust text-industrial-50 px-4 py-3 mb-6 industrial-border-sm font-mono text-sm">
        <span class="font-bold">[OK]</span> {{ session('success') }}
    </div>
@endif

<!-- Summary Stats -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
    @php
        $totalUnits = $blocks->sum(fn($b) => $b->units->count());
        $avgProgress = $totalUnits > 0 
            ? round($blocks->sum(fn($b) => $b->units->sum('progres_pembangunan')) / $totalUnits) 
            : 0;
        $terjual = $blocks->sum(fn($b) => $b->units->where('status_penjualan', 'Terjual')->count());
    @endphp
    
    <div class="card-industrial p-4 relative">
        <span class="corner-rivet" style="top: 4px; left: 4px;"></span>
        <span class="corner-rivet" style="top: 4px; right: 4px;"></span>
        <span class="corner-rivet" style="bottom: 4px; left: 4px;"></span>
        <span class="corner-rivet" style="bottom: 4px; right: 4px;"></span>
        <p class="font-mono text-xs text-industrial-700 tracking-wider">TOTAL BLOK</p>
        <p class="font-display text-4xl font-bold text-industrial-900">{{ $blocks->count() }}</p>
    </div>
    
    <div class="card-industrial p-4 relative">
        <span class="corner-rivet" style="top: 4px; left: 4px;"></span>
        <span class="corner-rivet" style="top: 4px; right: 4px;"></span>
        <span class="corner-rivet" style="bottom: 4px; left: 4px;"></span>
        <span class="corner-rivet" style="bottom: 4px; right: 4px;"></span>
        <p class="font-mono text-xs text-industrial-700 tracking-wider">TOTAL UNIT</p>
        <p class="font-display text-4xl font-bold text-industrial-900">{{ $totalUnits }}</p>
    </div>
    
    <div class="card-industrial p-4 relative">
        <span class="corner-rivet" style="top: 4px; left: 4px;"></span>
        <span class="corner-rivet" style="top: 4px; right: 4px;"></span>
        <span class="corner-rivet" style="bottom: 4px; left: 4px;"></span>
        <span class="corner-rivet" style="bottom: 4px; right: 4px;"></span>
        <p class="font-mono text-xs text-industrial-700 tracking-wider">TERJUAL</p>
        <p class="font-display text-4xl font-bold text-rust">{{ $terjual }}</p>
    </div>
    
    <div class="card-industrial p-4 relative">
        <span class="corner-rivet" style="top: 4px; left: 4px;"></span>
        <span class="corner-rivet" style="top: 4px; right: 4px;"></span>
        <span class="corner-rivet" style="bottom: 4px; left: 4px;"></span>
        <span class="corner-rivet" style="bottom: 4px; right: 4px;"></span>
        <p class="font-mono text-xs text-industrial-700 tracking-wider">AVG PROGRESS</p>
        <p class="font-display text-4xl font-bold text-industrial-900">{{ $avgProgress }}%</p>
    </div>
</div>

<!-- Blok Cards -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    @foreach($blocks as $block)
        <div class="card-industrial relative">
            <!-- Header Blok -->
            <div class="bg-industrial-900 text-industrial-50 px-4 py-3 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="rivet"></span>
                    <h3 class="font-display text-xl font-bold tracking-wider">{{ strtoupper($block->nama_blok) }}</h3>
                </div>
                <span class="label-tag bg-rust">{{ $block->units->count() }} UNIT</span>
            </div>
            
            <!-- Body -->
            <div class="p-4 space-y-3">
                @forelse($block->units as $unit)
                    <a href="{{ route('production.show', $unit->id) }}" 
                       class="block bg-industrial-50 border-2 border-industrial-700 p-3 hover:bg-industrial-100 transition group">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-display text-lg font-bold text-industrial-900 tracking-wide">
                                UNIT {{ $unit->unit_number }}
                            </span>
                            <span class="font-mono text-xs px-2 py-1 border-2 border-industrial-900
                                @if($unit->status_penjualan == 'Terjual') bg-rust text-industrial-50 border-rust-dark
                                @elseif($unit->status_penjualan == 'Sudah DP') bg-industrial-700 text-industrial-50
                                @else bg-industrial-100 text-industrial-900
                                @endif">
                                {{ strtoupper($unit->status_penjualan) }}
                            </span>
                        </div>
                        
                        <!-- Progress Bar Industrial -->
                        <div class="progress-bar-industrial">
                            <div class="fill" style="width: {{ $unit->progres_pembangunan }}%"></div>
                        </div>
                        
                        <div class="flex justify-between items-center mt-2 font-mono text-xs text-industrial-700">
                            <span>PROGRESS: {{ $unit->progres_pembangunan }}%</span>
                            <span class="group-hover:text-rust transition">→ DETAIL</span>
                        </div>
                    </a>
                @empty
                    <p class="font-mono text-sm text-industrial-600 italic">
                        // Belum ada unit di blok ini
                    </p>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
@endsection