<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['aspirasi_id', 'file_name', 'file_path', 'file_type', 'file_size'])]
class AspirasiAttachment extends Model
{
    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    public function aspirasi(): BelongsTo
    {
        return $this->belongsTo(Aspirasi::class);
    }
}
