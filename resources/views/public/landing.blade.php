@extends('layouts.public')
@section('title', 'Beranda — PPG UIN Siber Syekh Nurjati Cirebon')
@section('meta-description', 'Portal resmi Program Pendidikan Profesi Guru UIN Siber Syekh Nurjati Cirebon. Informasi lapor diri, berita, dan pengumuman.')

@section('content')@php
    $landingActivePeriod   = \App\Models\Period::currentOpen();
    $landingUpcomingPeriod = \App\Models\Period::nextUpcoming();
@endphp

{{-- ===== MASTER HERO SECTION: SLIDER + UNIFIED ACTION CARD ===== --}}
<section class="relative overflow-hidden bg-white">

    {{-- ANNOUNCEMENT MARQUEE --}}
    @if($announcements->count() > 0)
    <div class="absolute top-[10px] left-0 right-0 z-40 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto pointer-events-none">
        <div class="pointer-events-auto bg-white shadow border border-purple-100 rounded-full flex items-center overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-[10px] sm:text-[11px] font-extrabold px-4 sm:px-5 py-2 uppercase tracking-wider flex-shrink-0 z-10 flex items-center">
                <i class="fa-solid fa-bullhorn mr-2 animate-pulse"></i> <span class="hidden sm:inline">Pengumuman</span>
            </div>
            <div class="relative flex-1 overflow-hidden flex items-center py-2 h-full" style="-webkit-mask-image: linear-gradient(to right, transparent, black 3%, black 97%, transparent);">
                <div class="animate-marquee-announcement flex items-center whitespace-nowrap text-gray-700 text-xs sm:text-sm">
                    @foreach($announcements as $ann)
                        <span class="mx-6 flex items-center">
                            @if($ann->tipe == 'penting')
                                <span class="w-2 h-2 rounded-full bg-red-400 mr-2 animate-pulse shadow-[0_0_8px_rgba(248,113,113,0.8)]"></span>
                            @elseif($ann->tipe == 'peringatan')
                                <span class="w-2 h-2 rounded-full bg-yellow-400 mr-2 shadow-[0_0_8px_rgba(250,204,21,0.8)]"></span>
                            @else
                                <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 shadow-[0_0_8px_rgba(96,165,250,0.8)]"></span>
                            @endif
                            <span class="font-medium hover:text-purple-200 transition-colors cursor-pointer">{!! $ann->judul !!}</span>
                        </span>
                    @endforeach
                    {{-- Duplicate for infinite scroll --}}
                    @foreach($announcements as $ann)
                        <span class="mx-6 flex items-center">
                            @if($ann->tipe == 'penting')
                                <span class="w-2 h-2 rounded-full bg-red-400 mr-2 animate-pulse shadow-[0_0_8px_rgba(248,113,113,0.8)]"></span>
                            @elseif($ann->tipe == 'peringatan')
                                <span class="w-2 h-2 rounded-full bg-yellow-400 mr-2 shadow-[0_0_8px_rgba(250,204,21,0.8)]"></span>
                            @else
                                <span class="w-2 h-2 rounded-full bg-blue-400 mr-2 shadow-[0_0_8px_rgba(96,165,250,0.8)]"></span>
                            @endif
                            <span class="font-medium hover:text-purple-600 transition-colors cursor-pointer">{!! $ann->judul !!}</span>
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <style>
        @keyframes marquee-announcement {
            0% { transform: translateX(0%); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee-announcement {
            display: inline-flex;
            width: max-content;
            animation: marquee-announcement {{ max(20, $announcements->count() * 10) }}s linear infinite;
        }
        .animate-marquee-announcement:hover {
            animation-play-state: paused;
        }
    </style>
    @endif

    {{-- 1. HERO SLIDER / DEFAULT CONTENT --}}
    @if($sliders->count() > 0)
        <div id="slider-container" class="absolute inset-x-0 top-0 z-0 overflow-hidden" style="height:610px; -webkit-mask-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22 preserveAspectRatio=%22none%22%3E%3Cpath d=%22M0,0 L100,0 L100,86 C70,100 30,100 0,86 Z%22 fill=%22black%22/%3E%3C/svg%3E'); mask-image: url('data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22 preserveAspectRatio=%22none%22%3E%3Cpath d=%22M0,0 L100,0 L100,86 C70,100 30,100 0,86 Z%22 fill=%22black%22/%3E%3C/svg%3E'); mask-size: 100% 100%;">
            @foreach($sliders as $i => $slide)
            <div class="slide {{ $i === 0 ? 'active' : '' }} absolute inset-0 w-full h-full pb-16" style="min-height:460px;">
                <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('storage/' . $slide->gambar) }}');"></div>


                <div class="relative z-10 flex items-start h-full max-w-7xl mx-auto px-6 pt-36 pb-4">
                    <div class="max-w-xl text-white">
                        @if($slide->judul)
                        <h2 class="text-3xl lg:text-4xl font-extrabold leading-tight mb-3">{{ $slide->judul }}</h2>
                        @endif
                        @if($slide->subjudul)
                        <p class="text-purple-100 text-base leading-relaxed mb-6">{{ $slide->subjudul }}</p>
                        @endif
                        <div class="flex flex-wrap gap-3">
                            @if($slide->tampilkan_tombol)
                            <a href="{{ $slide->link_tombol ?? '#' }}" class="px-6 py-3 !bg-white !text-purple-950 font-bold rounded-xl text-sm shadow-lg hover:bg-purple-50 transition">{{ $slide->teks_tombol ?: 'Selengkapnya' }}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Slider Controls --}}
            @if($sliders->count() > 1)
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex space-x-2 z-20">
                @foreach($sliders as $i => $slide)
                <button onclick="goToSlide({{ $i }})" id="dot-{{ $i }}" class="w-2 h-2 rounded-full transition-all {{ $i === 0 ? 'bg-white w-6' : 'bg-white/40' }}"></button>
                @endforeach
            </div>
            <button onclick="prevSlide()" class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-white/20 hover:bg-white/30 backdrop-blur rounded-full flex items-center justify-center text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button onclick="nextSlide()" class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-white/20 hover:bg-white/30 backdrop-blur rounded-full flex items-center justify-center text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            @endif


        </div>
    @else
    {{-- Default Hero jika tidak ada slide --}}
    <div class="relative pt-14 pb-10 sm:pt-20 sm:pb-14 text-center">
        <div class="relative z-10 max-w-2xl mx-auto px-6">
            <h1 class="text-3xl sm:text-4xl font-extrabold mb-4 leading-tight">Portal Lapor Diri PPG<br>UIN Siber Syekh Nurjati Cirebon</h1>
            <p class="text-purple-200 text-sm sm:text-base mb-6 max-w-xl mx-auto">Sistem lapor diri mahasiswa baru berbasis single entry yang menghasilkan data siap-import ke PDDIKTI dan SIAKAD.</p>
            <a href="{{ route('login') }}" class="inline-block px-8 py-3.5 !bg-white !text-purple-950 font-extrabold rounded-xl text-sm shadow-2xl hover:bg-purple-50 hover:scale-105 active:scale-95 transition-all duration-200">Masuk ke Portal Lapor Diri →</a>
        </div>
    </div>
    @endif

    {{-- 2. UNIFIED HERO ACTION CARD (STATUS PERIODE & 5 LANGKAH LAPOR DIRI - FULL PURPLE CANVAS) --}}
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 pt-[416px] pb-4">
        <div class="rounded-3xl p-3 sm:p-4 text-white shadow-2xl shadow-purple-900/30 border border-purple-700/30 overflow-hidden" style="background: linear-gradient(135deg, rgba(30,27,75,0.98) 0%, rgba(42,21,86,0.96) 100%);">
            <!-- Glow Accents -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

            <!-- BAGIAN 1: STATUS PERIODE LAPOR DIRI -->
            @if($landingActivePeriod)
            <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-3 p-3 rounded-2xl bg-white/[0.08] dark:bg-white/[0.04] backdrop-blur-md border border-white/15 shadow-inner">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 border border-emerald-400/40 text-emerald-400 flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-500/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-400/20 text-emerald-300 border border-emerald-400/30">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 mr-1.5 animate-pulse"></span>
                                Pendaftaran Dibuka
                            </span>
                            <h3 class="font-extrabold text-white text-base sm:text-lg tracking-wide">{{ $landingActivePeriod->nama }}</h3>
                        </div>
                        <p class="text-xs sm:text-sm text-purple-200/90 mt-1 leading-relaxed">
                            Batas waktu lapor diri sampai <strong class="text-amber-300 font-bold">{{ $landingActivePeriod->tanggal_selesai->translatedFormat('d F Y, H:i') }} WIB</strong>. Pastikan seluruh dokumen persyaratan disiapkan sebelum mengisi formulir.
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 w-full sm:w-auto justify-end flex-shrink-0">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-extrabold text-xs sm:text-sm rounded-xl shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                        <span>Lapor Diri Sekarang</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
            @elseif($landingUpcomingPeriod)
            <div class="relative z-10 flex flex-col lg:flex-row items-start lg:items-center justify-between gap-3 p-3 rounded-2xl bg-white/[0.08] dark:bg-white/[0.04] backdrop-blur-md border border-white/15 shadow-inner">
                <div class="flex items-center gap-3.5 sm:gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-amber-500/20 border border-amber-400/40 text-amber-400 flex items-center justify-center flex-shrink-0 shadow-lg shadow-amber-500/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex flex-wrap items-center gap-2 sm:gap-2.5">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-400/20 text-amber-300 border border-amber-400/30">
                                Akan Dibuka
                            </span>
                            <h3 class="font-extrabold text-white text-base sm:text-lg tracking-wide">{{ $landingUpcomingPeriod->nama }}</h3>
                        </div>
                        <p class="text-xs sm:text-sm text-purple-200/90 mt-1 leading-relaxed">
                            Pendaftaran dibuka mulai <strong class="text-white font-semibold">{{ $landingUpcomingPeriod->tanggal_mulai->translatedFormat('d F Y, H:i') }} WIB</strong> s/d <strong class="text-white font-semibold">{{ $landingUpcomingPeriod->tanggal_selesai->translatedFormat('d F Y, H:i') }} WIB</strong>.
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 w-full sm:w-auto justify-end flex-shrink-0">
                    <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 bg-amber-500 hover:bg-amber-600 text-purple-950 font-extrabold text-xs sm:text-sm rounded-xl shadow-lg transition">
                        <span>Lihat Informasi</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
            @else
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 p-4 sm:p-5 rounded-2xl bg-white/[0.06] backdrop-blur-md border border-white/10 shadow-inner">
                <div class="flex items-center gap-3.5">
                    <div class="w-11 h-11 rounded-xl bg-purple-400/10 border border-purple-400/20 text-purple-300 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-white text-sm sm:text-base">Periode Lapor Diri Saat Ini Sedang Ditutup</h3>
                        <p class="text-xs text-purple-200/80 mt-0.5">
                            Pendaftaran lapor diri belum dibuka atau sudah berakhir. Silakan cek berkala informasi gelombang berikutnya.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- SEPARATOR ELEGAN -->
            <div class="relative z-10 my-2 flex items-center">
                <div class="flex-grow border-t border-white/10"></div>
                <span class="flex-shrink mx-4 text-purple-300/60 text-[10px] uppercase font-bold tracking-widest flex items-center gap-1.5">
                    <i class="fa-solid fa-arrow-down-long text-[9px] text-amber-400"></i> Alur Pelaksanaan Lapor Diri
                </span>
                <div class="flex-grow border-t border-white/10"></div>
            </div>

            <!-- BAGIAN 2: RINGKASAN 5 LANGKAH ALUR LAPOR DIRI -->
            <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-2 mb-2">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-400 text-purple-950 flex items-center justify-center font-black text-lg shadow-md">
                        <i class="fa-solid fa-route"></i>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-base sm:text-lg text-white">Ringkasan 5 Langkah Alur Lapor Diri</h3>
                        <p class="text-xs text-purple-200 mt-0.5">Tahapan penting yang harus dilalui oleh setiap calon mahasiswa PPG</p>
                    </div>
                </div>
                <button type="button" 
                        onclick="switchInfographicTab(0); document.getElementById('infographic-showcase-container').scrollIntoView({behavior: 'smooth'})" 
                        class="px-4 py-2 rounded-xl font-bold text-xs bg-white/10 hover:bg-white/20 text-white border border-white/20 backdrop-blur transition flex items-center gap-2 cursor-pointer shadow-sm">
                    <span>Lihat Panduan Lengkap</span>
                    <i class="fa-solid fa-arrow-down text-xs"></i>
                </button>
            </div>

            {{-- 5 Steps Interactive Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2 relative z-10">
                <div class="bg-white/10 dark:bg-slate-900/60 backdrop-blur p-3.5 rounded-2xl border border-white/10 hover:bg-white/20 hover:border-amber-400/40 hover:-translate-y-0.5 transition-all duration-200 group cursor-pointer" 
                     onclick="switchInfographicTab(0); document.getElementById('infographic-showcase-container').scrollIntoView({behavior: 'smooth'})">
                    <div class="flex items-center justify-between mb-1">
                        <span class="w-7 h-7 rounded-lg bg-amber-400 text-purple-950 font-black text-xs flex items-center justify-center shadow">1</span>
                        <i class="fa-solid fa-key text-xs text-purple-200 group-hover:scale-110 group-hover:text-amber-300 transition-all"></i>
                    </div>
                    <h4 class="font-bold text-xs text-white">Login Akun</h4>
                    <p class="text-[11px] text-purple-200 mt-1 leading-snug">Akses portal resmi SIMPKB dan masuk ke sistem PPG.</p>
                </div>

                <div class="bg-white/10 dark:bg-slate-900/60 backdrop-blur p-3.5 rounded-2xl border border-white/10 hover:bg-white/20 hover:border-amber-400/40 hover:-translate-y-0.5 transition-all duration-200 group cursor-pointer" 
                     onclick="switchInfographicTab(0); document.getElementById('infographic-showcase-container').scrollIntoView({behavior: 'smooth'})">
                    <div class="flex items-center justify-between mb-1">
                        <span class="w-7 h-7 rounded-lg bg-amber-400 text-purple-950 font-black text-xs flex items-center justify-center shadow">2</span>
                        <i class="fa-solid fa-user-pen text-xs text-purple-200 group-hover:scale-110 group-hover:text-amber-300 transition-all"></i>
                    </div>
                    <h4 class="font-bold text-xs text-white">Isi Biodata</h4>
                    <p class="text-[11px] text-purple-200 mt-1 leading-snug">Lengkapi identitas diri, pendidikan S1, dan data keluarga.</p>
                </div>

                <div class="bg-white/10 dark:bg-slate-900/60 backdrop-blur p-3.5 rounded-2xl border border-white/10 hover:bg-white/20 hover:border-amber-400/40 hover:-translate-y-0.5 transition-all duration-200 group cursor-pointer" 
                     onclick="switchInfographicTab(1); document.getElementById('infographic-showcase-container').scrollIntoView({behavior: 'smooth'})">
                    <div class="flex items-center justify-between mb-2">
                        <span class="w-7 h-7 rounded-lg bg-amber-400 text-purple-950 font-black text-xs flex items-center justify-center shadow">3</span>
                        <i class="fa-solid fa-file-arrow-up text-xs text-purple-200 group-hover:scale-110 group-hover:text-amber-300 transition-all"></i>
                    </div>
                    <h4 class="font-bold text-xs text-white">Unggah Berkas</h4>
                    <p class="text-[11px] text-purple-200 mt-1 leading-snug">Scan dan unggah berkas wajib (A1, Pakta, SKCK, Napza, Sehat).</p>
                </div>

                <div class="bg-white/10 dark:bg-slate-900/60 backdrop-blur p-3.5 rounded-2xl border border-white/10 hover:bg-white/20 hover:border-amber-400/40 hover:-translate-y-0.5 transition-all duration-200 group cursor-pointer" 
                     onclick="switchInfographicTab(0); document.getElementById('infographic-showcase-container').scrollIntoView({behavior: 'smooth'})">
                    <div class="flex items-center justify-between mb-1">
                        <span class="w-7 h-7 rounded-lg bg-amber-400 text-purple-950 font-black text-xs flex items-center justify-center shadow">4</span>
                        <i class="fa-solid fa-clipboard-check text-xs text-purple-200 group-hover:scale-110 group-hover:text-amber-300 transition-all"></i>
                    </div>
                    <h4 class="font-bold text-xs text-white">Verifikasi LPTK</h4>
                    <p class="text-[11px] text-purple-200 mt-1 leading-snug">Pemeriksaan dan validasi berkas oleh tim verifikator LPTK.</p>
                </div>

                <div class="bg-white/10 dark:bg-slate-900/60 backdrop-blur p-3.5 rounded-2xl border border-white/10 hover:bg-white/20 hover:border-amber-400/40 hover:-translate-y-0.5 transition-all duration-200 group cursor-pointer" 
                     onclick="switchInfographicTab(0); document.getElementById('infographic-showcase-container').scrollIntoView({behavior: 'smooth'})">
                    <div class="flex items-center justify-between mb-1">
                        <span class="w-7 h-7 rounded-lg bg-amber-400 text-purple-950 font-black text-xs flex items-center justify-center shadow">5</span>
                        <i class="fa-solid fa-print text-xs text-purple-200 group-hover:scale-110 group-hover:text-amber-300 transition-all"></i>
                    </div>
                    <h4 class="font-bold text-xs text-white">Cetak Bukti</h4>
                    <p class="text-[11px] text-purple-200 mt-1 leading-snug">Unduh formulir bukti lapor diri ber-barcode sah.</p>
                </div>
            </div>
        </div>
    </div>


</section>

{{-- ===== BAGIAN INFOGRAFIS & PANDUAN VISUAL PPG ===== --}}
<section id="section-infografis" class="relative pt-4 pb-14 sm:pb-16 transition-colors" style="background-color: #ffffff;">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        {{-- BAGIAN BAWAH: Spotlight Showcase Terbaru (Fokus 1 Infografis per Tab yang Lega & Bersih) --}}
        @if(isset($infographics) && $infographics->count() > 0)
        <div id="infographic-showcase-container" class="bg-white dark:bg-slate-900 rounded-3xl border border-purple-100/90 dark:border-slate-800 shadow-sm p-6 sm:p-8 relative">
            @foreach($infographics as $idx => $info)
            <div id="infographic-panel-{{ $idx }}" 
                 class="infographic-panel {{ $idx === 0 ? 'flex' : 'hidden' }} flex-col lg:flex-row items-center gap-8 lg:gap-12 animate-fadeIn">
                
                {{-- Left: Poster Display (Proporsional & Nyaman Dilihat) --}}
                <div class="w-full lg:w-5/12 flex flex-col items-center">
                    <div class="relative max-w-sm w-full rounded-2xl overflow-hidden shadow-md border border-purple-100 dark:border-slate-800 group cursor-pointer bg-slate-50 dark:bg-slate-950"
                         onclick="openInfographicModalByElement(this)"
                         data-id="{{ $info->id }}"
                         data-title="{{ $info->judul }}"
                         data-category-name="{{ $info->kategori }}"
                         data-desc="{{ $info->deskripsi }}"
                         data-image="{{ $info->image_url }}"
                         data-pdf="{{ $info->pdf_url }}">
                        
                        <img src="{{ $info->image_url }}" 
                             alt="{{ $info->judul }}" 
                             class="w-full h-auto object-cover max-h-[460px] group-hover:scale-105 transition-transform duration-500">
                        
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center p-4">
                            <span class="px-4 py-2.5 rounded-xl bg-white/95 text-purple-950 font-extrabold text-xs shadow-xl backdrop-blur flex items-center gap-2">
                                <i class="fa-solid fa-magnifying-glass-plus text-amber-500"></i> Buka Layar Penuh
                            </span>
                        </div>
                    </div>
                    <p class="text-[11px] text-gray-400 mt-2 flex items-center gap-1.5">
                        <i class="fa-solid fa-hand-pointer text-purple-500"></i> Klik poster untuk memperbesar / zoom
                    </p>
                </div>

                {{-- Right: Details & Action Buttons --}}
                <div class="w-full lg:w-7/12 flex flex-col justify-between self-stretch">
                    <div>
                        <div class="flex items-center justify-between flex-wrap gap-2 mb-4 pb-3 border-b border-purple-50 dark:border-slate-800">
                            <div class="inline-flex p-1 bg-purple-50/70 dark:bg-slate-800/80 rounded-2xl border border-purple-100 dark:border-slate-700 overflow-x-auto max-w-full">
                                @foreach($infographics as $tIdx => $tInfo)
                                @php
                                    $tLabel = match(true) {
                                        str_contains(strtolower($tInfo->kategori), 'alur') => 'Alur',
                                        str_contains(strtolower($tInfo->kategori), 'syarat') || str_contains(strtolower($tInfo->kategori), 'berkas') => 'Syarat Berkas',
                                        str_contains(strtolower($tInfo->kategori), 'foto') => 'Ketentuan Foto',
                                        str_contains(strtolower($tInfo->kategori), 'bantuan') => 'Bantuan',
                                        default => $tInfo->kategori,
                                    };
                                @endphp
                                <button type="button" 
                                        onclick="switchInfographicTab({{ $tIdx }})" 
                                        class="px-3 py-1 rounded-xl text-xs font-bold transition-all cursor-pointer whitespace-nowrap {{ $tIdx === $idx ? 'bg-purple-700 text-white shadow-sm' : 'text-gray-600 dark:text-slate-300 hover:text-purple-700 dark:hover:text-purple-300' }}">
                                    {{ $tLabel }}
                                </button>
                                @endforeach
                            </div>
                            <span class="text-xs font-semibold text-gray-400">
                                Panduan #{{ $idx + 1 }} dari {{ $infographics->count() }}
                            </span>
                        </div>

                        <h3 class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white leading-tight">
                            {{ $info->judul }}
                        </h3>

                        <div class="mt-4 p-4 rounded-2xl bg-purple-50/50 dark:bg-slate-800/60 border border-purple-100/70 dark:border-slate-700/60 text-xs sm:text-sm text-gray-600 dark:text-slate-300 leading-relaxed">
                            {{ $info->deskripsi }}
                        </div>

                        {{-- Panduan Poin Ringkas --}}
                        <div class="mt-5 space-y-2 text-xs text-gray-600 dark:text-slate-300">
                            @if(str_contains(strtolower($info->kategori), 'alur'))
                                <div class="flex items-start gap-2.5">
                                    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 text-xs"></i>
                                    <span>Ikuti seluruh 5 tahapan mulai dari login hingga unduh bukti verifikasi.</span>
                                </div>
                                <div class="flex items-start gap-2.5">
                                    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 text-xs"></i>
                                    <span>Pastikan data PDDIKTI dan SIAKAD sudah sinkron sebelum finalisasi.</span>
                                </div>
                            @elseif(str_contains(strtolower($info->kategori), 'syarat') || str_contains(strtolower($info->kategori), 'berkas'))
                                <div class="flex items-start gap-2.5">
                                    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 text-xs"></i>
                                    <span>Semua dokumen discan dalam format PDF/JPG yang jelas dan terbaca.</span>
                                </div>
                                <div class="flex items-start gap-2.5">
                                    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 text-xs"></i>
                                    <span>Pakta Integritas wajib dibubuhi meterai Rp 10.000 dan tanda tangan basah.</span>
                                </div>
                            @elseif(str_contains(strtolower($info->kategori), 'foto'))
                                <div class="flex items-start gap-2.5">
                                    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 text-xs"></i>
                                    <span>Wajib mengenakan jas hitam, kemeja putih berkerah, dan dasi (pria).</span>
                                </div>
                                <div class="flex items-start gap-2.5">
                                    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 text-xs"></i>
                                    <span>Latar belakang foto merah polos dengan rasio 4x6 cm resolusi tajam.</span>
                                </div>
                            @else
                                <div class="flex items-start gap-2.5">
                                    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5 text-xs"></i>
                                    <span>Hubungi tim customer service melalui Live Support atau Sistem Tiket Helpdesk.</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Actions & Navigation Footer --}}
                    <div class="mt-8 pt-6 border-t border-purple-100 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex flex-wrap items-center gap-2.5">
                            <button type="button" 
                                    onclick="openInfographicModalByElement(document.querySelector('#infographic-panel-{{ $idx }} [data-id]'))" 
                                    class="px-5 py-2.5 rounded-xl font-bold text-xs text-white bg-purple-700 hover:bg-purple-800 shadow-md shadow-purple-700/20 transition cursor-pointer flex items-center gap-2">
                                <i class="fa-solid fa-expand"></i> Buka Layar Penuh
                            </button>
                            <a href="{{ $info->image_url }}" download class="px-4 py-2.5 rounded-xl font-bold text-xs text-purple-700 dark:text-purple-300 bg-purple-50 dark:bg-purple-950/50 hover:bg-purple-100 dark:hover:bg-purple-900/50 border border-purple-200 dark:border-purple-800 transition flex items-center gap-2">
                                <i class="fa-solid fa-download"></i> Unduh Poster
                            </a>
                        </div>

                        {{-- Prev / Next Nav Buttons --}}
                        <div class="flex items-center gap-1.5 text-xs">
                            <button type="button" 
                                    onclick="switchInfographicTab({{ ($idx - 1 + $infographics->count()) % $infographics->count() }})" 
                                    class="p-2 rounded-xl border border-gray-200 dark:border-slate-700 hover:bg-purple-50 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300 transition"
                                    title="Panduan Sebelumnya">
                                <i class="fa-solid fa-chevron-left text-xs"></i>
                            </button>
                            <span class="px-2 text-xs font-bold text-gray-500">{{ $idx + 1 }} / {{ $infographics->count() }}</span>
                            <button type="button" 
                                    onclick="switchInfographicTab({{ ($idx + 1) % $infographics->count() }})" 
                                    class="p-2 rounded-xl border border-gray-200 dark:border-slate-700 hover:bg-purple-50 dark:hover:bg-slate-800 text-gray-600 dark:text-slate-300 transition"
                                    title="Panduan Berikutnya">
                                <i class="fa-solid fa-chevron-right text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
            @endforeach
        </div>
        @endif

    </div>
</section>

{{-- ===== MODAL LIGHTBOX INFOGRAFIS ===== --}}
<div id="modal-infographic-lightbox" class="hidden fixed inset-0 z-[99999] flex items-center justify-center p-3 sm:p-6 bg-black/85 backdrop-blur-md animate-fadeIn" role="dialog" aria-modal="true">
    <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-4xl w-full max-h-[92vh] flex flex-col md:flex-row overflow-hidden shadow-2xl border border-purple-200/50 dark:border-slate-800 relative">
        
        {{-- Close Button --}}
        <button type="button" onclick="closeInfographicModal()" class="absolute top-3 right-3 z-30 w-8 h-8 rounded-full bg-black/50 hover:bg-black/80 text-white flex items-center justify-center transition cursor-pointer">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        {{-- Left: Full Image with Zoom container --}}
        <div class="md:w-3/5 bg-slate-950 flex items-center justify-center overflow-auto p-4 relative group max-h-[50vh] md:max-h-full">
            <img id="lightbox-img" src="" alt="Preview Infografis" class="max-h-[82vh] w-auto object-contain rounded-xl shadow-lg transition-transform duration-300 cursor-zoom-in" onclick="toggleLightboxZoom(this)">
            <span class="absolute bottom-3 left-3 px-2.5 py-1 rounded-lg bg-black/60 text-white/90 text-[10px] font-semibold backdrop-blur pointer-events-none">
                <i class="fa-solid fa-magnifying-glass mr-1"></i> Klik gambar untuk perbesar
            </span>
        </div>

        {{-- Right: Details Sidebar --}}
        <div class="md:w-2/5 p-6 flex flex-col justify-between bg-white dark:bg-slate-900 border-t md:border-t-0 md:border-l border-purple-100 dark:border-slate-800 overflow-y-auto">
            <div>
                <span id="lightbox-category" class="inline-block px-3 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-purple-100 dark:bg-purple-900/60 text-purple-700 dark:text-purple-300 mb-2.5">
                    Kategori
                </span>
                <h3 id="lightbox-title" class="text-lg sm:text-xl font-extrabold text-gray-900 dark:text-white leading-tight">
                    Judul Infografis
                </h3>
                <div class="mt-4 pt-4 border-t border-purple-100 dark:border-slate-800">
                    <h5 class="text-xs font-bold uppercase tracking-wider text-purple-800 dark:text-purple-300 mb-2">Penjelasan & Catatan</h5>
                    <p id="lightbox-desc" class="text-xs text-gray-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                        Deskripsi infografis...
                    </p>
                </div>
            </div>

            <div class="mt-6 pt-4 border-t border-purple-100 dark:border-slate-800 flex flex-col gap-2">
                <a id="lightbox-download-btn" href="#" download class="w-full py-3 px-4 rounded-xl font-bold text-xs text-white bg-gradient-to-r from-purple-700 to-indigo-700 hover:from-purple-600 hover:to-indigo-600 shadow-md shadow-purple-700/20 text-center flex items-center justify-center gap-2 transition">
                    <i class="fa-solid fa-download"></i> Unduh Poster Resolusi Penuh
                </a>
                <button type="button" onclick="closeInfographicModal()" class="w-full py-2.5 rounded-xl font-semibold text-xs text-gray-500 hover:bg-gray-100 dark:hover:bg-slate-800 transition cursor-pointer">
                    Tutup Tampilan
                </button>
            </div>
        </div>
    </div>
</div>



{{-- ===== BERITA TERBARU ===== --}}
<section class="max-w-7xl mx-auto px-6 pb-6">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h2 class="text-2xl font-extrabold text-gray-800">Berita & Kegiatan</h2>
            <p class="text-gray-500 text-sm mt-1">Informasi terbaru seputar PPG UIN Siber Syekh Nurjati Cirebon</p>
        </div>
        <a href="{{ route('public.berita') }}" class="text-sm text-purple-600 font-semibold hover:text-purple-800 flex items-center space-x-1">
            <span>Lihat Semua</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </a>
    </div>

    @if($articles->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($articles as $article)
        <a href="{{ route('public.berita.show', $article->slug) }}" class="article-card bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 block">
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
    @else
    <div class="text-center py-16 text-gray-400">
        <svg class="w-16 h-16 mx-auto mb-4 text-purple-100" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
        <p class="font-medium">Belum ada artikel yang dipublikasikan.</p>
    </div>
    @endif
</section>

{{-- ===== TESTIMONI ===== --}}
@if($testimonials->count() > 0)
<section class="pt-8 sm:pt-12 pb-16 sm:pb-24 bg-gradient-to-b from-white to-purple-50/50 dark:from-slate-900 dark:to-slate-900/80">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-14">
            <span class="inline-block py-1 px-3 rounded-full bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300 font-semibold tracking-widest uppercase text-[10px] mb-3">Suara Mereka</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-800 dark:text-white mb-3 leading-tight">Testimoni Alumni & Peserta</h2>
            <p class="text-gray-500 dark:text-slate-400 text-[13px] leading-relaxed">Dengarkan langsung pengalaman berharga dari para guru hebat yang telah mengikuti program Pendidikan Profesi Guru (PPG) di UIN Siber Syekh Nurjati Cirebon.</p>
        </div>

        @php
            $videoTesti = $testimonials->where('type', 'video')->first();
            $textTestis = $testimonials->where('type', 'text')->take(2);
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            {{-- Video Testimoni (Large) --}}
            @if($videoTesti)
            <div class="lg:col-span-2 relative rounded-[2rem] overflow-hidden shadow-2xl shadow-purple-900/10 border border-purple-100/50 dark:border-slate-800 group bg-slate-100 dark:bg-slate-800">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent z-10 pointer-events-none"></div>
                
                @if($videoTesti->avatar)
                    <img src="{{ asset('storage/'.$videoTesti->avatar) }}" alt="{{ $videoTesti->name }}" class="w-full h-[320px] sm:h-[420px] object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                @else
                    <img src="https://images.unsplash.com/photo-1577896851231-70ef18881754?q=80&w=1470&auto=format&fit=crop" alt="Video Testimoni" class="w-full h-[320px] sm:h-[420px] object-cover group-hover:scale-105 transition-transform duration-700 ease-out">
                @endif
                
                {{-- Play Button Glow --}}
                <div class="absolute inset-0 z-20 flex items-center justify-center">
                    <a href="{{ $videoTesti->media_url }}" target="_blank" class="relative group cursor-pointer block">
                        <div class="absolute inset-0 bg-purple-600 rounded-full blur-xl opacity-40 group-hover:opacity-70 transition-opacity duration-300 animate-pulse"></div>
                        <button class="relative w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center border border-white/40 text-white group-hover:bg-white group-hover:text-purple-700 group-hover:scale-110 transition-all duration-300 shadow-[0_0_40px_rgba(255,255,255,0.2)]">
                            <i class="fa-solid fa-play text-xl sm:text-2xl ml-1.5"></i>
                        </button>
                    </a>
                </div>
                
                <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8 z-20">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="px-3 py-1 bg-purple-600 text-white text-[9px] font-semibold uppercase rounded-full tracking-wider shadow-sm">Cerita Inspiratif</span>
                        <span class="flex items-center gap-1 text-amber-400 text-xs">
                            @for($i=0; $i<$videoTesti->rating; $i++) <i class="fa-solid fa-star"></i> @endfor
                        </span>
                    </div>
                    <h3 class="text-lg sm:text-xl font-semibold text-white/95 leading-tight mb-2">"{{ $videoTesti->content ?? 'Sistem pembelajaran jarak jauh sangat fleksibel, responsif, dan sangat membantu guru di pelosok daerah.' }}"</h3>
                    <div class="flex items-center gap-3 mt-4">
                        <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center text-white text-sm font-semibold backdrop-blur-sm border border-white/30">
                            {{ strtoupper(substr($videoTesti->name, 0, 2)) }}
                        </div>
                        <div>
                            <p class="text-white text-[13px] font-semibold">{{ $videoTesti->name }}</p>
                            <p class="text-gray-300 text-[11px]">{{ $videoTesti->subtitle }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Text Testimonis (Column) --}}
            @if($textTestis->count() > 0)
            <div class="flex flex-col gap-6 sm:gap-8 justify-between lg:col-span-1 {{ !$videoTesti ? 'lg:col-span-3 grid grid-cols-1 md:grid-cols-2' : '' }}">
                @foreach($textTestis as $index => $textTesti)
                    @if($index % 2 == 0)
                        {{-- Card 1 (Light) --}}
                        <div class="bg-white dark:bg-slate-800 p-6 sm:p-8 rounded-[2rem] border border-purple-100/50 dark:border-slate-700 shadow-xl shadow-purple-900/5 relative group hover:-translate-y-1 transition-transform duration-300 h-full flex flex-col justify-between">
                            <div class="absolute top-6 right-6 text-purple-50 dark:text-slate-700/50 group-hover:text-purple-100 dark:group-hover:text-slate-700 transition-colors">
                                <i class="fa-solid fa-quote-right text-4xl"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-3 mb-4 relative z-10">
                                    @if($textTesti->avatar)
                                        <img src="{{ asset('storage/'.$textTesti->avatar) }}" alt="{{ $textTesti->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-purple-100 dark:border-slate-700 p-0.5">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center font-bold">{{ strtoupper(substr($textTesti->name, 0, 1)) }}</div>
                                    @endif
                                    <div>
                                        <h4 class="text-[13px] font-semibold text-gray-800 dark:text-white">{{ $textTesti->name }}</h4>
                                        <p class="text-[10px] text-gray-500 dark:text-slate-400">{{ $textTesti->subtitle }}</p>
                                    </div>
                                </div>
                                <p class="text-[13px] text-gray-500 dark:text-slate-300 leading-relaxed relative z-10 italic">
                                    "{{ $textTesti->content }}"
                                </p>
                            </div>
                            <div class="flex gap-1 text-amber-400 text-[9px] mt-4 relative z-10">
                                @for($i=0; $i<$textTesti->rating; $i++) <i class="fa-solid fa-star"></i> @endfor
                            </div>
                        </div>
                    @else
                        {{-- Card 2 (Dark Gradient) --}}
                        <div class="bg-gradient-to-br from-purple-700 to-indigo-800 p-6 sm:p-8 rounded-[2rem] shadow-xl shadow-purple-900/20 text-white relative overflow-hidden group hover:-translate-y-1 transition-transform duration-300 h-full flex flex-col justify-between">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10 pointer-events-none group-hover:bg-white/20 transition-colors duration-500"></div>
                            <div>
                                <div class="flex items-center gap-3 mb-4 relative z-10">
                                    @if($textTesti->avatar)
                                        <img src="{{ asset('storage/'.$textTesti->avatar) }}" alt="{{ $textTesti->name }}" class="w-10 h-10 rounded-full object-cover border-2 border-white/20 p-0.5">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-white/20 text-white flex items-center justify-center font-bold">{{ strtoupper(substr($textTesti->name, 0, 1)) }}</div>
                                    @endif
                                    <div>
                                        <h4 class="text-[13px] font-semibold">{{ $textTesti->name }}</h4>
                                        <p class="text-[10px] text-purple-200">{{ $textTesti->subtitle }}</p>
                                    </div>
                                </div>
                                <p class="text-[13px] text-purple-100/90 leading-relaxed relative z-10 italic">
                                    "{{ $textTesti->content }}"
                                </p>
                            </div>
                            <div class="flex gap-1 text-amber-300 text-[9px] mt-4 relative z-10">
                                @for($i=0; $i<$textTesti->rating; $i++) <i class="fa-solid fa-star"></i> @endfor
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- ===== MITRA KERJA (MARQUEE) ===== --}}
<style>
    @keyframes marquee {
        0% { transform: translateX(0%); }
        100% { transform: translateX(-50%); }
    }
    .animate-marquee {
        display: flex;
        width: max-content;
        animation: marquee 30s linear infinite;
    }
    .animate-marquee:hover {
        animation-play-state: paused;
    }
</style>
@if($partners->count() > 0)
<section class="py-10 bg-white dark:bg-slate-900 border-t border-purple-50 dark:border-slate-800 overflow-hidden relative">
    {{-- Gradient mask for smooth fade effect at the edges --}}
    <div class="absolute inset-y-0 left-0 w-16 md:w-40 bg-gradient-to-r from-white to-transparent dark:from-slate-900 z-10 pointer-events-none"></div>
    <div class="absolute inset-y-0 right-0 w-16 md:w-40 bg-gradient-to-l from-white to-transparent dark:from-slate-900 z-10 pointer-events-none"></div>
    
    <div class="max-w-7xl mx-auto px-4 mb-6 text-center">
        <h3 class="text-[11px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-[0.25em]">Bekerjasama & Didukung Oleh Mitra Terpercaya</h3>
    </div>

    <div class="relative flex overflow-hidden">
        {{-- Two identical blocks for seamless infinite scroll --}}
        <div class="animate-marquee flex items-center gap-16 sm:gap-24 px-8">
            {{-- Set 1 --}}
            @foreach($partners as $partner)
            <a {!! $partner->url ? 'href="'.$partner->url.'" target="_blank"' : '' !!} class="flex flex-col sm:flex-row items-center justify-center gap-3 grayscale hover:grayscale-0 opacity-50 hover:opacity-100 transition-all duration-300 cursor-pointer">
                @if($partner->logo)
                    <img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}" class="h-10 sm:h-12 w-auto object-contain">
                @endif
                <span class="font-bold text-gray-700 dark:text-slate-300 text-sm hidden sm:block">{{ $partner->name }}</span>
            </a>
            @endforeach
            
            {{-- Set 2 (Duplikat) --}}
            @foreach($partners as $partner)
            <a {!! $partner->url ? 'href="'.$partner->url.'" target="_blank"' : '' !!} class="flex flex-col sm:flex-row items-center justify-center gap-3 grayscale hover:grayscale-0 opacity-50 hover:opacity-100 transition-all duration-300 cursor-pointer">
                @if($partner->logo)
                    <img src="{{ asset('storage/'.$partner->logo) }}" alt="{{ $partner->name }}" class="h-10 sm:h-12 w-auto object-contain">
                @endif
                <span class="font-bold text-gray-700 dark:text-slate-300 text-sm hidden sm:block">{{ $partner->name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@push('scripts')
<script>
    let current = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('[id^="dot-"]');
    const total = slides.length;

    function goToSlide(n) {
        slides[current].classList.remove('active');
        dots[current].classList.remove('w-6', 'bg-white');
        dots[current].classList.add('bg-white/40');
        current = (n + total) % total;
        slides[current].classList.add('active');
        dots[current].classList.add('w-6', 'bg-white');
        dots[current].classList.remove('bg-white/40');
    }
    function nextSlide() { goToSlide(current + 1); }
    function prevSlide() { goToSlide(current - 1); }

    // Auto-play every 5s
    if (total > 1) setInterval(nextSlide, 5000);

    // =========================================================
    // Infographics Interactive Tab Switcher & Modal Lightbox Engine
    // =========================================================
    function switchInfographicTab(idx) {
        const panels = document.querySelectorAll('.infographic-panel');
        const tabs = document.querySelectorAll('.infographic-nav-tab');

        panels.forEach((panel, i) => {
            if (i === idx) {
                panel.classList.remove('hidden');
                panel.classList.add('flex');
            } else {
                panel.classList.add('hidden');
                panel.classList.remove('flex');
            }
        });

        tabs.forEach((tab, i) => {
            if (i === idx) {
                tab.className = 'infographic-nav-tab px-3.5 py-1.5 rounded-xl text-xs transition-all duration-200 cursor-pointer whitespace-nowrap bg-purple-700 text-white font-bold shadow-sm';
            } else {
                tab.className = 'infographic-nav-tab px-3.5 py-1.5 rounded-xl text-xs transition-all duration-200 cursor-pointer whitespace-nowrap font-semibold text-gray-600 dark:text-slate-300 hover:text-purple-700 dark:hover:text-purple-300';
            }
        });
    }

    function openInfographicModal(data) {
        const modal = document.getElementById('modal-infographic-lightbox');
        const img = document.getElementById('lightbox-img');
        const title = document.getElementById('lightbox-title');
        const category = document.getElementById('lightbox-category');
        const desc = document.getElementById('lightbox-desc');
        const downloadBtn = document.getElementById('lightbox-download-btn');

        if (!modal || !img) return;

        img.src = data.gambar;
        img.alt = data.judul;
        img.classList.remove('scale-150');
        img.classList.add('cursor-zoom-in');
        img.classList.remove('cursor-zoom-out');

        title.textContent = data.judul;
        category.textContent = data.kategori;
        desc.textContent = data.deskripsi || 'Tidak ada deskripsi.';

        downloadBtn.href = data.gambar;
        downloadBtn.setAttribute('download', (data.judul || 'infografis-ppg') + '.jpg');

        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function openInfographicModalByElement(el) {
        const data = {
            id: el.getAttribute('data-id'),
            judul: el.getAttribute('data-title'),
            kategori: el.getAttribute('data-category-name'),
            deskripsi: el.getAttribute('data-desc'),
            gambar: el.getAttribute('data-image'),
            pdf_url: el.getAttribute('data-pdf'),
        };
        openInfographicModal(data);
    }

    function openInfographicModalByTitle(searchTitle) {
        const cards = Array.from(document.querySelectorAll('.infographic-card'));
        const card = cards.find(c => {
            const t = c.getAttribute('data-title');
            return t && t.toLowerCase().includes(searchTitle.toLowerCase());
        });
        if (card) {
            openInfographicModalByElement(card);
        }
    }

    function closeInfographicModal() {
        const modal = document.getElementById('modal-infographic-lightbox');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    }

    function toggleLightboxZoom(img) {
        const isZoomed = img.classList.contains('scale-150');
        if (isZoomed) {
            img.classList.remove('scale-150');
            img.classList.remove('cursor-zoom-out');
            img.classList.add('cursor-zoom-in');
        } else {
            img.classList.add('scale-150');
            img.classList.remove('cursor-zoom-in');
            img.classList.add('cursor-zoom-out');
        }
    }

    // Escape listener to close lightbox
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeInfographicModal();
        }
    });

    // Close on background click
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('modal-infographic-lightbox');
        if (modal && !modal.classList.contains('hidden')) {
            if (e.target === modal) {
                closeInfographicModal();
            }
        }
    });
</script>
@endpush
@endsection
