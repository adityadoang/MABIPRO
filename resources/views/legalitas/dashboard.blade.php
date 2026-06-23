@extends('layouts.app')

@section('title', 'Dashboard Legalitas - MABIPRO')

@section('header')
<div class="px-4 md:px-8 pt-6 md:pt-8 pb-4 md:pb-6 bg-white border-b border-gray-200 z-10 shadow-sm flex-shrink-0">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-3">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Legal Compliance</h1>
            <p class="text-gray-500 mt-1 text-sm">Manage unit documents, certificates, and permits.</p>
        </div>
        <div class="flex gap-2 sm:gap-3 w-full sm:w-auto">
            <div class="relative flex-1 sm:flex-none">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </span>
                <input type="text" class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 w-full sm:w-64 bg-gray-50" placeholder="Search units or documents">
            </div>
            <button class="p-2 border border-gray-300 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            </button>
        </div>
    </div>

    @php
        $totalUnits = $units->count();
        $compliant = $units->where('status_legalitas', 'Lengkap')->count();
        $inProgress = $units->filter(function($unit) {
            return $unit->status_legalitas != 'Lengkap' && $unit->legalDocuments->count() > 0;
        })->count();
        $missing = $units->filter(function($unit) {
            return $unit->status_legalitas != 'Lengkap' && $unit->legalDocuments->count() == 0;
        })->count();
        $completionRate = $totalUnits > 0 ? round(($compliant / $totalUnits) * 100) : 0;
    @endphp

    <!-- Stats Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mt-6 md:mt-8">
        <!-- Stat Card 1 -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 card-shadow">
            <div class="flex justify-between items-start">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Units</h3>
                <div class="p-1.5 bg-gray-100 text-gray-500 rounded-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-4xl font-bold text-gray-900">{{ $totalUnits }}</span>
            </div>
            <p class="text-xs text-gray-500 mt-2 font-medium">Across {{ $units->pluck('block_id')->unique()->count() }} housing blocks</p>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 card-shadow">
            <div class="flex justify-between items-start">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Compliant</h3>
                <div class="p-1.5 bg-emerald-100 text-emerald-600 rounded-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-4xl font-bold text-[#047857]">{{ $compliant }}</span>
            </div>
            <p class="text-xs text-[#047857] mt-2 font-semibold">{{ $completionRate }}% Completion rate</p>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white border border-gray-200 rounded-xl p-5 card-shadow">
            <div class="flex justify-between items-start">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">In Progress</h3>
                <div class="p-1.5 bg-amber-100 text-amber-600 rounded-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-4xl font-bold text-amber-600">{{ $inProgress }}</span>
            </div>
            <p class="text-xs text-amber-600 mt-2 font-medium">Partially uploaded</p>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-white border border-red-200 rounded-xl p-5 shadow-sm">
            <div class="flex justify-between items-start">
                <h3 class="text-xs font-bold text-red-600 uppercase tracking-wider">Missing Docs</h3>
                <div class="p-1.5 bg-red-100 text-red-600 rounded-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <div class="mt-2">
                <span class="text-4xl font-bold text-red-600">{{ $missing }}</span>
            </div>
            <p class="text-xs text-red-600 mt-2 font-medium">Requires immediate action</p>
        </div>
    </div>
</div>
@endsection

@section('content')
@php
    $groupedUnits = $units->groupBy(function($unit) {
        return $unit->block ? $unit->block->nama_blok : 'Unknown Block';
    });
@endphp

@forelse($groupedUnits as $blockName => $blockUnits)
@php
    $blockIndex = $loop->index;
    $blockCompliant = $blockUnits->where('status_legalitas', 'Lengkap')->count();
    $blockMissing = $blockUnits->count() - $blockCompliant;
@endphp

{{-- Block Accordion --}}
<div class="mb-4 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }">

    {{-- Accordion Header (clickable) --}}
    <button type="button"
        class="w-full flex items-center gap-3 px-6 py-4 hover:bg-gray-50 transition-colors text-left"
        @click="open = !open">

        {{-- Building icon --}}
        <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600 flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>

        {{-- Block name --}}
        <div class="flex-1 min-w-0">
            <h2 class="text-base font-bold text-gray-800">{{ $blockName }}</h2>
            <p class="text-xs text-gray-500 mt-0.5">{{ $blockUnits->count() }} unit
                &bull; <span class="text-emerald-600 font-semibold">{{ $blockCompliant }} complete</span>
                @if($blockMissing > 0)
                    &bull; <span class="text-red-500 font-semibold">{{ $blockMissing }} missing docs</span>
                @endif
            </p>
        </div>

        {{-- Badges --}}
        <div class="flex items-center gap-2 flex-shrink-0">
            @if($blockMissing > 0)
                <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wide">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    {{ $blockMissing }} Missing
                </span>
            @else
                <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase tracking-wide">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    All Complete
                </span>
            @endif

            {{-- Chevron --}}
            <svg class="w-5 h-5 text-gray-400 transition-transform duration-200 flex-shrink-0"
                :class="open ? 'rotate-180' : ''"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </button>

    {{-- Accordion Body --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="border-t border-gray-100">

        <div class="p-4 md:p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
            @foreach($blockUnits as $unit)
                @php
                    $isComplete = $unit->status_legalitas == 'Lengkap';
                    $borderColor = $isComplete ? 'border-gray-200' : 'border-red-500';
                    $borderLeft = $isComplete ? '' : 'border-l-4';
                @endphp

                <div class="bg-white rounded-xl shadow-sm border {{ $borderColor }} {{ $borderLeft }} flex flex-col overflow-hidden">

                    {{-- Card Header --}}
                    <div class="p-5 bg-gray-50/50 border-b border-gray-100 flex-shrink-0">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-gray-900">Unit {{ $unit->unit_number }}</h3>
                            @if($isComplete)
                                <span class="inline-flex items-center gap-1 bg-emerald-100 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wide">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    Complete
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 text-[10px] font-bold px-2 py-0.5 rounded uppercase tracking-wide">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Missing
                                </span>
                            @endif
                        </div>
                        <div class="text-xs text-gray-500 space-y-0.5">
                            <p>Type: <span class="text-gray-700 font-medium">-</span> &bull; Owner: <span class="text-gray-700 font-medium">-</span></p>
                            <p>Status: <span class="text-gray-700 font-medium">{{ $unit->status_penjualan }}</span></p>
                        </div>
                    </div>

                    {{-- Card Body (Documents) --}}
                    <div class="p-5 flex-1 flex flex-col gap-3">

                        @foreach($unit->legalDocuments as $doc)
                            <div class="border border-gray-200 rounded-lg p-3 flex items-center gap-2 bg-white hover:border-emerald-300 transition-colors group">
                                <div class="flex items-center gap-2 min-w-0 flex-1">
                                    <div class="bg-emerald-50 p-2 rounded text-emerald-600 flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 leading-tight line-clamp-2">{{ $doc->document_name }}</p>
                                        <p class="text-xs text-gray-600 font-medium mt-0.5 truncate">No: {{ $doc->document_number ?? '-' }}</p>
                                        <p class="text-[10px] text-gray-400 mt-0.5">Uploaded: {{ $doc->created_at ? $doc->created_at->format('d M Y') : '-' }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-1 flex-shrink-0">
                                    <a href="{{ route('legalitas.preview', $doc->id) }}" target="_blank" class="text-gray-400 hover:text-blue-600 p-1 transition-colors" title="Preview PDF">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('legalitas.download', $doc->id) }}" class="text-gray-400 hover:text-emerald-600 p-1 transition-colors" title="Download File">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>
                                    @if(auth()->user()->role === 'Admin')
                                    <form action="{{ route('legalitas.document.destroy', $doc->id) }}" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus dokumen \'{{ $doc->document_name }}\'? Tindakan ini tidak dapat dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-600 p-1 transition-colors" title="Hapus Dokumen">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        {{-- Upload Form Container --}}
                        <div class="border border-dashed {{ $isComplete ? 'border-gray-300 bg-gray-50' : 'border-red-300 bg-red-50/50' }} rounded-lg p-3 flex flex-col gap-2 mt-2">
                            @if(!$isComplete)
                            <div class="flex items-start gap-2">
                                <div class="bg-white p-1 rounded border border-red-100 text-red-500 shadow-sm mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-red-800">Required document missing</p>
                                    <p class="text-[10px] text-red-600 mt-0.5">Please upload certificates or permits.</p>
                                </div>
                            </div>
                            @else
                            <div class="flex items-start gap-2 mb-1">
                                <div class="bg-white p-1 rounded border border-emerald-100 text-emerald-500 shadow-sm mt-0.5">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-gray-700">Add more documents</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">You can upload additional files to this unit.</p>
                                </div>
                            </div>
                            @endif

                            {{-- Upload Form --}}
                            <form action="{{ route('legalitas.upload', $unit->id) }}" method="POST" enctype="multipart/form-data" class="mt-2 bg-white p-3 rounded-md border {{ $isComplete ? 'border-gray-200' : 'border-red-200' }}">
                                @csrf

                                <div class="mb-3">
                                    <label class="block text-[10px] font-bold text-gray-700 mb-1 uppercase tracking-wider">Jenis Dokumen <span class="text-red-500">*</span></label>
                                    <input type="text" name="document_name" class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-emerald-500 focus:outline-none" placeholder="Contoh: SHM / IMB / PBB" value="Sertifikat Tanah / IMB" required>
                                </div>

                                <div class="mb-3">
                                    <label class="block text-[10px] font-bold text-gray-700 mb-1 uppercase tracking-wider">Nomor Dokumen <span class="text-red-500">*</span></label>
                                    <input type="text" name="document_number" class="w-full px-2 py-1.5 border border-gray-300 rounded text-xs focus:ring-1 focus:ring-emerald-500 focus:outline-none" placeholder="Masukkan No. Dokumen" required>
                                </div>

                                <div class="mb-3">
                                    <label class="block text-[10px] font-bold text-gray-700 mb-1 uppercase tracking-wider">File PDF <span class="text-red-500">*</span></label>
                                    <input type="file" name="file_legalitas" accept="application/pdf" class="w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-[10px] file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" required>
                                </div>

                                <button type="submit" class="w-full {{ $isComplete ? 'bg-gray-800 hover:bg-gray-900' : 'bg-emerald-600 hover:bg-emerald-700' }} text-white text-xs font-semibold py-1.5 rounded transition-colors flex items-center justify-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                    Upload Dokumen
                                </button>
                            </form>
                        </div>

                    </div>

                    {{-- Card Footer --}}
                    <div class="px-5 pb-4 pt-2 flex-shrink-0 flex justify-between items-center mt-2">
                        @if($isComplete)
                            <span class="text-xs font-bold text-gray-400">All docs verified</span>
                            <a href="#" class="text-xs font-bold text-emerald-600 hover:text-emerald-800 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                History
                            </a>
                        @else
                            <span class="text-[10px] text-gray-500">Awaiting completion</span>
                            @if($unit->legalDocuments->count() > 0)
                            <form action="{{ route('legalitas.complete', $unit->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="text-[10px] font-bold text-white bg-emerald-600 hover:bg-emerald-700 px-3 py-1.5 rounded transition-colors shadow-sm">
                                    Tandai Selesai
                                </button>
                            </form>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

@empty
    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center flex flex-col items-center justify-center shadow-sm">
        <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
        <h3 class="text-lg font-bold text-gray-800">No properties found</h3>
        <p class="text-sm text-gray-500 mt-2 max-w-sm">There are currently no housing units in the database. Add properties to start managing legal compliance.</p>
    </div>
@endforelse
@endsection