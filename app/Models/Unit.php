<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Unit extends Model {
    protected $guarded = [];

    public function block(): BelongsTo {
        return $this->belongsTo(Block::class);
    }
}
