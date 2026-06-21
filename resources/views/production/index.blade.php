@extends('layouts.admin')

@section('title', 'Production Dashboard')

@section('content')
<div class="mb-lg">
    <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
        <div>
            <h1 class="font-headline-lg text-headline-lg font-bold text-primary">Production Dashboard</h1>
            <p class="font-body-md text-body-md text-on-surface-variant mt-1">Monitor construction health and submit field updates.</p>
        </div>
        <div class="flex gap-3">
            <button class="bg-surface-container-high hover:bg-surface-container-highest text-on-surface font-label-md px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <span class="material-symbols-outlined text-[18px]">filter_list</span>
                Filter
            </button>
            <a href="{{ route('production.edit', 1) }}" class="bg-secondary hover:bg-on-secondary-fixed-variant text-on-secondary font-label-md px-4 py-2 rounded-lg flex items-center gap-2 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-[18px]">add</span>
                New Update
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 space-y-6">
        <div>
            <h2 class="font-headline-md text-lg font-bold text-on-surface mb-4">Block Construction Health</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach($blocks as $block)
                    @php
                        $avgProgress = $block->units->avg('progres_pembangunan') ?? 0;
                        $isDelayed = $avgProgress < 50;
                        $statusLabel = $isDelayed ? 'Delayed' : 'On Track';
                        $statusColor = $isDelayed ? 'bg-error-container text-on-error-container' : 'bg-secondary-container text-on-secondary-container';
                        $borderColor = $isDelayed ? 'border-l-error' : 'border-l-secondary';
                    @endphp
                    <div class="bg-surface-container-low rounded-xl border-l-4 {{ $borderColor }} p-5 shadow-sm transition-all hover:shadow-md">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="font-headline-md text-base font-bold text-on-surface">{{ $block->nama_blok }}</h3>
                                <p class="font-body-sm text-sm text-on-surface-variant">{{ $block->units->count() }} Units Total</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-md font-label-sm text-xs {{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                        
                        <div class="space-y-3 mt-4">
                            @php
                                $displayTahaps = ['Foundation', 'Framing', 'Finishing'];
                                $tahapMapping = [
                                    'Foundation' => ['Persiapan Lahan', 'Pondasi'],
                                    'Framing' => ['Struktur & Dinding', 'Pengecatan'],
                                    'Finishing' => ['Finishing', 'Serah Terima']
                                ];
                            @endphp
                            @foreach($displayTahaps as $displayTahap)
                                @php
                                    $mappedTahaps = $tahapMapping[$displayTahap];
                                    $progress = $block->units->flatMap->constructionProgress
                                        ->filter(fn($p) => in_array($p->tahap, $mappedTahaps))
                                        ->max('persentase') ?? 0;
                                    $barColor = $progress === 100 ? 'bg-secondary' : ($progress < 50 ? 'bg-error' : 'bg-primary');
                                    $textColor = $progress < 50 ? 'text-error' : 'text-on-surface';
                                @endphp
                                <div>
                                    <div class="flex justify-between text-xs mb-1 font-body-sm">
                                        <span class="text-on-surface-variant">{{ $displayTahap }}</span>
                                        <span class="font-bold {{ $textColor }}">{{ $progress }}%</span>
                                    </div>
                                    <div class="w-full bg-surface-container-highest rounded-full h-2 overflow-hidden">
                                        <div class="{{ $barColor }} h-full rounded-full transition-all duration-500" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-surface-container-low rounded-xl p-6 shadow-sm">
            <h2 class="font-headline-md text-lg font-bold text-on-surface mb-5">Submit Progress Update</h2>
            
            <form action="{{ route('production.update', 1) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-label-sm text-sm text-on-surface mb-2">Select Block</label>
                        <select name="block_id" id="blockSelect" class="w-full bg-surface border border-outline-variant text-on-surface rounded-lg px-3 py-2.5 font-body-md focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                            @foreach($blocks as $block)
                                <option value="{{ $block->id }}">{{ $block->nama_blok }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-label-sm text-sm text-on-surface mb-2">Select Unit</label>
                        <select name="unit_id" id="unitSelect" class="w-full bg-surface border border-outline-variant text-on-surface rounded-lg px-3 py-2.5 font-body-md focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all">
                            @if($blocks->first() && $blocks->first()->units)
                                @foreach($blocks->first()->units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->unit_number }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block font-label-sm text-sm text-on-surface mb-3">Construction Phase Progress</label>
                    <div class="grid grid-cols-5 gap-2">
                        @foreach([0, 25, 50, 75, 100] as $pct)
                            <label class="cursor-pointer">
                                <input type="radio" name="persentase" value="{{ $pct }}" class="peer sr-only" {{ $pct == 0 ? 'checked' : '' }}>
                                <div class="bg-surface border border-outline-variant rounded-lg py-3 text-center font-label-md text-sm text-on-surface peer-checked:bg-primary peer-checked:text-on-primary peer-checked:border-primary hover:bg-surface-container-high transition-all">
                                    {{ $pct }}%
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <div>
                    <label class="block font-label-sm text-sm text-on-surface mb-2">Catatan</label>
                    <textarea name="catatan" rows="2" class="w-full bg-surface border border-outline-variant text-on-surface rounded-lg px-3 py-2.5 font-body-md focus:ring-2 focus:ring-primary focus:border-primary outline-none transition-all" placeholder="Masukkan catatan progres..."></textarea>
                </div>
                
                <div>
                    <label class="block font-label-sm text-sm text-on-surface mb-2">Photo Evidence</label>
                    <div class="bg-surface border-2 border-dashed border-outline-variant rounded-lg p-8 text-center cursor-pointer hover:bg-surface-container-high transition-colors" onclick="document.getElementById('foto').click()">
                        <input type="file" name="foto" id="foto" accept="image/*" class="hidden">
                        <span class="material-symbols-outlined text-[40px] text-on-surface-variant mb-2">cloud_upload</span>
                        <p class="font-label-md text-sm text-on-surface">Drag and drop or click to upload</p>
                        <p class="font-body-sm text-xs text-error mt-1">Batas unggahan maksimal 5MB (format PNG/JPEG)</p>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 pt-4 border-t border-outline-variant">
                    <button type="button" class="bg-surface-container-high hover:bg-surface-container-highest text-on-surface font-label-md px-5 py-2.5 rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="bg-primary hover:bg-on-primary-fixed-variant text-on-primary font-label-md px-5 py-2.5 rounded-lg shadow-sm transition-colors">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="lg:col-span-1">
        <div class="bg-surface-container-low rounded-xl p-5 shadow-sm sticky top-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-headline-md text-lg font-bold text-on-surface">Recent Activity</h2>
                <span class="material-symbols-outlined text-on-surface-variant">schedule</span>
            </div>
            
            <div class="space-y-4">
                @php
                    $recentProgress = \App\Models\ConstructionProgress::with(['unit', 'updater'])
                        ->orderBy('created_at', 'desc')
                        ->take(5)
                        ->get();
                @endphp
                
                @forelse($recentProgress as $activity)
                    <div class="flex gap-3">
                        <div class="w-2 h-2 rounded-full bg-secondary mt-2 flex-shrink-0"></div>
                        <div class="flex-1">
                            <p class="font-body-sm text-xs text-on-surface-variant mb-1">{{ $activity->created_at->diffForHumans() }}</p>
                            <p class="font-body-sm text-sm text-on-surface">
                                <span class="font-bold">{{ $activity->updater->name ?? 'System' }}</span>
                                updated <span class="font-bold text-primary">{{ $activity->unit->unit_number ?? 'Unit' }}</span>
                            </p>
                            <p class="font-label-sm text-xs text-on-secondary-container mt-1 bg-secondary-container rounded px-2 py-1 inline-block">
                                {{ $activity->tahap }}: {{ $activity->persentase }}%
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="font-body-sm text-sm text-on-surface-variant text-center py-4">No recent activity</p>
                @endforelse
            </div>
            
            <button class="w-full mt-6 bg-surface-container-high hover:bg-surface-container-highest text-on-surface font-label-md py-2.5 rounded-lg transition-colors border border-outline-variant">
                View All Activity
            </button>
        </div>
    </div>
</div>

<script>
// Dynamic unit selection based on block
document.getElementById('blockSelect').addEventListener('change', function() {
    const blockId = this.value;
    const unitSelect = document.getElementById('unitSelect');
    
    // Fetch units for selected block
    fetch(`/api/blocks/${blockId}/units`)
        .then(response => response.json())
        .then(units => {
            unitSelect.innerHTML = '';
            units.forEach(unit => {
                const option = document.createElement('option');
                option.value = unit.id;
                option.textContent = unit.unit_number;
                unitSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching units:', error));
});
</script>
@endsection