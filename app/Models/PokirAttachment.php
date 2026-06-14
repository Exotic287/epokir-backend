<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['pokir_id', 'file_name', 'file_path', 'type', 'file_type', 'file_size'])]
class PokirAttachment extends Model
{
    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    public function pokir(): BelongsTo
    {
        return $this->belongsTo(Pokir::class);
    }
}
