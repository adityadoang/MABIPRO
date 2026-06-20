<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legal Compliance - MABIPRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .sidebar-active { background-color: #6ee7b7; color: #064e3b; font-weight: 600; }
        .sidebar-item { color: #475569; transition: all 0.2s; }
        .sidebar-item:hover { background-color: #e2e8f0; }
        .card-shadow { box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="flex h-screen overflow-hidden text-gray-800">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between h-full flex-shrink-0 z-20 shadow-sm">
        <div>
            <!-- Logo & Profile -->
            <div class="p-6 flex items-center gap-3 border-b border-gray-100">
                <div class="w-10 h-10 rounded-full bg-slate-800 flex items-center justify-center text-white font-bold">
                    A
                </div>
                <div>
                    <h2 class="font-bold text-gray-900 leading-tight">MABIPRO<br>Admin</h2>
                    <p class="text-[10px] text-gray-500 leading-tight mt-0.5">Property Management<br>System</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="p-4 space-y-1">
                <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Overview
                </a>
                <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    Marketing
                </a>
                <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Production
                </a>
                <a href="#" class="sidebar-active flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Legality
                </a>
                <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    User Management
                </a>
            </nav>
        </div>

        <div class="p-4 border-t border-gray-100">
            <button class="w-full bg-[#047857] hover:bg-[#065f46] text-white font-semibold py-2.5 px-4 rounded-lg text-sm flex justify-center items-center gap-2 mb-4 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Property
            </button>
            <nav class="space-y-1">
                <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-2 rounded-lg text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Settings
                </a>
                <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-2 rounded-lg text-sm text-red-600 hover:bg-red-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </a>
            </nav>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col h-full overflow-hidden bg-[#f8fafc]">
        
        <!-- Top Header Area -->
        <div class="px-8 pt-8 pb-6 bg-white border-b border-gray-200 z-10 shadow-sm flex-shrink-0">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Legal Compliance</h1>
                    <p class="text-gray-500 mt-1 text-sm">Manage unit documents, certificates, and permits.</p>
                </div>
                <div class="flex gap-3">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 w-64 bg-gray-50" placeholder="Search units or documents">
                    </div>
                    <button class="p-2 border border-gray-300 rounded-lg bg-gray-50 hover:bg-gray-100 text-gray-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
                    </button>
                </div>
            </div>

            @php
                $totalUnits = $units->count();
                $compliant = $units->where('status_legalitas', 'Lengkap')->count();
                $missing = $totalUnits - $compliant;
                $completionRate = $totalUnits > 0 ? round(($compliant / $totalUnits) * 100) : 0;
            @endphp

            <!-- Stats Row -->
            <div class="grid grid-cols-4 gap-4 mt-8">
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
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider">Pending Review</h3>
                        <div class="p-1.5 bg-blue-100 text-blue-600 rounded-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div class="mt-2">
                        <span class="text-4xl font-bold text-[#1d4ed8]">0</span>
                    </div>
                    <p class="text-xs text-[#1d4ed8] mt-2 font-medium">Awaiting verification</p>
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

        <!-- Scrollable Content Area -->
        <div class="flex-1 overflow-y-auto p-8">
            
            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 shadow-sm flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @php
                $groupedUnits = $units->groupBy(function($unit) {
                    return $unit->block ? $unit->block->nama_blok : 'Unknown Block';
                });
            @endphp

            @forelse($groupedUnits as $blockName => $blockUnits)
            <div class="mb-10">
                <!-- Block Header -->
                <div class="flex items-center gap-3 mb-6 pb-2 border-b border-gray-200">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <h2 class="text-xl font-bold text-gray-800">{{ $blockName }}</h2>
                    <div class="ml-auto">
                        <span class="bg-gray-100 text-gray-600 text-xs font-semibold px-2.5 py-1 rounded-full border border-gray-200">
                            {{ $blockUnits->count() }} Units
                        </span>
                    </div>
                </div>

                <!-- Units Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($blockUnits as $unit)
                        @php
                            $isComplete = $unit->status_legalitas == 'Lengkap';
                            $borderColor = $isComplete ? 'border-gray-200' : 'border-red-500';
                            $borderLeft = $isComplete ? '' : 'border-l-4';
                        @endphp

                        <div class="bg-white rounded-xl shadow-sm border {{ $borderColor }} {{ $borderLeft }} flex flex-col overflow-hidden">
                            
                            <!-- Card Header -->
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

                            <!-- Card Body (Documents) -->
                            <div class="p-5 flex-1 flex flex-col gap-3">
                                
                                @foreach($unit->legalDocuments as $doc)
                                    <div class="border border-gray-200 rounded-lg p-3 flex items-center justify-between bg-white hover:border-emerald-300 transition-colors group">
                                        <div class="flex items-center gap-3">
                                            <div class="bg-emerald-50 p-2 rounded text-emerald-600">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-800 leading-tight">{{ $doc->document_name }}</p>
                                                <p class="text-[10px] text-gray-500 mt-0.5">Uploaded: {{ $doc->created_at ? $doc->created_at->format('d M Y') : '-' }}</p>
                                            </div>
                                        </div>
                                        <a href="{{ route('legalitas.download', $doc->id) }}" class="text-gray-400 hover:text-emerald-600 p-1">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        </a>
                                    </div>
                                @endforeach

                                @if(!$isComplete)
                                    <!-- Missing Document Indicator -->
                                    <div class="border border-dashed border-red-300 rounded-lg p-3 bg-red-50/50 flex flex-col gap-2">
                                        <div class="flex items-start gap-2">
                                            <div class="bg-white p-1 rounded border border-red-100 text-red-500 shadow-sm mt-0.5">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-red-800">Required document missing</p>
                                                <p class="text-[10px] text-red-600 mt-0.5">Please upload certificates or permits.</p>
                                            </div>
                                        </div>

                                        <!-- Upload Form -->
                                        <form action="{{ route('legalitas.upload', $unit->id) }}" method="POST" enctype="multipart/form-data" class="mt-2">
                                            @csrf
                                            <div class="relative group cursor-pointer">
                                                <input type="file" name="file_legalitas" accept="application/pdf" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required onchange="this.form.submit()">
                                                <input type="hidden" name="document_name" value="Sertifikat Tanah / IMB">
                                                <div class="border border-gray-300 bg-white rounded-md p-3 text-center transition-colors group-hover:border-emerald-500 group-hover:bg-emerald-50 flex flex-col items-center justify-center gap-1">
                                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                                    <p class="text-[10px] font-semibold text-gray-600 group-hover:text-emerald-700">Click to upload <span class="font-normal text-gray-400">or drag PDF</span></p>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                @endif

                            </div>
                            
                            <!-- Card Footer -->
                            @if($isComplete)
                            <div class="px-5 pb-4 pt-2 flex-shrink-0 flex justify-end">
                                <a href="#" class="text-xs font-bold text-emerald-600 hover:text-emerald-800 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    View History
                                </a>
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-200 p-12 text-center flex flex-col items-center justify-center shadow-sm">
                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <h3 class="text-lg font-bold text-gray-800">No properties found</h3>
                    <p class="text-sm text-gray-500 mt-2 max-w-sm">There are currently no housing units in the database. Add properties to start managing legal compliance.</p>
                </div>
            @endforelse

        </div>

        <!-- Footer -->
        <footer class="bg-gray-200/50 border-t border-gray-300 px-8 py-4 flex justify-between items-center flex-shrink-0 mt-auto">
            <div class="flex items-center gap-6">
                <span class="font-bold text-gray-900 text-xs">MABIPRO</span>
                <span class="text-gray-500 text-xs">&copy; 2026 MABIPRO Property Management. All rights reserved.</span>
            </div>
            <div class="flex items-center gap-4 text-xs text-gray-500 font-medium">
                <a href="#" class="hover:text-gray-900">Privacy Policy</a>
                <a href="#" class="hover:text-gray-900">Terms of Service</a>
                <a href="#" class="hover:text-gray-900">Help Center</a>
            </div>
        </footer>

    </main>

</body>
</html>