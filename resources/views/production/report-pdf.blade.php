<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Progress - {{ $unit->unit_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #10B981;
            padding-bottom: 10px;
        }
        
        .header h1 {
            margin: 0;
            color: #10B981;
            font-size: 18px;
        }
        
        .header h2 {
            margin: 5px 0;
            color: #374151;
            font-size: 14px;
        }
        
        .unit-info {
            background-color: #ECFDF5;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .unit-info table {
            width: 100%;
        }
        
        .unit-info td {
            padding: 5px;
        }
        
        .unit-info strong {
            color: #047857;
        }
        
        .section-title {
            background-color: #10B981;
            color: white;
            padding: 8px 12px;
            font-weight: bold;
            font-size: 14px;
            margin: 20px 0 10px 0;
            border-radius: 3px;
        }
        
        .progress-item {
            border: 1px solid #E5E7EB;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }
        
        .progress-item-header {
            background-color: #F9FAFB;
            padding: 5px;
            margin: -10px -10px 10px -10px;
            border-bottom: 1px solid #E5E7EB;
        }
        
        .progress-item-header h3 {
            margin: 0;
            color: #10B981;
            font-size: 13px;
        }
        
        .progress-item table {
            width: 100%;
        }
        
        .progress-item td {
            padding: 4px;
            vertical-align: top;
        }
        
        .progress-item .label {
            font-weight: bold;
            color: #6B7280;
            width: 100px;
        }
        
        .progress-item .value {
            color: #111827;
        }
        
        .photo-placeholder {
            background-color: #ECFDF5;
            border: 2px dashed #10B981;
            text-align: center;
            padding: 20px;
            color: #6B7280;
            font-style: italic;
            margin-top: 5px;
        }
        
        .photo-container {
            text-align: center;
            margin: 10px 0;
        }
        
        .photo-container img {
            max-width: 100%;
            height: auto;
            border: 1px solid #E5E7EB;
        }
        
        .signature-section {
            margin-top: 30px;
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        
        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #374151;
            padding-top: 5px;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #6B7280;
            border-top: 1px solid #E5E7EB;
            padding-top: 10px;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .badge-success {
            background-color: #D1FAE5;
            color: #065F46;
        }
        
        .badge-warning {
            background-color: #FEF3C7;
            color: #92400E;
        }
        
        .progress-bar {
            width: 100%;
            height: 20px;
            background-color: #E5E7EB;
            border-radius: 3px;
            overflow: hidden;
            margin: 5px 0;
        }
        
        .progress-fill {
            height: 100%;
            background-color: #10B981;
            text-align: center;
            color: white;
            font-size: 11px;
            line-height: 20px;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <h1>MABIPRO - Property Management</h1>
        <h2>Laporan Progress Konstruksi</h2>
    </div>

    {{-- Unit Information --}}
    <div class="unit-info">
        <table>
            <tr>
                <td><strong>Nomor Unit</strong></td>
                <td>: {{ $unit->unit_number }}</td>
                <td><strong>Blok</strong></td>
                <td>: {{ $unit->block->nama_blok ?? '-' }}</td>
            </tr>
            <tr>
                <td><strong>Status Penjualan</strong></td>
                <td>: 
                    <span class="badge {{ $unit->status_penjualan == 'Terjual' ? 'badge-success' : 'badge-warning' }}">
                        {{ $unit->status_penjualan ?? 'Belum Terjual' }}
                    </span>
                </td>
                <td><strong>Status Legalitas</strong></td>
                <td>: {{ $unit->status_legalitas ?? 'Belum Lengkap' }}</td>
            </tr>
            <tr>
                <td><strong>Total Progress</strong></td>
                <td colspan="3">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $unit->progres_pembangunan }}%">
                            {{ $unit->progres_pembangunan }}%
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Construction History --}}
    <div class="section-title">RIWAYAT KONSTRUKSI</div>
    
    @if($progressHistory->count() > 0)
        @foreach($progressHistory as $progress)
            <div class="progress-item">
                <div class="progress-item-header">
                    <h3>{{ $progress->tahap }} - {{ $progress->persentase }}%</h3>
                </div>
                <table>
                    <tr>
                        <td class="label">CATATAN</td>
                        <td class="value">: {{ $progress->catatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="label">TANGGAL</td>
                        <td class="value">: {{ $progress->created_at->format('d.m.Y // H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="label">PETUGAS</td>
                        <td class="value">: {{ $progress->updater->name ?? 'ADMIN' }}</td>
                    </tr>
                    @if($progress->photos->count() > 0)
                        <tr>
                            <td class="label">FOTO</td>
                            <td class="value">
                                @foreach($progress->photos as $photo)
                                    <div class="photo-container">
                                        <img src="{{ public_path('storage/' . $photo->file_path) }}" alt="Progress Photo">
                                        @if($photo->keterangan)
                                            <p style="font-size: 10px; margin: 5px 0; color: #6B7280;">{{ $photo->keterangan }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                    @else
                        <tr>
                            <td class="label">FOTO</td>
                            <td class="value">
                                <div class="photo-placeholder">Foto progress</div>
                            </td>
                        </tr>
                    @endif
                </table>
            </div>
        @endforeach
    @else
        <p style="text-align: center; color: #6B7280; padding: 20px;">Belum ada riwayat konstruksi</p>
    @endif

    {{-- Signature Section --}}
    <div class="signature-section">
        <div class="signature-box">
            <div>Diketahui Oleh,</div>
            <div class="signature-line">
                ( ............................. )<br>
                <span style="font-size: 11px;">Project Manager</span>
            </div>
        </div>
        <div class="signature-box">
            <div>Dibuat Oleh,</div>
            <div class="signature-line">
                ( {{ auth()->user()->name ?? 'ADMIN' }} )<br>
                <span style="font-size: 11px;">{{ now()->format('d F Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem MABIPRO</p>
        <p>Dicetak pada: {{ now()->format('d F Y H:i') }} WIB</p>
    </div>
</body>
</html>