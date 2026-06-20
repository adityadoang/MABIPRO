<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressPhoto extends Model
{
    protected $table = 'progress_photos';

    protected $fillable = [
        'progress_id',
        'file_path',
        'keterangan',
        'uploaded_by',
    ];

    // Relasi: Foto ini milik progress mana
    public function progress(): BelongsTo
    {
        return $this->belongsTo(ConstructionProgress::class, 'progress_id');
    }

    // Relasi: Foto ini diupload oleh user siapa
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}