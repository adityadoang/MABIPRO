<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Unit extends Model
{
    protected $table = 'units';

    protected $fillable = [
        'block_id',
        'unit_number',
        'customer_name',
        'customer_phone',
        'harga_unit',
        'luas_tanah',
        'luas_bangunan',
        'status_penjualan',
        'progres_pembangunan',
        'status_legalitas',
        'tanggal_akhir_progres',
    ];

    protected $casts = [
        'harga_unit' => 'decimal:2',
        'luas_tanah' => 'decimal:2',
        'luas_bangunan' => 'decimal:2',
        'progres_pembangunan' => 'integer',
        'tanggal_akhir_progres' => 'datetime',
    ];

    // Relasi: Unit ini milik blok mana
    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class);
    }

    // Relasi: Unit ini punya banyak histori progres
    public function constructionProgress(): HasMany
    {
        return $this->hasMany(ConstructionProgress::class);
    }

    // Relasi: Unit ini punya banyak laporan
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    // Relasi: Unit ini punya banyak foto (via construction_progress)
    public function photos(): HasManyThrough
    {
        return $this->hasManyThrough(
            ProgressPhoto::class,
            ConstructionProgress::class,
            'unit_id',
            'progress_id',
            'id',
            'id'
        );
    }

    // Method helper: Ambil progres terbaru
    public function latestProgress()
    {
        return $this->constructionProgress()->latest()->first();
    }

    // Method helper: Ambil foto terbaru
    public function latestPhoto()
    {
        return $this->photos()->latest()->first();
    }
}