<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['tampil_di_menu' => 'boolean'];

    public function author() { return $this->belongsTo(User::class, 'author_id'); }

    public function scopePublished($query) {
        return $query->where('status', 'published');
    }

    protected static function boot() {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->judul) . '-' . Str::random(5);
            }
        });
    }
}
