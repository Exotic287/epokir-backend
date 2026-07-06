<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['bidang_urusan_id', 'uraian_permasalahan', 'opd_tujuan', 'program', 'skema_lokasi', 'status'])]
class KamusUsulan extends Model
{
    public function bidangUrusan(): BelongsTo
    {
        return $this->belongsTo(BidangUrusan::class);
    }

    public function aspirasi(): HasMany
    {
        return $this->hasMany(Aspirasi::class);
    }
}
