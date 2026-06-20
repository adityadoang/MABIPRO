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

    // Relasi: Blok ini punya banyak unit
    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }
}