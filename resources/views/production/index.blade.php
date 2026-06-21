@extends('layouts.production')

@section('title', 'Production Dashboard')

@section('content')
<div class="flex justify-between items-start mb-8">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Production Dashboard</h1>
        <p class="text-sm text-gray-500 mt-1">Monitor construction health and submit field updates.</p>
    </div>
    <div class="flex gap-3">
        <button class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
            Filter
        </button>
        <a href="{{ route('production.edit', 1) }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Update
        </a>
    </div>
</div>

<div class="grid grid-cols-3 gap-6">
    <div class="col-span-2 space-y-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Block Construction Health</h2>
            <div class="grid grid-cols-2 gap-4">
                @foreach($blocks as $block)
                    @php
                        $avgProgress = $block->units->avg('progres_pembangunan') ?? 0;
                        $isDelayed = $avgProgress < 50;
                        $statusLabel = $isDelayed ? 'Delayed' : 'On Track';
                        $statusColor = $isDelayed ? 'bg-red-100 text-red-700' : 'bg-primary-100 text-primary-700';
                        $borderColor = $isDelayed ? 'border-l-red-500' : 'border-l-primary-500';
                    @endphp
                    <div class="bg-white rounded-xl border border-gray-200 border-l-4 {{ $borderColor }} p-5 shadow-sm">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">{{ $block->nama_blok }}</h3>
                                <p class="text-xs text-gray-500">{{ $block->units->count() }} Units Total</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-md text-xs font-medium {{ $statusColor }}">
                                {{ $statusLabel }}
                            </span>
                        </div>
                        
                        <div class="space-y-3">
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
                                    $barColor = $progress === 100 ? 'bg-primary-500' : ($progress < 50 ? 'bg-red-500' : 'bg-gray-900');
                                    $textColor = $progress < 50 ? 'text-red-600' : 'text-gray-900';
                                @endphp
                                <div>
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="text-gray-700">{{ $displayTahap }}</span>
                                        <span class="font-medium {{ $textColor }}">{{ $progress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="progress-bar {{ $barColor }} h-2 rounded-full" style="width: {{ $progress }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 mb-5">Submit Progress Update</h2>
            
            <form action="{{ route('production.update', 1) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Block</label>
                        <select name="block_id" id="blockSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            @foreach($blocks as $block)
                                <option value="{{ $block->id }}">{{ $block->nama_blok }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Unit</label>
                        <select name="unit_id" id="unitSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            @if($blocks->first() && $blocks->first()->units)
                                @foreach($blocks->first()->units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->unit_number }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">Construction Phase Progress</label>
                    <div class="grid grid-cols-5 gap-3">
                        @foreach([0, 25, 50, 75, 100] as $pct)
                            <label class="cursor-pointer">
                                <input type="radio" name="persentase" value="{{ $pct }}" class="peer sr-only" {{ $pct == 0 ? 'checked' : '' }}>
                                <div class="border border-gray-300 rounded-lg py-3 text-center text-sm font-medium text-gray-700 peer-checked:bg-gray-900 peer-checked:text-white peer-checked:border-gray-900 hover:bg-gray-50 transition">
                                    {{ $pct }}%
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="catatan" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="Masukkan catatan progres..."></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Photo Evidence</label>
                    <div class="drop-zone rounded-lg p-8 text-center cursor-pointer" onclick="document.getElementById('foto').click()">
                        <input type="file" name="foto" id="foto" accept="image/*" class="hidden">
                        <svg class="w-10 h-10 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <p class="text-sm text-gray-700 font-medium">Drag and drop or click to upload</p>
                        <p class="text-xs text-red-500 mt-1">Batas unggahan maksimal 5MB (format PNG/JPEG)</p>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <button type="button" class="px-5 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700">
                        Submit Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div>
        <div class="bg-white rounded-xl border border-gray-200 p-5 shadow-sm sticky top-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Recent Activity</h2>
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
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
                        <div class="w-2 h-2 rounded-full bg-primary-500 mt-2 flex-shrink-0"></div>
                        <div class="flex-1">
                            <p class="text-xs text-gray-500 mb-1">{{ $activity->created_at->diffForHumans() }}</p>
                            <p class="text-sm text-gray-900">
                                <span class="font-semibold">{{ $activity->updater->name ?? 'System' }}</span>
                                updated <span class="font-semibold">{{ $activity->unit->unit_number ?? 'Unit' }}</span>
                            </p>
                            <p class="text-xs text-gray-600 mt-1 bg-gray-50 rounded px-2 py-1 inline-block">
                                {{ $activity->tahap }}: {{ $activity->persentase }}%
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500 text-center py-4">No recent activity</p>
                @endforelse
            </div>
            
            <button class="w-full mt-4 py-2.5 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
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