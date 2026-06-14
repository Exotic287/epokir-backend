<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['pokir_id', 'field_name', 'note', 'is_resolved', 'flagged_by'])]
class PokirRevisionFlagged extends Model
{
    protected function casts(): array
    {
        return [
            'is_resolved' => 'boolean',
        ];
    }

    public function pokir(): BelongsTo
    {
        return $this->belongsTo(Pokir::class);
    }

    public function flaggedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'flagged_by');
    }
}
