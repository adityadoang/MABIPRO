@extends('layouts.admin')

@section('title', 'Unit ' . $unit->unit_number)

@section('content')
<div class="mb-6">
    <a href="{{ route('production.dashboard') }}" class="font-label-md text-sm text-primary hover:text-primary-fixed-dim flex items-center gap-1 transition-colors">
        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
        Back to Dashboard
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-surface-container-low rounded-xl p-6 shadow-sm border border-outline-variant">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <div>
                    <h1 class="font-headline-lg text-2xl font-bold text-on-surface">Unit {{ $unit->unit_number }}</h1>
                    <p class="font-body-md text-sm text-on-surface-variant mt-1">
                        <span class="font-bold text-primary">{{ $unit->block->nama_blok }}</span> • Property Management
                    </p>
                </div>
                <div class="flex gap-2">
                    <span class="bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full font-label-sm text-xs">
                        {{ $unit->status_penjualan }}
                    </span>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-surface-container-high rounded-lg p-4">
                    <p class="font-label-sm text-xs text-on-surface-variant mb-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">sell</span>
                        Sales Status
                    </p>
                    <p class="font-headline-sm text-lg font-bold text-on-surface">{{ $unit->status_penjualan }}</p>
                </div>
                <div class="bg-primary rounded-lg p-4 text-on-primary shadow-sm relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="font-label-sm text-xs text-primary-container mb-1 flex items-center gap-1">
                            <span class="material-symbols-outlined text-[14px]">trending_up</span>
                            Total Progress
                        </p>
                        <p class="font-headline-sm text-lg font-bold">{{ $unit->progres_pembangunan }}%</p>
                    </div>
                    <div class="absolute right-0 bottom-0 opacity-20 transform translate-x-4 translate-y-4">
                        <span class="material-symbols-outlined text-[80px]">construction</span>
                    </div>
                </div>
                <div class="bg-surface-container-high rounded-lg p-4">
                    <p class="font-label-sm text-xs text-on-surface-variant mb-1 flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">gavel</span>
                        Legal Status
                    </p>
                    <p class="font-headline-sm text-lg font-bold text-on-surface">{{ $unit->status_legalitas ?? 'Belum Lengkap' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-low rounded-xl border border-outline-variant p-6 shadow-sm">
            <h2 class="font-headline-md text-lg font-bold text-on-surface mb-4">Progress Chart</h2>
            <div class="h-64">
                <canvas id="progressChart"></canvas>
            </div>
        </div>

        <div class="bg-surface-container-low rounded-xl border border-outline-variant p-6 shadow-sm">
            <h2 class="font-headline-md text-lg font-bold text-on-surface mb-4">Construction Log</h2>
            <div class="space-y-4">
                @php
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
                    <div class="border-l-4 border-primary pl-4 py-2 hover:bg-surface-container-high transition-colors rounded-r-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-headline-sm font-semibold text-on-surface">{{ $progress->tahap }}</h3>
                                <p class="font-body-md text-sm text-on-surface-variant mt-1">{{ $progress->catatan }}</p>
                                <p class="font-body-sm text-xs text-on-surface-variant mt-2 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">person</span> {{ $progress->updater->name ?? 'Unknown' }} 
                                    <span class="mx-1">•</span> 
                                    <span class="material-symbols-outlined text-[12px]">calendar_today</span> {{ $progress->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                            <span class="font-headline-md text-2xl font-bold text-primary">{{ $progress->persentase }}%</span>
                        </div>
                        @if($progress->photos->count() > 0)
                            <div class="flex gap-2 mt-3 overflow-x-auto pb-2">
                                @foreach($progress->photos as $photo)
                                    <img src="{{ asset('storage/' . $photo->file_path) }}" class="w-20 h-20 object-cover rounded-lg border border-outline-variant hover:opacity-80 transition-opacity cursor-pointer">
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="bg-surface-container-low rounded-xl border border-outline-variant p-5 shadow-sm">
            <h3 class="font-headline-md font-semibold text-on-surface mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">bolt</span>
                Quick Actions
            </h3>
            <div class="space-y-3">
                <a href="{{ route('production.edit', $unit->id) }}" class="block w-full py-2.5 bg-primary text-on-primary rounded-lg font-label-md text-sm text-center hover:bg-on-primary-fixed-variant transition-colors shadow-sm flex justify-center items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                    Update Progress
                </a>
                <a href="{{ route('production.report', $unit->id) }}" class="block w-full py-2.5 bg-surface border border-outline-variant text-on-surface rounded-lg font-label-md text-sm text-center hover:bg-surface-container-high transition-colors flex justify-center items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                    Generate PDF Report
                </a>
            </div>
        </div>

        <div class="bg-surface-container-low rounded-xl border border-outline-variant p-5 shadow-sm">
            <h3 class="font-headline-md font-semibold text-on-surface mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">history</span>
                Activity Timeline
            </h3>
            <div class="space-y-4">
                @foreach($progressHistory->take(5) as $activity)
                    <div class="flex gap-3">
                        <div class="w-2 h-2 rounded-full bg-secondary mt-2 flex-shrink-0"></div>
                        <div class="flex-1">
                            <p class="font-body-sm text-xs text-on-surface-variant">{{ $activity->created_at->diffForHumans() }}</p>
                            <p class="font-body-md text-sm text-on-surface font-medium">{{ $activity->tahap }}</p>
                            <p class="font-label-sm text-xs text-primary font-bold mt-1">{{ $activity->persentase }}%</p>
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