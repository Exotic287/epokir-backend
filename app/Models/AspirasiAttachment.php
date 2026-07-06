<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['aspirasi_id', 'file_name', 'file_path', 'file_type', 'file_size'])]
class AspirasiAttachment extends Model
{
    protected $appends = ['url', 'view_url'];

    protected function casts(): array
    {
        return [
            'file_size' => 'integer',
        ];
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => Storage::disk('public')->url($this->file_path),
        );
    }

    // Stream lewat controller dengan Content-Disposition: inline, supaya browser/download
    // manager (mis. IDM) menampilkannya alih-alih memaksa download — lihat AspirasiAttachmentController::view()
    protected function viewUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => url("/api/v1/aspirasi/{$this->aspirasi_id}/attachments/{$this->id}/view"),
        );
    }

    public function aspirasi(): BelongsTo
    {
        return $this->belongsTo(Aspirasi::class);
    }
}
