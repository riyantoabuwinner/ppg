@extends('layouts.public')
@section('title', $page->judul . ' — ' . (\App\Models\AppSetting::get('app_name', 'PPG UIN Siber')))
@section('meta-description', $page->meta_description)

@section('content')
<article class="max-w-4xl mx-auto px-6 py-12">
    {{-- Breadcrumb --}}
    <nav class="flex items-center space-x-2 text-xs text-gray-500 mb-6">
        <a href="{{ route('landing') }}" class="hover:text-purple-600">Beranda</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">{{ $page->judul }}</span>
    </nav>

    <h1 class="text-3xl font-extrabold text-gray-900 leading-tight mb-8">{{ $page->judul }}</h1>

    {{-- Konten HTML --}}
    <div class="prose prose-gray max-w-none text-gray-700 leading-relaxed" style="line-height: 1.9;">
        {!! $page->konten !!}
    </div>
</article>
@endsection
