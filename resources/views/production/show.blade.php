@extends('layouts.production')

@section('title', 'Unit ' . $unit->unit_number)

@section('content')
<div class="mb-6">
    <a href="{{ route('production.index') }}" class="text-sm text-primary-600 hover:text-primary-700 flex items-center gap-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Dashboard
    </a>
</div>

<div class="grid grid-cols-3 gap-6">
    <div class="col-span-2 space-y-6">
        <div class="unit-header mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Unit {{ $unit->unit_number }}</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $unit->block->nama_blok }} • Property Management</p>
                </div>
            </div>
            
            <div class="grid grid-cols-3 gap-4">
                <div class="status-card">
                    <p class="text-xs text-gray-500 mb-1">Sales Status</p>
                    <p class="text-lg font-bold text-gray-900">{{ $unit->status_penjualan }}</p>
                </div>
                <div class="status-card primary">
                    <p class="text-xs text-gray-500 mb-1">Total Progress</p>
                    <p class="text-lg font-bold text-primary-700">{{ $unit->progres_pembangunan }}%</p>
                </div>
                <div class="status-card">
                    <p class="text-xs text-gray-500 mb-1">Legal Status</p>
                    <p class="text-lg font-bold text-gray-900">{{ $unit->status_legalitas ?? 'Belum Lengkap' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Progress Chart</h2>
            <div class="h-64">
                <canvas id="progressChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Construction Log</h2>
            <div class="space-y-4">
                @php
                    // Urutkan berdasarkan urutan tahap yang benar
                    $tahapOrder = [
                        'Persiapan Lahan' => 1,
                        'Pondasi' => 2,
                        'Struktur & Dinding' => 3,
                        'Pengecatan' => 4,
                        'Finishing' => 5,
                        'Serah Terima' => 6,
                    ];
                    
                    $sortedHistory = $progressHistory->sortBy(function($progress) use ($tahapOrder) {
                        return $tahapOrder[$progress->tahap] ?? 99;
                    });
                @endphp
                
                @foreach($sortedHistory as $progress)
                    <div class="border-l-4 border-primary-500 pl-4 py-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-gray-900">{{ $progress->tahap }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $progress->catatan }}</p>
                                <p class="text-xs text-gray-500 mt-2">
                                    By: {{ $progress->updater->name ?? 'Unknown' }} • {{ $progress->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                            <span class="text-2xl font-bold text-primary-600">{{ $progress->persentase }}%</span>
                        </div>
                        @if($progress->photos->count() > 0)
                            <div class="flex gap-2 mt-3">
                                @foreach($progress->photos as $photo)
                                    <img src="{{ asset('storage/' . $photo->file_path) }}" class="w-20 h-20 object-cover rounded-lg border border-gray-200">
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-3">Quick Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('production.edit', $unit->id) }}" class="block w-full py-2.5 bg-primary-600 text-white rounded-lg text-sm font-medium text-center hover:bg-primary-700">
                    Update Progress
                </a>
                <a href="{{ route('production.report', $unit->id) }}" class="block w-full py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium text-center hover:bg-gray-50">
                    Generate PDF Report
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm">
            <h3 class="font-semibold text-gray-900 mb-3">Activity Log</h3>
            <div class="space-y-3">
                @foreach($progressHistory->take(5) as $activity)
                    <div class="flex gap-3">
                        <div class="w-2 h-2 rounded-full bg-primary-50 mt-2 flex-shrink-0"></div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                            <p class="text-sm text-gray-900">{{ $activity->tahap }}</p>
                            <p class="text-xs text-primary-600 font-medium">{{ $activity->persentase }}%</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('progressChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode($chartDataFormatted->pluck('tahap')) !!},
            datasets: [{
                label: 'Progress per Tahap (%)',
                data: {!! json_encode($chartDataFormatted->pluck('persentase')) !!},
                backgroundColor: [
                    '#047857',
                    '#059669',
                    '#10B981',
                    '#34D399',
                    '#6EE7B7',
                    '#A7F3D0'
                ],
                borderColor: '#065F46',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: true,
                    position: 'top',
                    labels: { 
                        font: { family: 'Inter', size: 12 },
                        color: '#374151'
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    grid: { color: '#F3F4F6' },
                    ticks: { 
                        font: { family: 'Inter', size: 11 },
                        callback: function(value) { return value + '%'; }
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'Inter', size: 11 } }
                }
            }
        }
    });
</script>
@endsection