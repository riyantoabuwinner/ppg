<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['published_at' => 'datetime'];

    public function author() { return $this->belongsTo(User::class, 'author_id'); }

    public function galleries() { return $this->hasMany(Gallery::class, 'article_id'); }

    public function getGambarUrlAttribute(): ?string
    {
        if (empty($this->gambar)) {
            return null;
        }

        if (str_starts_with($this->gambar, 'http://') || str_starts_with($this->gambar, 'https://')) {
            return $this->gambar;
        }

        return asset('storage/' . $this->gambar);
    }

    public function scopePublished($query) {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    // Auto-generate slug dari judul
    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->judul) . '-' . Str::random(5);
            }
        });
    }
}
