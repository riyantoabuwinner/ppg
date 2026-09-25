<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Gallery extends Model
{
    protected $guarded = ['id'];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    /**
     * URL publik untuk gambar galeri
     */
    public function getUrlAttribute(): string
    {
        if (str_starts_with($this->file_path, 'http://') || str_starts_with($this->file_path, 'https://')) {
            return $this->file_path;
        }

        if (Storage::disk('public')->exists($this->file_path)) {
            return asset('storage/' . $this->file_path);
        }

        if (!empty($this->file_url)) {
            return $this->file_url;
        }

        return asset('storage/' . $this->file_path);
    }
}
