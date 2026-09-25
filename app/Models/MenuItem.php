<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    protected $fillable = [
        'menu_id',
        'parent_id',
        'title',
        'type',
        'reference_id',
        'url',
        'target',
        'icon',
        'order_column',
        'is_active',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'order_column'  => 'integer',
        'reference_id'  => 'integer',
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->orderBy('order_column');
    }

    public function activeChildren(): HasMany
    {
        return $this->hasMany(MenuItem::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('order_column');
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'reference_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'reference_id');
    }

    /**
     * Resolusi URL cerdas berdasarkan tipe item menu.
     */
    public function getUrl(): string
    {
        if ($this->type === 'page') {
            if ($this->page) {
                return route('public.page', $this->page->slug);
            }
            if ($this->reference_id) {
                $page = Page::find($this->reference_id);
                if ($page) {
                    return route('public.page', $page->slug);
                }
            }
            return '#';
        }

        if ($this->type === 'category') {
            if ($this->category) {
                return route('public.berita', ['kategori' => $this->category->name]);
            }
            if ($this->reference_id) {
                $cat = Category::find($this->reference_id);
                if ($cat) {
                    return route('public.berita', ['kategori' => $cat->name]);
                }
            }
            return route('public.berita');
        }

        if (empty($this->url)) {
            return '#';
        }

        if (str_starts_with($this->url, 'http://') || str_starts_with($this->url, 'https://') || str_starts_with($this->url, 'mailto:') || str_starts_with($this->url, 'tel:')) {
            return $this->url;
        }

        if (str_starts_with($this->url, '/')) {
            return url($this->url);
        }

        return url('/' . $this->url);
    }

    /**
     * Mengecek apakah item menu ini cocok dengan URL/halaman saat ini.
     */
    public function isActiveUrl(): bool
    {
        $currentUrl = request()->url();
        $itemUrl = $this->getUrl();

        if ($itemUrl === '#' || empty($itemUrl)) {
            return false;
        }

        if ($currentUrl === $itemUrl) {
            return true;
        }

        // Cek jika rute saat ini cocok dengan prefix
        if ($itemUrl !== url('/') && str_starts_with($currentUrl, $itemUrl)) {
            return true;
        }

        return false;
    }

    /**
     * Label tipe yang mudah dibaca.
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'module'      => 'Modul Sistem',
            'page'        => 'Halaman Statis',
            'category'    => 'Kategori Artikel',
            'custom_link' => 'Custom Link',
            default       => ucfirst(str_replace('_', ' ', $this->type ?? 'link')),
        };
    }
}
