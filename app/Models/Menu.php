<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'location',
    ];

    /**
     * Semua item yang berelasi dengan menu ini, diurutkan berdasarkan kolom order_column.
     */
    public function items(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_id')->orderBy('order_column');
    }

    /**
     * Hanya item level root (tanpa parent).
     */
    public function rootItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'menu_id')
            ->whereNull('parent_id')
            ->orderBy('order_column');
    }

    /**
     * Scope query untuk filter lokasi menu.
     */
    public function scopeLocation($query, string $location)
    {
        return $query->where('location', $location);
    }

    /**
     * Label lokasi yang ramah pengguna.
     */
    public function getLocationLabelAttribute(): string
    {
        return match ($this->location) {
            'main'   => 'Menu Utama (Main Navbar)',
            'top'    => 'Menu Atas (Top Utility Bar)',
            'footer' => 'Menu Bawah (Footer)',
            'sub'    => 'Menu Sekunder (Sidebar/Sub)',
            default  => ucfirst($this->location),
        };
    }
}
