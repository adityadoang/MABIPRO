<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function generateReport($unitId)
    {
        $unit = Unit::with(['block', 'constructionProgress.updater', 'constructionProgress.photos'])->findOrFail($unitId);
        $progressHistory = $unit->constructionProgress()->orderBy('created_at', 'asc')->get();
        
        $pdf = \PDF::loadView('production.report-pdf', compact('unit', 'progressHistory'));
        return $pdf->download("laporan-{$unit->unit_number}-" . now()->format('YmdHis') . ".pdf");
    }
}