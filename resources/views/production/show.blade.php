<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Unit {{ $unit->unit_number }} - MABIPRO</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <a href="{{ route('production.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">
            ← Kembali ke Dashboard
        </a>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Unit {{ $unit->unit_number }}</h1>
                    <p class="text-gray-600">{{ $unit->block->nama_blok }}</p>
                </div>
                <a href="{{ route('production.edit', $unit->id) }}" 
                   class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Update Progres
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-blue-50 p-4 rounded">
                    <p class="text-sm text-gray-600">Status Penjualan</p>
                    <p class="text-xl font-bold text-gray-800">{{ $unit->status_penjualan }}</p>
                </div>
                <div class="bg-green-50 p-4 rounded">
                    <p class="text-sm text-gray-600">Progres Total</p>
                    <p class="text-xl font-bold text-gray-800">{{ $unit->progres_pembangunan }}%</p>
                </div>
                <div class="bg-purple-50 p-4 rounded">
                    <p class="text-sm text-gray-600">Status Legalitas</p>
                    <p class="text-xl font-bold text-gray-800">{{ $unit->status_legalitas }}</p>
                </div>
            </div>

            @if($latestProgress)
                <div class="border-t pt-4">
                    <h3 class="text-lg font-semibold mb-2">Tahap Saat Ini</h3>
                    <p class="text-gray-800">{{ $latestProgress->tahap }}</p>
                    <p class="text-sm text-gray-600">{{ $latestProgress->catatan }}</p>
                </div>
            @endif
        </div>

        <!-- Diagram Progress -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Diagram Progress</h2>
            <canvas id="progressChart" height="100"></canvas>
        </div>

        <!-- Histori Progress -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Histori Progress</h2>
            <div class="space-y-4">
                @foreach($progressHistory as $progress)
                    <div class="border-l-4 border-blue-500 pl-4 py-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="font-semibold text-gray-800">{{ $progress->tahap }}</h3>
                                <p class="text-sm text-gray-600">{{ $progress->catatan }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Oleh: {{ $progress->updater->name ?? 'Unknown' }} | 
                                    {{ $progress->created_at->format('d M Y H:i') }}
                                </p>
                            </div>
                            <span class="text-2xl font-bold text-blue-600">{{ $progress->persentase }}%</span>
                        </div>

                        @if($progress->photos->count() > 0)
                            <div class="flex gap-2 mt-2">
                                @foreach($progress->photos as $photo)
                                    <img src="{{ asset('storage/' . $photo->file_path) }}" 
                                         alt="Foto progress" 
                                         class="w-20 h-20 object-cover rounded">
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Generate Report -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <a href="{{ route('production.report', $unit->id) }}" 
               class="bg-green-600 text-white px-6 py-3 rounded hover:bg-green-700 inline-block">
                📄 Generate Laporan PDF
            </a>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('progressChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($progressHistory->pluck('tahap')) !!},
                datasets: [{
                    label: 'Persentase Progress',
                    data: {!! json_encode($progressHistory->pluck('persentase')) !!},
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    </script>
</body>
</html>