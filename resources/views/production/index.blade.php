@extends('layouts.production')

@section('title', 'Production Dashboard')

@section('content')
<style>
.bg-primary { background-color: #10B981 !important; }
.bg-primary-dark { background-color: #059669 !important; }
.bg-primary-container { background-color: #D1FAE5 !important; }
.text-primary { color: #10B981 !important; }
.text-on-primary { color: white !important; }
.text-on-primary-container { color: #065F46 !important; }
.border-primary { border-color: #10B981 !important; }
.hover\:bg-primary-dark:hover { background-color: #059669 !important; }
.progress-fill { background-color: #10B981; transition: width 0.5s ease; }
.status-on-track { background-color: #D1FAE5; color: #065F46; }
.status-delayed { background-color: #FEE2E2; color: #991B1B; }
</style>

<div class="mb-6">
    <h1 class="font-headline-lg text-2xl font-bold text-on-surface">Production Dashboard</h1>
    <p class="font-body-md text-sm text-on-surface-variant mt-1">Monitor construction health and submit field updates.</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Block Construction Health --}}
    <div class="lg:col-span-2 space-y-6">
        <div class="bg-surface-container-low rounded-xl border border-outline-variant p-6 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-headline-md text-lg font-bold text-on-surface">Block Construction Health</h2>
                <span class="font-label-sm text-xs text-on-surface-variant">
                    {{ $blocks->sum(fn($b) => $b->units->count()) }} Total Units
                </span>
            </div>
            
            <div class="space-y-6">
                @foreach($blocks as $block)
                    @php
                        $totalUnits = $block->units->count();
                        $avgProgress = $block->units->avg('progres_pembangunan') ?? 0;
                        $isDelayed = $avgProgress < 50;
                        $borderColor = $isDelayed ? 'border-red-500' : 'border-primary';
                        $statusColor = $isDelayed ? 'bg-red-100 text-red-700' : 'bg-primary-container text-on-primary-container';
                        $statusText = $isDelayed ? 'Delayed' : 'On Track';
                        $overallProgress = round($avgProgress);
                    @endphp
                    
                    <div class="bg-surface-container-high rounded-xl border-l-4 {{ $borderColor }} p-5 shadow-sm">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg {{ $isDelayed ? 'bg-red-100' : 'bg-primary-container' }} flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[20px] {{ $isDelayed ? 'text-red-600' : 'text-primary' }}">
                                        {{ $isDelayed ? 'warning' : 'check_circle' }}
                                    </span>
                                </div>
                                <div>
                                    <h3 class="font-headline-sm font-bold text-on-surface">{{ $block->nama_blok }}</h3>
                                    <p class="font-body-sm text-xs text-on-surface-variant">{{ $totalUnits }} Unit{{ $totalUnits != 1 ? 's' : '' }} Total</p>
                                </div>
                            </div>
                            <span class="{{ $statusColor }} px-3 py-1 rounded-full font-label-sm text-xs font-semibold">
                                {{ $statusText }}
                            </span>
                        </div>
                        
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-label-sm text-sm font-semibold text-on-surface">Overall Progress</span>
                                <span class="font-headline-md text-xl font-bold {{ $overallProgress > 0 ? 'text-primary' : 'text-red-500' }}">
                                    {{ $overallProgress }}%
                                </span>
                            </div>
                            <div class="w-full bg-surface-container rounded-full h-3">
                                <div class="{{ $overallProgress > 0 ? 'bg-primary' : 'bg-red-400' }} h-3 rounded-full transition-all" 
                                     style="width: {{ $overallProgress }}%"></div>
                            </div>
                        </div>
                        
                        <div class="border-t border-outline-variant pt-4">
                            <p class="font-label-sm text-xs font-semibold text-on-surface-variant mb-3">Units in {{ $block->nama_blok }}:</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @forelse($block->units as $unit)
                                    <a href="{{ route('production.show', $unit->id) }}" 
                                       class="flex items-center justify-between p-3 bg-white rounded-lg border border-outline-variant hover:border-primary hover:shadow-md transition-all group">
                                        <div class="flex items-center gap-2">
                                            <span class="material-symbols-outlined text-[18px] text-primary group-hover:text-primary-dark">home</span>
                                            <div>
                                                <p class="font-label-md text-sm font-semibold text-on-surface">{{ $unit->unit_number }}</p>
                                                <p class="font-body-sm text-xs text-on-surface-variant">{{ $unit->status_penjualan ?? 'Available' }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-label-sm text-xs font-bold {{ $unit->progres_pembangunan > 0 ? 'text-primary' : 'text-red-500' }}">
                                                {{ $unit->progres_pembangunan }}%
                                            </p>
                                        </div>
                                    </a>
                                @empty
                                    <p class="text-xs text-on-surface-variant italic col-span-2">No units in this block</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="space-y-6">
        <div class="bg-surface-container-low rounded-xl border border-outline-variant p-5 shadow-sm sticky top-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-headline-md font-semibold text-on-surface">Recent Activity</h2>
                <span class="material-symbols-outlined text-on-surface-variant">schedule</span>
            </div>
            
            <div class="space-y-4">
                @php
                    $recentProgress = \App\Models\ConstructionProgress::with(['unit.block', 'updater'])
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                @endphp
                
                @forelse($recentProgress as $activity)
                    <div class="flex gap-3">
                        <div class="w-2 h-2 rounded-full bg-primary mt-2 flex-shrink-0"></div>
                        <div class="flex-1">
                            <p class="font-body-sm text-xs text-on-surface-variant">{{ $activity->created_at->diffForHumans() }}</p>
                            <p class="font-body-md text-sm text-on-surface">
                                <span class="font-semibold">{{ $activity->updater->name ?? 'System' }}</span>
                                updated 
                                <a href="{{ route('production.show', $activity->unit_id) }}" class="font-semibold text-primary hover:underline">
                                    {{ $activity->unit->unit_number ?? 'Unit' }}
                                </a>
                                <span class="text-xs text-on-surface-variant">({{ $activity->unit->block->nama_blok ?? '' }})</span>
                            </p>
                            <p class="font-label-sm text-xs bg-primary-container text-on-primary-container rounded px-2 py-1 inline-block mt-1 font-semibold">
                                {{ $activity->tahap }}: {{ $activity->persentase }}%
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="font-body-md text-sm text-on-surface-variant text-center py-4">No recent activity</p>
                @endforelse
            </div>
            
            <a href="#" onclick="window.location.reload(); return false;" class="block w-full mt-4 py-2.5 bg-primary text-on-primary rounded-lg font-label-md text-sm text-center hover:bg-primary-dark transition-colors font-semibold">
                View All Activity
            </a>
        </div>
    </div>
</div>
@endsection