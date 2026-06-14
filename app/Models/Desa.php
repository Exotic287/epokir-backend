<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name', 'kecamatan_id', 'dapil_id', 'latitude', 'longitude'])]
class Desa extends Model
{
    protected function casts(): array
    {
        return [
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function dapil(): BelongsTo
    {
        return $this->belongsTo(Dapil::class);
    }

    public function aspirasi(): HasMany
    {
        return $this->hasMany(Aspirasi::class);
    }
}
