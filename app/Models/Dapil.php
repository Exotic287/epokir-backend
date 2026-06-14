<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['number', 'name', 'description', 'is_active'])]
class Dapil extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function kecamatan(): HasMany
    {
        return $this->hasMany(Kecamatan::class);
    }

    public function desa(): HasMany
    {
        return $this->hasMany(Desa::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function aspirasi(): HasMany
    {
        return $this->hasMany(Aspirasi::class);
    }

    public function pokir(): HasMany
    {
        return $this->hasMany(Pokir::class);
    }
}
