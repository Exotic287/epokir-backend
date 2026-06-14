<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

#[Fillable([
    'sso_id', 'name', 'email', 'avatar', 'role',
    'dapil_id', 'dapil_nama',
    'fraksi_id', 'fraksi_nama',
    'is_active', 'sso_token'
])]
// #[Hidden(['sso_token'])]
#[Hidden(['sso_token', 'sso_id', 'deleted_at'])]
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function dapil(): BelongsTo
    {
        return $this->belongsTo(Dapil::class);
    }

    public function aspirasi(): HasMany
    {
        return $this->hasMany(Aspirasi::class);
    }

    public function pokir(): HasMany
    {
        return $this->hasMany(Pokir::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSetwan(): bool
    {
        return $this->role === 'setwan';
    }

    public function isDewan(): bool
    {
        return $this->role === 'dewan';
    }
}
