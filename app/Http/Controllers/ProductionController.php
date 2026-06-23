<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Unit;
use App\Models\ConstructionProgress;
use App\Models\ProgressPhoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProductionController extends Controller
{
    public function index()
    {
        $blocks = Block::with([
            'units' => fn($q) => $q->select('id', 'block_id', 'unit_number', 'progres_pembangunan', 'status_penjualan'),
            'units.constructionProgress' => fn($q) => $q->select('id', 'unit_id', 'tahap', 'persentase', 'created_at')->orderBy('created_at', 'desc')
        ])->get();

        return view('production.index', compact('blocks'));
    }

    public function show($unitId)
    {
        $unit = Unit::with(['block', 'constructionProgress.updater', 'constructionProgress.photos'])->findOrFail($unitId);
        $progressHistory = $unit->constructionProgress()->orderBy('created_at', 'desc')->get();
        
        $chartData = $unit->constructionProgress()
            ->select('tahap', DB::raw('MAX(persentase) as persentase'))
            ->groupBy('tahap')
            ->pluck('persentase', 'tahap');

        $allTahap = ['Persiapan Lahan', 'Pondasi', 'Struktur & Dinding', 'Pengecatan', 'Finishing', 'Serah Terima'];
        $chartDataFormatted = collect($allTahap)->map(fn($tahap) => ['tahap' => $tahap, 'persentase' => $chartData[$tahap] ?? 0]);

        return view('production.show', compact('unit', 'progressHistory', 'chartDataFormatted'));
    }

    public function edit($unitId)
    {
        $unit = Unit::with('block')->findOrFail($unitId);
        return view('production.edit', compact('unit'));
    }

    public function updateProgress(Request $request, $unitId)
    {
        $request->validate([
            'tahap' => 'nullable|in:Persiapan Lahan,Pondasi,Struktur & Dinding,Pengecatan,Finishing,Serah Terima',
            'persentase' => 'required|integer|min:0|max:100',
            'catatan' => 'nullable|string|max:500',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $unit = Unit::findOrFail($unitId);
        $progress = ConstructionProgress::create([
            'unit_id' => $unit->id,
            'tahap' => $request->tahap ?? 'Persiapan Lahan',
            'persentase' => $request->persentase,
            'catatan' => $request->catatan,
            'updated_by' => auth()->id() ?? 1,
        ]);

        $unit->update(['progres_pembangunan' => $request->persentase]);

        if ($request->hasFile('foto')) {
            try {
                $photo = $request->file('foto');
                $photoPath = $photo->storeAs('progress_photos', time() . '_' . $photo->getClientOriginalName(), 'public');
                ProgressPhoto::create([
                    'progress_id' => $progress->id,
                    'file_path' => $photoPath,
                    'keterangan' => $request->catatan,
                    'uploaded_by' => auth()->id() ?? 1,
                ]);
            } catch (\Exception $e) {
                Log::error('Upload foto error: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal upload foto')->withInput();
            }
        }

        return redirect()->route('production.show', $unit->id)->with('success', 'Progress berhasil diupdate!');
    }

    public function generateReport($unitId)
    {
        $unit = Unit::with(['block', 'constructionProgress.updater', 'constructionProgress.photos'])->findOrFail($unitId);
        $progressHistory = $unit->constructionProgress()->orderBy('created_at', 'asc')->get();
        
        $pdf = \PDF::loadView('production.report-pdf', compact('unit', 'progressHistory'));
        return $pdf->download("laporan-{$unit->unit_number}-" . now()->format('YmdHis') . ".pdf");
    }
}