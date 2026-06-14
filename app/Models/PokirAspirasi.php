<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['pokir_id', 'aspirasi_id', 'position', 'added_at'])]
class PokirAspirasi extends Model
{
    public $incrementing = false;

    protected function casts(): array
    {
        return [
            'added_at' => 'datetime',
        ];
    }

    public function pokir(): BelongsTo
    {
        return $this->belongsTo(Pokir::class);
    }

    public function aspirasi(): BelongsTo
    {
        return $this->belongsTo(Aspirasi::class);
    }
}
