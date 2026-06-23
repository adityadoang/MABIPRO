<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'block_id',
        'unit_number',
        'status_penjualan',
        'progres_pembangunan',
        'status_legalitas',
        // ... field lain yang ada di tabel units
    ];

    // Relasi lain yang sudah ada sebelumnya
    public function legalDocuments()
    {
        return $this->hasMany(LegalDocument::class, 'unit_id');
    }

    public function installmentPayments()
    {
        return $this->hasMany(InstallmentPayment::class, 'unit_id');
    }

    // ==========================================
    // RELASI TAMBAHAN (Construction & Block)
    // ==========================================

    public function constructionProgress()
    {
        return $this->hasMany(ConstructionProgress::class, 'unit_id');
    }

    public function latestProgress()
    {
        return $this->hasOne(ConstructionProgress::class, 'unit_id')->latestOfMany();
    }

    public function block()
    {
        return $this->belongsTo(Block::class, 'block_id');
    }

    public function latestPhoto()
    {
        return $this->hasOneThrough(
            ProgressPhoto::class,
            ConstructionProgress::class,
            'unit_id',           // Foreign key di construction_progress
            'progress_id',       // Foreign key di progress_photos
            'id',                // Local key di units
            'id'                 // Local key di construction_progress
        )->latestOfMany();
    }
}