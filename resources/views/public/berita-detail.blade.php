@extends('layouts.public')
@section('title', $article->judul . ' — PPG UIN Siber')
@section('meta-description', $article->excerpt)

@section('content')
<article class="max-w-4xl mx-auto px-6 py-12">
    {{-- Breadcrumb --}}
    <nav class="flex items-center space-x-2 text-xs text-gray-500 mb-6">
        <a href="{{ route('landing') }}" class="hover:text-purple-600">Beranda</a>
        <span>/</span>
        <a href="{{ route('public.berita') }}" class="hover:text-purple-600">Berita</a>
        <span>/</span>
        <span class="text-gray-800 font-medium line-clamp-1">{{ $article->judul }}</span>
    </nav>

    {{-- Category & Date --}}
    <div class="flex items-center space-x-3 mb-4">
        <span class="text-xs px-3 py-1 bg-purple-100 text-purple-700 rounded-full font-semibold">{{ $article->kategori }}</span>
        <span class="text-xs text-gray-400">{{ $article->published_at?->translatedFormat('d F Y') }}</span>
        <span class="text-xs text-gray-400">oleh <span class="font-medium text-gray-600">{{ $article->author->name ?? 'Admin' }}</span></span>
    </div>

    {{-- Title --}}
    <h1 class="text-3xl font-extrabold text-gray-900 leading-tight mb-6">{{ $article->judul }}</h1>

    {{-- Thumbnail --}}
    @if($article->gambar_url || $article->gambar)
    <div class="mb-8 rounded-2xl overflow-hidden shadow-md">
        <img src="{{ $article->gambar_url ?: asset('storage/' . $article->gambar) }}" alt="{{ $article->judul }}" class="w-full max-h-96 object-cover">
    </div>
    @endif

    {{-- Content --}}
    <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed text-sm" style="line-height: 1.9;">
        {!! $article->konten !!}
    </div>

    {{-- Back button --}}
    <div class="mt-12 pt-6 border-t border-gray-100">
        <a href="{{ route('public.berita') }}" class="inline-flex items-center text-sm font-semibold text-purple-600 hover:text-purple-800 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Daftar Berita
        </a>
    </div>
</article>
@endsection
