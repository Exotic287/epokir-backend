<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name', 'dapil_id', 'is_active'])]
class Kecamatan extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function dapil(): BelongsTo
    {
        return $this->belongsTo(Dapil::class);
    }

    public function desa(): HasMany
    {
        return $this->hasMany(Desa::class);
    }

    public function aspirasi(): HasMany
    {
        return $this->hasMany(Aspirasi::class);
    }
}
