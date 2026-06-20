<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Produksi - MABIPRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Dashboard Produksi</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($blocks as $block)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $block->nama_blok }}</h2>
                    <p class="text-gray-600 mb-4">{{ $block->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    
                    <div class="space-y-3">
                        @foreach($block->units as $unit)
                            <a href="{{ route('production.show', $unit->id) }}" 
                               class="block border border-gray-200 rounded p-3 hover:bg-gray-50 transition">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="font-semibold text-gray-800">Unit {{ $unit->unit_number }}</span>
                                    <span class="text-sm px-2 py-1 rounded 
                                        @if($unit->status_penjualan == 'Terjual') bg-green-100 text-green-800
                                        @elseif($unit->status_penjualan == 'Sudah DP') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ $unit->status_penjualan }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center">
                                    <div class="w-full bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-blue-600 h-2 rounded-full" 
                                             style="width: {{ $unit->progres_pembangunan }}%"></div>
                                    </div>
                                    <span class="text-sm font-semibold text-gray-700">
                                        {{ $unit->progres_pembangunan }}%
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>