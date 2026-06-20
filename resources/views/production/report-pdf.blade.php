<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Progres - Unit {{ $unit->unit_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Roboto+Mono&display=swap');
        
        * { box-sizing: border-box; }
        
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #EFEBE9;
            color: #3E2723;
        }
        
        .header {
            background: #3E2723;
            color: #EFEBE9;
            padding: 20px;
            border: 3px solid #3E2723;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .header h1 {
            font-family: 'Oswald', sans-serif;
            font-size: 28px;
            margin: 0;
            letter-spacing: 3px;
        }
        
        .header h2 {
            font-family: 'Oswald', sans-serif;
            font-size: 20px;
            margin: 5px 0 0;
            color: #BF360C;
            letter-spacing: 2px;
        }
        
        .header p {
            font-family: 'Roboto Mono', monospace;
            font-size: 11px;
            margin: 10px 0 0;
            letter-spacing: 1px;
        }
        
        .label-tag {
            background: #3E2723;
            color: #EFEBE9;
            font-family: 'Roboto Mono', monospace;
            font-size: 10px;
            letter-spacing: 2px;
            padding: 3px 10px;
            text-transform: uppercase;
            display: inline-block;
        }
        
        .info-box {
            border: 2px solid #3E2723;
            box-shadow: 4px 4px 0px #3E2723;
            margin-bottom: 20px;
            background: #EFEBE9;
        }
        
        .info-box-header {
            background: #5D4037;
            color: #EFEBE9;
            padding: 10px 15px;
            font-family: 'Oswald', sans-serif;
            letter-spacing: 2px;
            font-size: 14px;
        }
        
        .info-box-body {
            padding: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: 'Roboto Mono', monospace;
            font-size: 12px;
        }
        
        table th {
            background: #D7CCC8;
            text-align: left;
            padding: 8px 12px;
            border: 1px solid #5D4037;
            width: 35%;
            font-weight: 600;
            letter-spacing: 1px;
        }
        
        table td {
            padding: 8px 12px;
            border: 1px solid #5D4037;
            background: #EFEBE9;
        }
        
        .progress-item {
            border: 2px solid #3E2723;
            box-shadow: 3px 3px 0px #3E2723;
            margin-bottom: 15px;
            background: #EFEBE9;
            page-break-inside: avoid;
        }
        
        .progress-item-header {
            background: #BF360C;
            color: #EFEBE9;
            padding: 8px 15px;
            font-family: 'Oswald', sans-serif;
            letter-spacing: 2px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .progress-item-body {
            padding: 15px;
        }
        
        .progress-item-body p {
            margin: 5px 0;
            font-family: 'Roboto Mono', monospace;
            font-size: 11px;
        }
        
        .photo-grid {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px;
        }
        
        .photo-grid img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border: 2px solid #3E2723;
            box-shadow: 2px 2px 0px #3E2723;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 3px double #3E2723;
            font-family: 'Roboto Mono', monospace;
            font-size: 10px;
            color: #5D4037;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PROGRES PEMBANGUNAN</h1>
        <h2>UNIT {{ $unit->unit_number }} // {{ strtoupper($unit->block->nama_blok) }}</h2>
        <p>TANGGAL CETAK: {{ strtoupper(now()->format('d F Y // H:i')) }} WIB</p>
    </div>
    
    <div class="info-box">
        <div class="info-box-header">INFORMASI UNIT</div>
        <div class="info-box-body">
            <table>
                <tr><th>NOMOR UNIT</th><td>{{ $unit->unit_number }}</td></tr>
                <tr><th>BLOK</th><td>{{ strtoupper($unit->block->nama_blok) }}</td></tr>
                <tr><th>STATUS PENJUALAN</th><td>{{ strtoupper($unit->status_penjualan) }}</td></tr>
                <tr><th>PROGRES TOTAL</th><td style="color: #BF360C; font-weight: bold;">{{ $unit->progres_pembangunan }}%</td></tr>
                <tr><th>STATUS LEGALITAS</th><td>{{ strtoupper($unit->status_legalitas) }}</td></tr>
            </table>
        </div>
    </div>
    
    <div class="info-box">
        <div class="info-box-header">DETAIL PROGRESS PER TAHAP</div>
        <div class="info-box-body">
            @foreach($progressHistory as $progress)
                <div class="progress-item">
                    <div class="progress-item-header">
                        <span>{{ strtoupper($progress->tahap) }}</span>
                        <span style="font-size: 20px; font-weight: bold;">{{ $progress->persentase }}%</span>
                    </div>
                    <div class="progress-item-body">
                        <p><strong>CATATAN:</strong> {{ $progress->catatan ?? '-' }}</p>
                        <p><strong>TANGGAL:</strong> {{ $progress->created_at->format('d.m.Y // H:i') }}</p>
                        <p><strong>PETUGAS:</strong> {{ strtoupper($progress->updater->name ?? '-') }}</p>
                        
                        @if($progress->photos->count() > 0)
                            <p style="margin-top: 10px;"><strong>DOKUMENTASI:</strong></p>
                            <div class="photo-grid">
                                @foreach($progress->photos as $photo)
                                    <img src="{{ public_path('storage/' . $photo->file_path) }}" alt="Foto progress">
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
    <div class="footer">
        --- AKHIR LAPORAN // MABIPRO PRODUCTION SYSTEM ---
    </div>
</body>
</html>