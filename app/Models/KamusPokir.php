<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kamus_version', 'level', 'parent_id', 'opd_id', 'program_sipd_id', 'is_active'])]
class KamusPokir extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'level'     => 'integer',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(KamusPokir::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(KamusPokir::class, 'parent_id');
    }

    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class);
    }

    public function programSipd(): BelongsTo
    {
        return $this->belongsTo(ProgramSipd::class);
    }

    public function pokir(): HasMany
    {
        return $this->hasMany(Pokir::class);
    }
}
