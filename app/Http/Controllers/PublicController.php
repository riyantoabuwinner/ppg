<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Slider;
use App\Models\Announcement;
use App\Models\Infographic;

class PublicController extends Controller
{
    public function landing()
    {
        $sliders = Slider::active()->get();
        $announcements = Announcement::active()->latest()->get();
        $articles = Article::published()->latest('published_at')->limit(6)->get();
        $infographics = Infographic::active()->orderBy('urutan')->latest()->get();
        $testimonials = \App\Models\Testimonial::where('is_active', true)->orderBy('sort_order')->latest()->get();
        $partners = \App\Models\Partner::where('is_active', true)->orderBy('sort_order')->latest()->get();
        return view('public.landing', compact('sliders', 'announcements', 'articles', 'infographics', 'testimonials', 'partners'));
    }

    public function beritaList()
    {
        $kategori = request('kategori');
        $q        = trim(request('q', ''));

        $articles = Article::published()
            ->when($kategori && $kategori !== 'Semua', fn($query) => $query->where('kategori', $kategori))
            ->when($q, fn($query) => $query->where(function ($sub) use ($q) {
                $sub->where('judul', 'like', "%{$q}%")
                    ->orWhere('konten', 'like', "%{$q}%")
                    ->orWhere('excerpt', 'like', "%{$q}%");
            }))
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        return view('public.berita-list', compact('articles', 'q', 'kategori'));
    }

    public function beritaShow($slug)
    {
        $article = Article::published()->where('slug', $slug)->firstOrFail();
        return view('public.berita-detail', compact('article'));
    }

    public function pageShow($slug)
    {
        $page = \App\Models\Page::published()->where('slug', $slug)->firstOrFail();
        return view('public.page-detail', compact('page'));
    }
}
