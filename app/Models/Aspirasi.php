<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable([
    'user_id', 'code', 'title', 'description', 'tanggal', 'desa_id', 'kecamatan_id', 'dapil_id', 'opd_id',
    'kamus_usulan_id', 'source', 'alamat', 'latitude', 'longitude', 'narasi_dokumen', 'is_complete', 'is_used_in_pokir',
    'is_archived',
])]
class Aspirasi extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'tanggal'         => 'date',
            'latitude'        => 'float',
            'longitude'       => 'float',
            'is_complete'     => 'boolean',
            'is_used_in_pokir' => 'boolean',
            'is_archived'     => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class);
    }

    public function dapil(): BelongsTo
    {
        return $this->belongsTo(Dapil::class);
    }

    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class);
    }

    public function kamusUsulan(): BelongsTo
    {
        return $this->belongsTo(KamusUsulan::class);
    }

    public function pokir(): BelongsToMany
    {
        return $this->belongsToMany(Pokir::class, 'pokir_aspirasis')
            ->withPivot('position', 'added_at')
            ->orderByPivot('position');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(AspirasiActivity::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(AspirasiAttachment::class);
    }
}
