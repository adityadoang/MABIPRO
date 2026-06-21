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
        // Identitas
        'block_id',
        'unit_number',
        // Data pelanggan (Produksi)
        'customer_name',
        'customer_phone',
        // Data fisik (Produksi)
        'luas_tanah',
        'luas_bangunan',
        'tanggal_akhir_progres',
        // Status & progress (semua divisi)
        'status_penjualan',
        'progres_pembangunan',
        'status_legalitas',
        // Harga
        'harga_unit',
        // Payment / KPR (Marketing)
        'payment_method',
        'kpr_duration_months',
        'amount_paid',
        'payment_proof_path',
        'kpr_type',
        'bank_name',
        'akad_date',
        'dp_amount',
        'dp_percentage',
        'pokok_kredit',
        'interest_rate',
        'interest_type',
        'monthly_installment',
    ];

    protected $casts = [
        // Produksi
        'luas_tanah'            => 'decimal:2',
        'luas_bangunan'         => 'decimal:2',
        'progres_pembangunan'   => 'integer',
        'tanggal_akhir_progres' => 'datetime',
        // Marketing / KPR
        'akad_date'             => 'date',
        'amount_paid'           => 'decimal:2',
        'harga_unit'            => 'decimal:2',
        'dp_amount'             => 'decimal:2',
        'dp_percentage'         => 'decimal:2',
        'pokok_kredit'          => 'decimal:2',
        'interest_rate'         => 'decimal:2',
        'monthly_installment'   => 'decimal:2',
    ];

    // ── Relasi ─────────────────────────────────────────────

    // Unit ini milik blok mana
    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class);
    }

    // Unit ini punya banyak histori progres pembangunan (Produksi)
    public function constructionProgress(): HasMany
    {
        return $this->hasMany(ConstructionProgress::class);
    }

    // Unit ini punya banyak foto via construction_progress (Produksi)
    public function photos(): HasManyThrough
    {
        return $this->hasManyThrough(
            ProgressPhoto::class,
            ConstructionProgress::class,
            'unit_id',   // FK di construction_progress
            'progress_id', // FK di progress_photos
            'id',
            'id'
        );
    }

    // Akses langsung ke progress_photos (Marketing/Legalitas/Admin — origin/main)
    public function progressPhotos(): HasMany
    {
        return $this->hasMany(ProgressPhoto::class);
    }

    // Unit ini punya banyak dokumen legalitas (Legalitas)
    public function legalDocuments(): HasMany
    {
        return $this->hasMany(LegalDocument::class);
    }

    // Unit ini punya banyak laporan (Produksi)
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    // ── Helper methods ──────────────────────────────────────

    // Ambil progres pembangunan terbaru
    public function latestProgress()
    {
        return $this->constructionProgress()->latest()->first();
    }

    // Ambil foto terbaru
    public function latestPhoto()
    {
        return $this->photos()->latest()->first();
    }

    public function installmentPayments()
    {
        return $this->hasMany(InstallmentPayment::class)->orderBy('installment_month');
    }
}
