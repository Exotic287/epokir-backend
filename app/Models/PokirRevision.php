<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['pokir_id', 'pokir_activity_id', 'field_name', 'old_value', 'new_value', 'changed_by', 'created_at'])]
class PokirRevision extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function pokir(): BelongsTo
    {
        return $this->belongsTo(Pokir::class);
    }

    public function activity(): BelongsTo
    {
        return $this->belongsTo(PokirActivity::class, 'pokir_activity_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
