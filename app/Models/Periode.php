<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nama', 'tanggal_buka', 'batas_submit', 'jadwal_freeze', 'status', 'created_by', 'deactivated_reason'])]
class Periode extends Model
{
    protected function casts(): array
    {
        return [
            'tanggal_buka' => 'date:Y-m-d',
            'batas_submit' => 'date:Y-m-d',
            'jadwal_freeze' => 'date:Y-m-d',
        ];
    }
}
