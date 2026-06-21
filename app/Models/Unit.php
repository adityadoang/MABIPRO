<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unit extends Model {
    protected $guarded = [];

    public function block(): BelongsTo {
        return $this->belongsTo(Block::class);
    }

    public function legalDocuments()
    {
        return $this->hasMany(LegalDocument::class);
    }

    public function progressPhotos()
    {
        return $this->hasMany(ProgressPhoto::class);
    }

    public function installmentPayments()
    {
        return $this->hasMany(InstallmentPayment::class)->orderBy('installment_month');
    }

}
