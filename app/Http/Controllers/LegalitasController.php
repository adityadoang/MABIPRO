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
            'document_number' => 'required|string|max:100',
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
            'document_number' => $request->document_number,
            'file_path' => $path,
            'uploaded_by' => Auth::id() ?? 1, // Fallback ke user ID 1 jika auth belum disetup
        ]);

        return redirect()->back()->with('success', 'Dokumen berhasil diunggah dengan aman.');
    }

    // Menandai unit sebagai Lengkap secara manual
    public function markAsComplete($unit_id)
    {
        $unit = Unit::findOrFail($unit_id);
        $unit->update(['status_legalitas' => 'Lengkap']);
        
        return redirect()->back()->with('success', 'Status unit ' . $unit->unit_number . ' berhasil ditandai sebagai Lengkap.');
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

    // FR-011: Preview Dokumen PDF langsung di browser
    public function preview($document_id)
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
            ->header('Content-Disposition', 'inline; filename="' . $originalFilename . '"');
    }

    // FR-012: Hapus Dokumen — hanya Admin
    public function destroy($document_id)
    {
        // Pastikan hanya Admin yang bisa menghapus
        if (Auth::user()->role !== 'Admin') {
            abort(403, 'Hanya Admin yang dapat menghapus dokumen.');
        }

        $document = LegalDocument::findOrFail($document_id);

        // Hapus file fisik dari storage
        if (Storage::exists($document->file_path)) {
            Storage::delete($document->file_path);
        }

        $document->delete();

        return redirect()->back()->with('success', 'Dokumen berhasil dihapus.');
    }
}