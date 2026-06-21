<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Progres - Unit {{ $unit->unit_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #1F2937;
            background-color: #F9FAFB;
        }
        
        .header {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
            padding: 30px;
            text-align: center;
            margin-bottom: 25px;
        }
        
        .header h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }
        
        .header h2 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .header .date {
            font-size: 11px;
            opacity: 0.95;
        }
        
        .section {
            margin-bottom: 20px;
            border: 1px solid #E5E7EB;
            border-radius: 8px;
            overflow: hidden;
            background: white;
        }
        
        .section-header {
            background: linear-gradient(135deg, #D1FAE5 0%, #A7F3D0 100%);
            color: #065F46;
            padding: 12px 20px;
            font-weight: 600;
            font-size: 13px;
            border-bottom: 2px solid #10B981;
        }
        
        .section-content {
            padding: 20px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        table tr {
            border-bottom: 1px solid #E5E7EB;
        }
        
        table tr:last-child {
            border-bottom: none;
        }
        
        table td {
            padding: 10px 15px;
            vertical-align: top;
        }
        
        table td:first-child {
            width: 35%;
            font-weight: 600;
            color: #374151;
            background-color: #F9FAFB;
        }
        
        table td:last-child {
            color: #1F2937;
        }
        
        .progress-item {
            margin-bottom: 15px;
            border: 1px solid #E5E7EB;
            border-radius: 6px;
            overflow: hidden;
        }
        
        .progress-item:last-child {
            margin-bottom: 0;
        }
        
        .progress-header {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
            padding: 10px 15px;
            font-weight: 600;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .progress-percentage {
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 10px;
            border-radius: 4px;
            font-size: 12px;
        }
        
        .progress-content {
            padding: 12px 15px;
            background-color: #F9FAFB;
        }
        
        .progress-content p {
            margin-bottom: 6px;
            font-size: 11px;
        }
        
        .progress-content strong {
            color: #065F46;
        }
        
        .progress-content .label {
            color: #6B7280;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .badge-success {
            background-color: #D1FAE5;
            color: #065F46;
        }
        
        .badge-warning {
            background-color: #FEF3C7;
            color: #92400E;
        }
        
        .photo-box {
            margin-top: 10px;
            padding: 10px;
            border: 2px dashed #D1FAE5;
            background-color: #ECFDF5;
            text-align: center;
            border-radius: 4px;
        }
        
        .photo-box img {
            max-width: 100%;
            height: auto;
            border-radius: 4px;
        }
        
        .photo-placeholder {
            color: #6B7280;
            font-style: italic;
            font-size: 11px;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #D1FAE5;
            text-align: center;
            font-size: 10px;
            color: #6B7280;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN PROGRES PEMBANGUNAN</h1>
        <h2>UNIT {{ strtoupper($unit->unit_number) }} // {{ strtoupper($unit->block->nama_blok) }}</h2>
        <div class="date">
            TANGGAL CETAK: {{ strtoupper(now()->format('d F Y // H:i')) }} WIB
        </div>
    </div>

    <!-- Informasi Unit -->
    <div class="section">
        <div class="section-header">INFORMASI UNIT</div>
        <div class="section-content">
            <table>
                <tr>
                    <td>NOMOR UNIT</td>
                    <td>{{ $unit->unit_number }}</td>
                </tr>
                <tr>
                    <td>BLOK</td>
                    <td>{{ $unit->block->nama_blok }}</td>
                </tr>
                <tr>
                    <td>STATUS PENJUALAN</td>
                    <td>
                        <span class="badge badge-success">{{ strtoupper($unit->status_penjualan) }}</span>
                    </td>
                </tr>
                <tr>
                    <td>PROGRES TOTAL</td>
                    <td><strong style="color: #10B981; font-size: 16px;">{{ $unit->progres_pembangunan }}%</strong></td>
                </tr>
                <tr>
                    <td>STATUS LEGALITAS</td>
                    <td>
                        <span class="badge badge-warning">{{ strtoupper($unit->status_legalitas ?? 'BELUM LENGKAP') }}</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Detail Progress Per Tahap -->
    <div class="section">
        <div class="section-header">DETAIL PROGRESS PER TAHAP</div>
        <div class="section-content">
            @php
                $chartData = $unit->constructionProgress()
                    ->select('tahap', \DB::raw('MAX(persentase) as persentase'))
                    ->groupBy('tahap')
                    ->orderBy('persentase', 'desc')
                    ->get();
            @endphp
            
            @forelse($chartData as $progress)
                @php
                    $latestLog = $unit->constructionProgress()
                        ->where('tahap', $progress->tahap)
                        ->orderBy('created_at', 'desc')
                        ->first();
                @endphp
                
                <div class="progress-item">
                    <div class="progress-header">
                        <span>{{ strtoupper($progress->tahap) }}</span>
                        <span class="progress-percentage">{{ $progress->persentase }}%</span>
                    </div>
                    <div class="progress-content">
                        @if($latestLog)
                            <p><span class="label">Catatan:</span> {{ $latestLog->catatan ?? '-' }}</p>
                            <p><span class="label">Tanggal:</span> {{ $latestLog->created_at->format('d.m.Y // H:i') }}</p>
                            <p><span class="label">Petugas:</span> {{ strtoupper($latestLog->updater->name ?? 'TIM PRODUKSI') }}</p>
                            
                            @if($latestLog->photos->count() > 0)
                                <div class="photo-box">
                                    <p class="label" style="margin-bottom: 8px;">DOKUMENTASI:</p>
                                    @foreach($latestLog->photos as $photo)
                                        <img src="{{ public_path('storage/' . $photo->file_path) }}" alt="Foto Progress">
                                    @endforeach
                                </div>
                            @else
                                <div class="photo-box">
                                    <p class="photo-placeholder">Foto progress</p>
                                </div>
                            @endif
                        @else
                            <p><span class="label">Catatan:</span> -</p>
                            <p><span class="label">Tanggal:</span> -</p>
                            <p><span class="label">Petugas:</span> -</p>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-center" style="color: #6B7280; padding: 20px;">Belum ada data progress</p>
            @endforelse
        </div>
    </div>

    <!-- Construction Log -->
    <div class="section">
        <div class="section-header">RIWAYAT KONSTRUKSI</div>
        <div class="section-content">
            @php
                $allLogs = $unit->constructionProgress()
                    ->with(['updater', 'photos'])
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get();
            @endphp
            
            @forelse($allLogs as $log)
                <div class="progress-item">
                    <div class="progress-header" style="background: linear-gradient(135deg, #34D399 0%, #10B981 100%);">
                        <span>{{ strtoupper($log->tahap) }}</span>
                        <span class="progress-percentage">{{ $log->persentase }}%</span>
                    </div>
                    <div class="progress-content">
                        <p><span class="label">Catatan:</span> {{ $log->catatan ?? '-' }}</p>
                        <p><span class="label">Tanggal:</span> {{ $log->created_at->format('d.m.Y // H:i') }}</p>
                        <p><span class="label">Petugas:</span> {{ strtoupper($log->updater->name ?? 'TIM PRODUKSI') }}</p>
                    </div>
                </div>
            @empty
                <p class="text-center" style="color: #6B7280; padding: 20px;">Belum ada riwayat konstruksi</p>
            @endforelse
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem MABIPRO</p>
        <p>Property Management System</p>
    </div>
</body>
</html>