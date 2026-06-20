@extends('layouts.production')

@section('title', 'Detail Unit ' . $unit->unit_number)

@section('content')
<div class="mb-6">
    <a href="{{ route('production.index') }}" 
       class="font-mono text-sm text-rust hover:text-rust-dark inline-flex items-center gap-2">
        ← KEMBALI KE DASHBOARD
    </a>
</div>

<!-- Header Unit -->
<div class="card-industrial mb-6 relative">
    <span class="corner-rivet" style="top: 8px; left: 8px;"></span>
    <span class="corner-rivet" style="top: 8px; right: 8px;"></span>
    <span class="corner-rivet" style="bottom: 8px; left: 8px;"></span>
    <span class="corner-rivet" style="bottom: 8px; right: 8px;"></span>
    
    <div class="bg-industrial-900 text-industrial-50 px-6 py-4 flex justify-between items-center">
        <div>
            <span class="label-tag bg-rust mb-2">UNIT DETAIL</span>
            <h1 class="font-display text-4xl font-bold tracking-wider mt-2">
                UNIT {{ $unit->unit_number }}
            </h1>
            <p class="font-mono text-sm text-industrial-300 mt-1">
                {{ strtoupper($unit->block->nama_blok) }} // MABIPRO
            </p>
        </div>
        <a href="{{ route('production.edit', $unit->id) }}" 
           class="btn-industrial bg-rust text-industrial-50 px-6 py-3 hover:bg-rust-dark">
            + UPDATE PROGRESS
        </a>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 divide-x-2 divide-y-2 md:divide-y-0 divide-industrial-700">
        <div class="p-4">
            <p class="font-mono text-xs text-industrial-700 tracking-wider">STATUS PENJUALAN</p>
            <p class="font-display text-2xl font-bold text-industrial-900 mt-1">
                {{ strtoupper($unit->status_penjualan) }}
            </p>
        </div>
        <div class="p-4">
            <p class="font-mono text-xs text-industrial-700 tracking-wider">TOTAL PROGRESS</p>
            <p class="font-display text-2xl font-bold text-rust mt-1">
                {{ $unit->progres_pembangunan }}%
            </p>
        </div>
        <div class="p-4">
            <p class="font-mono text-xs text-industrial-700 tracking-wider">STATUS LEGALITAS</p>
            <p class="font-display text-2xl font-bold text-industrial-900 mt-1">
                {{ strtoupper($unit->status_legalitas) }}
            </p>
        </div>
    </div>
    
    @if($latestProgress)
        <div class="border-t-2 border-industrial-700 px-6 py-4 bg-industrial-100">
            <div class="flex items-center gap-2 mb-2">
                <span class="label-tag">CURRENT STAGE</span>
            </div>
            <p class="font-display text-xl font-bold text-industrial-900">
                {{ strtoupper($latestProgress->tahap) }}
            </p>
            <p class="font-mono text-sm text-industrial-700 mt-1">
                // {{ $latestProgress->catatan ?? 'Tidak ada catatan' }}
            </p>
        </div>
    @endif
</div>

<!-- Diagram Progress -->
<div class="card-industrial mb-6 relative">
    <div class="bg-industrial-900 text-industrial-50 px-6 py-3 flex items-center gap-2">
        <span class="rivet"></span>
        <h2 class="font-display text-xl font-bold tracking-wider">PROGRESS CHART</h2>
    </div>
    <div class="p-6 bg-industrial-50">
        <canvas id="progressChart" height="80"></canvas>
    </div>
</div>

<!-- Histori Timeline -->
<div class="card-industrial mb-6 relative">
    <div class="bg-industrial-900 text-industrial-50 px-6 py-3 flex items-center gap-2">
        <span class="rivet"></span>
        <h2 class="font-display text-xl font-bold tracking-wider">CONSTRUCTION LOG</h2>
        <span class="label-tag bg-rust ml-auto">{{ $progressHistory->count() }} ENTRIES</span>
    </div>
    
    <div class="p-6 space-y-4">
        @foreach($progressHistory as $progress)
            <div class="timeline-line pl-6 py-2 relative">
                <!-- Dot -->
                <div class="absolute -left-[11px] top-3 w-5 h-5 bg-rust border-2 border-industrial-900 rounded-full"></div>
                
                <div class="bg-industrial-50 border-2 border-industrial-700 p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <span class="label-tag">{{ $progress->tahap }}</span>
                        </div>
                        <span class="font-display text-3xl font-bold text-rust">
                            {{ $progress->persentase }}%
                        </span>
                    </div>
                    
                    <p class="font-mono text-sm text-industrial-800 mt-2">
                        // {{ $progress->catatan ?? 'Tidak ada catatan' }}
                    </p>
                    
                    <div class="flex justify-between items-center mt-3 pt-3 border-t border-industrial-300 font-mono text-xs text-industrial-700">
                        <span>BY: {{ strtoupper($progress->updater->name ?? 'UNKNOWN') }}</span>
                        <span>{{ $progress->created_at->format('d.m.Y // H:i') }}</span>
                    </div>
                    
                    @if($progress->photos->count() > 0)
                        <div class="mt-3 pt-3 border-t border-industrial-300">
                            <p class="font-mono text-xs text-industrial-700 mb-2">DOCUMENTATION:</p>
                            <div class="flex gap-2 flex-wrap">
                                @foreach($progress->photos as $photo)
                                    <img src="{{ asset('storage/' . $photo->file_path) }}" 
                                         alt="Foto progress" 
                                         class="w-24 h-24 object-cover border-2 border-industrial-900 shadow-[3px_3px_0px_#3E2723]">
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Generate Report -->
<div class="card-industrial relative">
    <div class="bg-industrial-900 text-industrial-50 px-6 py-3 flex items-center gap-2">
        <span class="rivet"></span>
        <h2 class="font-display text-xl font-bold tracking-wider">REPORT GENERATOR</h2>
    </div>
    <div class="p-6 flex justify-between items-center bg-industrial-50">
        <div>
            <p class="font-display text-lg font-bold text-industrial-900">GENERATE PDF REPORT</p>
            <p class="font-mono text-xs text-industrial-700 mt-1">
                // Laporan lengkap progres pembangunan
            </p>
        </div>
        <a href="{{ route('production.report', $unit->id) }}" 
           class="btn-industrial bg-industrial-900 text-industrial-50 px-6 py-3 hover:bg-industrial-800">
            📄 GENERATE PDF
        </a>
    </div>
</div>

<script>
    const ctx = document.getElementById('progressChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($progressHistory->pluck('tahap')) !!},
            datasets: [{
                label: 'Progress (%)',
                data: {!! json_encode($progressHistory->pluck('persentase')) !!},
                backgroundColor: '#BF360C',
                borderColor: '#3E2723',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { 
                    labels: { 
                        font: { family: 'Roboto Mono', size: 12 }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: '#BCAAA4' },
                    ticks: { font: { family: 'Roboto Mono' } }
                },
                x: {
                    grid: { color: '#BCAAA4' },
                    ticks: { font: { family: 'Roboto Mono', size: 10 } }
                }
            }
        }
    });
</script>
@endsection