<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama'])]
class BidangUrusan extends Model
{
    public function kamusUsulan(): HasMany
    {
        return $this->hasMany(KamusUsulan::class);
    }
}
