<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class Infographic extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'urutan' => 'integer',
        'views_count' => 'integer',
    ];

    /**
     * Scope item aktif
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * URL Gambar Infografis
     */
    public function getImageUrlAttribute(): string
    {
        if (empty($this->gambar)) {
            return asset('images/default-infographic.png');
        }

        if (str_starts_with($this->gambar, 'http://') || str_starts_with($this->gambar, 'https://')) {
            return $this->gambar;
        }

        return asset('storage/' . $this->gambar);
    }

    /**
     * URL File PDF (jika ada)
     */
    public function getPdfUrlAttribute(): ?string
    {
        if (empty($this->file_pdf)) {
            return null;
        }

        if (str_starts_with($this->file_pdf, 'http://') || str_starts_with($this->file_pdf, 'https://')) {
            return $this->file_pdf;
        }

        return asset('storage/' . $this->file_pdf);
    }
}
