<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Block extends Model
{
    protected $table = 'blocks';

    protected $fillable = [
        'nama_blok',
        'deskripsi',
        'total_unit',
        'status',
    ];

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function unitsTerjual(): HasMany
    {
        return $this->hasMany(Unit::class)->where('status_penjualan', 'Terjual');
    }

    public function unitsDp(): HasMany
    {
        return $this->hasMany(Unit::class)->where('status_penjualan', 'Sudah DP');
    }
}
