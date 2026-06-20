<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Unit;
use App\Models\LegalDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

class LegalitasController extends Controller
{
    // FR-009: Dashboard Legalitas
    public function index()
    {
        $units = Unit::with(['legalDocuments', 'block'])->get();
        return view('legalitas.dashboard', compact('units'));
    }

    // FR-010: Upload Dokumen & NFR-S01: Enkripsi AES-256
    public function upload(Request $request, $unit_id)
    {
        $request->validate([
            'document_name' => 'required|string',
            'file_legalitas' => 'required|mimes:pdf|max:5120', // NFR-P03: Maks 5MB
        ]);

        $unit = Unit::findOrFail($unit_id);
        $file = $request->file('file_legalitas');

        // NFR-S01: Enkripsi AES-256
        $fileContent = file_get_contents($file->getRealPath());
        $encryptedContent = Crypt::encryptString($fileContent);
        
        $filename = time() . '_' . $file->getClientOriginalName() . '.enc';
        $path = 'legal_docs/' . $filename;
        
        Storage::put($path, $encryptedContent);

        LegalDocument::create([
            'unit_id' => $unit->id,
            'document_name' => $request->document_name,
            'file_path' => $path,
            'uploaded_by' => Auth::id() ?? 1, // Fallback ke user ID 1 jika auth belum disetup
        ]);

        $unit->update(['status_legalitas' => 'Lengkap']);

        return back()->with('success', 'Dokumen berhasil dienkripsi dan diunggah!');
    }

    public function download($document_id)
    {
        $document = LegalDocument::findOrFail($document_id);
        
        if (!Storage::exists($document->file_path)) {
            abort(404);
        }

        $encryptedContent = Storage::get($document->file_path);
        $decryptedContent = Crypt::decryptString($encryptedContent);
        
        $originalFilename = str_replace('.enc', '', basename($document->file_path));

        return response($decryptedContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="' . $originalFilename . '"');
    }
}