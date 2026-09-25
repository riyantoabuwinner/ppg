<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Announcement extends Model
{
    protected $guarded = ['id'];
    protected $casts = ['is_active' => 'boolean', 'tanggal_mulai' => 'date', 'tanggal_selesai' => 'date'];

    public function scopeActive($query) {
        $today = Carbon::today();
        return $query->where('is_active', true)
            ->where(fn($q) => $q->whereNull('tanggal_mulai')->orWhere('tanggal_mulai', '<=', $today))
            ->where(fn($q) => $q->whereNull('tanggal_selesai')->orWhere('tanggal_selesai', '>=', $today));
    }
}
