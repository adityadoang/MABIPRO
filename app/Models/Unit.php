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
        'customer_name',
        'customer_phone',
        'status_penjualan',
        'progres_pembangunan',
        'status_legalitas',
        'tanggal_akhir_progres',
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
        'luas_tanah',
        'luas_bangunan',
    ];

    public function legalDocuments()
    {
        return $this->hasMany(LegalDocument::class, 'unit_id');
    }

    public function installmentPayments()
    {
        return $this->hasMany(InstallmentPayment::class, 'unit_id');
    }


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
            'unit_id',          
            'progress_id',       
            'id',                //unit
            'id'                 //construction
        )->latestOfMany();
    }
}