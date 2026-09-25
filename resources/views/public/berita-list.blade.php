@extends('layouts.public')
@section('title', 'Berita & Kegiatan — PPG UIN Siber Syekh Nurjati Cirebon')

@section('content')
<section class="max-w-7xl mx-auto px-6 py-12">
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-800 mb-2">Berita & Kegiatan</h1>
        <p class="text-gray-500 text-sm">Informasi, pengumuman, dan kegiatan terbaru Program Pendidikan Profesi Guru.</p>
    </div>

    {{-- Filter Kategori --}}
    <div class="flex flex-wrap gap-2 mb-6">
        @foreach(['Semua', 'Berita', 'Kegiatan', 'Akademik', 'Pengumuman'] as $kat)
        <a href="{{ route('public.berita', array_filter(['kategori' => $kat !== 'Semua' ? $kat : null, 'q' => request('q')])) }}"
            class="text-sm px-4 py-1.5 rounded-full font-medium border transition {{ (request('kategori', 'Semua') === $kat) ? 'bg-purple-600 text-white border-purple-600' : 'bg-white text-gray-600 border-gray-200 hover:border-purple-400 hover:text-purple-700' }}">
            {{ $kat }}
        </a>
        @endforeach
    </div>

    @if(!empty($q))
    <div class="mb-6 p-4 bg-purple-50 border border-purple-200 rounded-2xl flex items-center justify-between">
        <div class="flex items-center space-x-2 text-sm text-purple-900">
            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <span>Hasil pencarian untuk: <strong class="font-bold text-purple-800">"{{ $q }}"</strong> (ditemukan {{ $articles->total() }} berita)</span>
        </div>
        <a href="{{ route('public.berita') }}" class="text-xs font-semibold text-purple-700 hover:text-purple-900 bg-white px-3 py-1.5 rounded-xl border border-purple-200 shadow-sm transition">
            Reset Pencarian
        </a>
    </div>
    @endif

    @if($articles->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @foreach($articles as $article)
        <a href="{{ route('public.berita.show', $article->slug) }}" class="article-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 block" style="transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 30px rgba(124,58,237,0.15)'" onmouseout="this.style.transform='translateY(0)';this.style.boxShadow=''">
            <div class="h-44 bg-gradient-to-br from-purple-100 to-purple-200 overflow-hidden">
                @if($article->gambar_url || $article->gambar)
                <img src="{{ $article->gambar_url ?: asset('storage/' . $article->gambar) }}" alt="{{ $article->judul }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <svg class="w-12 h-12 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
                @endif
            </div>
            <div class="p-5">
                <div class="flex items-center space-x-2 mb-2">
                    <span class="text-xs px-2 py-0.5 bg-purple-100 text-purple-700 rounded-full font-medium">{{ $article->kategori }}</span>
                    <span class="text-xs text-gray-400">{{ $article->published_at?->format('d M Y') }}</span>
                </div>
                <h3 class="font-bold text-gray-800 text-sm leading-snug line-clamp-2 mb-2">{{ $article->judul }}</h3>
                <p class="text-xs text-gray-500 leading-relaxed line-clamp-3">{{ $article->excerpt }}</p>
            </div>
        </a>
        @endforeach
    </div>
    {{ $articles->links() }}
    @else
    <div class="text-center py-20 text-gray-400">
        <p class="font-medium">Belum ada artikel dalam kategori ini.</p>
    </div>
    @endif
</section>
@endsection
