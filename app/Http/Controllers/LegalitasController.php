<?php

namespace App\Http\Controllers;

use App\Models\LegalDocument;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Crypt;

/**
 * LegalitasController
 *
 * Hanya menangani download & preview PDF karena keduanya
 * mengembalikan file binary response yang tidak bisa dihandle Livewire.
 *
 * Logika lainnya (upload, markAsComplete, deleteDocument) sudah
 * dipindah ke App\Livewire\LegalitasDashboard.
 */
class LegalitasController extends Controller
{
    /**
     * Download dokumen PDF yang sudah dienkripsi.
     * Content-Disposition: attachment (langsung download)
     */
    public function download(int $document_id)
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

    /**
     * FR-011: Preview dokumen PDF langsung di browser.
     * Content-Disposition: inline (tampil di browser, bukan download)
     */
    public function preview(int $document_id)
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
}