<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Report extends Model
{
    protected $table = 'reports';

    protected $fillable = [
        'unit_id',
        'generated_by',
        'file_path',
        'report_type',
        'generated_at',
    ];

    protected $casts = [
        'generated_at' => 'datetime',
    ];

    // Relasi: Report ini untuk unit mana
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    // Relasi: Report ini di-generate oleh user siapa
    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}