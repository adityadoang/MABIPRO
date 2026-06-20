<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Unit;
use App\Models\ConstructionProgress;
use App\Models\ProgressPhoto;
use App\Models\Report;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        $blocks = Block::with(['units' => function($query) {
            $query->select('id', 'block_id', 'unit_number', 'progres_pembangunan', 'status_penjualan');
        }])->get();

        return view('production.index', compact('blocks'));
    }

    public function show($unitId)
{
    $unit = Unit::with(['block', 'constructionProgress.updater', 'constructionProgress.photos'])
                ->findOrFail($unitId);

    $progressHistory = $unit->constructionProgress()
                            ->orderBy('created_at', 'desc')
                            ->get();

    // FIX: Ambil progres terbaru PER TAHAP (untuk chart)
    $chartData = $unit->constructionProgress()
                      ->select('tahap', \DB::raw('MAX(persentase) as persentase'))
                      ->groupBy('tahap')
                      ->pluck('persentase', 'tahap');

    // Pastikan semua 6 tahap ada di chart (kalau belum ada, isi 0)
    $allTahap = ['Persiapan Lahan', 'Pondasi', 'Struktur & Dinding', 'Pengecatan', 'Finishing', 'Serah Terima'];
    $chartDataFormatted = collect($allTahap)->map(function($tahap) use ($chartData) {
        return [
            'tahap' => $tahap,
            'persentase' => $chartData[$tahap] ?? 0
        ];
    });

    $latestProgress = $unit->latestProgress();
    $latestPhoto = $unit->latestPhoto();

    return view('production.show', compact('unit', 'progressHistory', 'latestProgress', 'latestPhoto', 'chartDataFormatted'));
}

    public function edit($unitId)
    {
        $unit = Unit::with('block')->findOrFail($unitId);
        
        return view('production.edit', compact('unit'));
    }

    public function updateProgress(Request $request, $unitId)
{
    $request->validate([
        'tahap' => 'required|in:Persiapan Lahan,Pondasi,Struktur & Dinding,Pengecatan,Finishing,Serah Terima',
        'persentase' => 'required|integer|min:0|max:100',
        'catatan' => 'nullable|string|max:500',
    ]);

    $unit = Unit::findOrFail($unitId);

    // FIX: Hardcode user_id = 1 (sementara, sampai ada sistem login)
    $userId = auth()->id() ?? 1;

    $progress = ConstructionProgress::create([
        'unit_id' => $unit->id,
        'tahap' => $request->tahap,
        'persentase' => $request->persentase,
        'catatan' => $request->catatan,
        'updated_by' => $userId,
    ]);

    $unit->update([
        'progres_pembangunan' => $request->persentase,
        'tanggal_akhir_progres' => now(),
    ]);

    if ($request->hasFile('foto')) {
        $path = $request->file('foto')->store('progress_photos', 'public');
        
        ProgressPhoto::create([
            'progress_id' => $progress->id,
            'file_path' => $path,
            'keterangan' => $request->tahap,
            'uploaded_by' => $userId,
        ]);
    }

    return redirect()->route('production.show', $unitId)
                    ->with('success', 'Progres pembangunan berhasil diupdate!');
}

    public function generateReport($unitId)
{
    $unit = Unit::with(['block', 'constructionProgress.updater', 'constructionProgress.photos'])
                ->findOrFail($unitId);

    $progressHistory = $unit->constructionProgress()
                            ->orderBy('created_at', 'asc')
                            ->get();

    // Generate PDF
    $pdf = \PDF::loadView('production.report-pdf', compact('unit', 'progressHistory'));
    
    // Nama file
    $filename = "laporan-{$unit->unit_number}-" . now()->format('YmdHis') . ".pdf";
    
    // Simpan ke storage
    $path = "reports/{$filename}";
    \Storage::disk('public')->put($path, $pdf->output());
    
    // Simpan record ke database
    Report::create([
        'unit_id' => $unit->id,
        'generated_by' => auth()->id() ?? 1,
        'file_path' => $path,
        'report_type' => 'progres_pembangunan',
        'generated_at' => now(),
    ]);

    // Download PDF
    return $pdf->download($filename);
}
}