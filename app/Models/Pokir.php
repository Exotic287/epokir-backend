<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['user_id', 'number', 'title', 'kamus_pokir_id', 'opd_id', 'dapil_id', 'status', 'submitted_by', 'verified_by', 'finalized_by', 'kecamatan_ids', 'desa_ids', 'notes'])]
class Pokir extends Model
{
    use SoftDeletes;

    protected function casts(): array
    {
        return [
            'kecamatan_ids' => 'array',
            'desa_ids' => 'array',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kamusPokir(): BelongsTo
    {
        return $this->belongsTo(KamusPokir::class);
    }

    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class);
    }

    public function dapil(): BelongsTo
    {
        return $this->belongsTo(Dapil::class);
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function finalizedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }

    public function aspirasi(): BelongsToMany
    {
        return $this->belongsToMany(Aspirasi::class, 'pokir_aspirasis')
            ->withPivot('position', 'added_at')
            ->orderByPivot('position');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(PokirActivity::class);
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(PokirRevision::class);
    }

    public function revisionsFlagged(): HasMany
    {
        return $this->hasMany(PokirRevisionFlagged::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(PokirAttachment::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }
    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }
    public function isVerified(): bool
    {
        return $this->status === 'verified';
    }
    public function isFinalized(): bool
    {
        return $this->status === 'finalized';
    }
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }
}
