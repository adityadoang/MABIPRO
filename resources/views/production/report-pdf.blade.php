<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Progres - Unit {{ $unit->unit_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 30px; }
        .info-box { background: #f5f5f5; padding: 15px; margin-bottom: 20px; border-radius: 5px; }
        .progress-item { border-left: 4px solid #3b82f6; padding-left: 15px; margin-bottom: 20px; }
        .photo-grid { display: flex; gap: 10px; flex-wrap: wrap; }
        .photo-grid img { width: 150px; height: 150px; object-fit: cover; border-radius: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table th { background: #f5f5f5; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PROGRES PEMBANGUNAN</h1>
        <h2>Unit {{ $unit->unit_number }} - {{ $unit->block->nama_blok }}</h2>
        <p>Tanggal Cetak: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <div class="info-box">
        <h3>Informasi Unit</h3>
        <table>
            <tr>
                <th width="30%">Nomor Unit</th>
                <td>{{ $unit->unit_number }}</td>
            </tr>
            <tr>
                <th>Blok</th>
                <td>{{ $unit->block->nama_blok }}</td>
            </tr>
            <tr>
                <th>Status Penjualan</th>
                <td>{{ $unit->status_penjualan }}</td>
            </tr>
            <tr>
                <th>Progres Total</th>
                <td>{{ $unit->progres_pembangunan }}%</td>
            </tr>
            <tr>
                <th>Status Legalitas</th>
                <td>{{ $unit->status_legalitas }}</td>
            </tr>
        </table>
    </div>

    <h3>Detail Progress Per Tahap</h3>
    @foreach($progressHistory as $progress)
        <div class="progress-item">
            <h4>{{ $progress->tahap }} - {{ $progress->persentase }}%</h4>
            <p><strong>Catatan:</strong> {{ $progress->catatan ?? '-' }}</p>
            <p><strong>Tanggal:</strong> {{ $progress->created_at->format('d M Y H:i') }}</p>
            <p><strong>Petugas:</strong> {{ $progress->updater->name ?? '-' }}</p>

            @if($progress->photos->count() > 0)
                <div class="photo-grid">
                    @foreach($progress->photos as $photo)
                        <img src="{{ public_path('storage/' . $photo->file_path) }}" alt="Foto progress">
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach

    <div style="margin-top: 50px; text-align: center;">
        <p>--- Akhir Laporan ---</p>
    </div>
</body>
</html>