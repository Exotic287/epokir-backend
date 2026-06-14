<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['code', 'name', 'short_name', 'is_active'])]
class Opd extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function programSipd(): HasMany
    {
        return $this->hasMany(ProgramSipd::class);
    }

    public function kamusPokir(): HasMany
    {
        return $this->hasMany(KamusPokir::class);
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
