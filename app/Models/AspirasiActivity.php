<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OpenApi\Attributes as OA;

#[Fillable(['aspirasi_id', 'user_id', 'action', 'changes', 'created_at'])]
class AspirasiActivity extends Model
{
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'changes' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function aspirasi(): BelongsTo
    {
        return $this->belongsTo(Aspirasi::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
