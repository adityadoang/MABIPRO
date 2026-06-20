<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstructionProgress extends Model
{
    protected $table = 'construction_progress';

    protected $fillable = [
        'unit_id',
        'tahap',
        'persentase',
        'catatan',
        'updated_by',
    ];

    protected $casts = [
        'persentase' => 'integer',
    ];

    // Relasi: Progress ini milik unit mana
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    // Relasi: Progress ini diupdate oleh user siapa
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Relasi: Progress ini punya banyak foto
    public function photos(): HasMany
    {
        return $this->hasMany(ProgressPhoto::class, 'progress_id');
    }
}