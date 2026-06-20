<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = [
        'block_id',
        'unit_number',
        'status_penjualan',
        'progres_pembangunan',
        'status_legalitas',
        'payment_method',
        'kpr_duration_months',
        'amount_paid',
        'payment_proof_path',
        'harga_unit',
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
        'akad_date'           => 'date',
        'amount_paid'         => 'decimal:2',
        'harga_unit'          => 'decimal:2',
        'dp_amount'           => 'decimal:2',
        'dp_percentage'       => 'decimal:2',
        'pokok_kredit'        => 'decimal:2',
        'interest_rate'       => 'decimal:2',
        'monthly_installment' => 'decimal:2',
    ];

    public function block(): BelongsTo
    {
        return $this->belongsTo(Block::class);
    }

    public function legalDocuments(): HasMany
    {
        return $this->hasMany(LegalDocument::class);
    }

    public function progressPhotos(): HasMany
    {
        return $this->hasMany(ProgressPhoto::class);
    }
}
