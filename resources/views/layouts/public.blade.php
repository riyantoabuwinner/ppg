<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Models\AppSetting::get('app_name', 'PPG') . ' — ' . \App\Models\AppSetting::get('app_subtitle', 'UIN Siber Syekh Nurjati Cirebon'))</title>
    <meta name="description" content="@yield('meta-description', 'Portal resmi Program Pendidikan Profesi Guru (PPG) ' . \App\Models\AppSetting::get('app_subtitle', 'UIN Siber Syekh Nurjati Cirebon'))">
    @php $favicon = \App\Models\AppSetting::get('app_favicon'); @endphp
    @if($favicon)
    <link rel="icon" type="image/png" href="{{ asset('storage/'.$favicon) }}">
    @endif
    <script>
        // Inisialisasi tema instan sebelum render untuk mencegah FOUC
        (function() {
            const savedTheme = localStorage.getItem('ppg_theme');
            const systemPrefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            // Inisialisasi instan preferensi aksesibilitas WCAG 2.1
            const fontSize = localStorage.getItem('app_wcag_font_size');
            if (fontSize && fontSize !== 'normal') {
                document.documentElement.classList.add('font-size-' + fontSize);
            }
            if (localStorage.getItem('app_wcag_contrast') === '1') {
                document.documentElement.classList.add('high-contrast');
            }
            if (localStorage.getItem('app_wcag_dyslexia') === '1') {
                document.documentElement.classList.add('dyslexia-font');
            }
            if (localStorage.getItem('app_wcag_underline') === '1') {
                document.documentElement.classList.add('underline-links');
            }
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#7C3AED', light: '#A78BFA', dark: '#5B21B6' }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 & ResponsiveVoice.js (Aksesibilitas WCAG 2.1 Level AAA) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.responsivevoice.org/responsivevoice.js?key=FREE" defer></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .nav-link { transition: color 0.2s; }
        .nav-link:hover { color: #A78BFA; }
        /* Slider */
        .slide { display: none; animation: fadeIn 0.6s ease; }
        .slide.active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: scale(1.02); } to { opacity: 1; transform: scale(1); } }
        /* Card hover */
        .article-card { transition: transform 0.2s, box-shadow 0.2s; }
        .article-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(124,58,237,0.15); }

        /* Dropdown Navigation Menu */
        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(8px);
            transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s;
            pointer-events: none;
        }
        .dropdown-group:hover > .dropdown-menu,
        .dropdown-group:focus-within > .dropdown-menu,
        .dropdown-group.is-open > .dropdown-menu {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateY(0) !important;
            pointer-events: auto !important;
        }
        .dropdown-group:hover .dropdown-chevron,
        .dropdown-group.is-open .dropdown-chevron {
            transform: rotate(180deg);
            color: #7C3AED;
        }
        .dropdown-submenu {
            opacity: 0;
            visibility: hidden;
            transform: translateX(-8px);
            transition: all 0.2s ease;
            pointer-events: none;
        }
        .dropdown-subgroup:hover > .dropdown-submenu {
            opacity: 1 !important;
            visibility: visible !important;
            transform: translateX(0) !important;
            pointer-events: auto !important;
        }

        /* =========================================================
           SEMBUNYIKAN TOTAL TOOLBAR / BANNER / OPSI GOOGLE TRANSLATE
           ========================================================= */
        .goog-te-banner-frame,
        .goog-te-banner-frame.skiptranslate,
        iframe.goog-te-banner-frame,
        iframe.skiptranslate,
        .skiptranslate iframe,
        body > .skiptranslate,
        body > div[class*="skiptranslate"],
        div[id*="goog-gt-"],
        div[id*=":1."],
        div[id*=":2."],
        iframe[id^=":"],
        #goog-gt-tt,
        .goog-te-balloon-frame,
        .goog-tooltip,
        .goog-tooltip:hover,
        .goog-logo-link,
        .goog-te-gadget,
        [class*="VIpgJd"],
        img[src*="google.com/images/cleardot.gif"],
        img[src*="gstatic.com/images/branding/googlelogo"] {
            display: none !important;
            visibility: hidden !important;
            height: 0px !important;
            max-height: 0px !important;
            width: 0px !important;
            opacity: 0 !important;
            pointer-events: none !important;
            border: none !important;
            z-index: -99999 !important;
        }

        body {
            top: 0px !important;
            position: static !important;
            margin-top: 0px !important;
            padding-top: 0px !important;
        }

        .goog-text-highlight {
            background: transparent !important;
            box-shadow: none !important;
        }

        font[style] {
            background-color: transparent !important;
            box-shadow: none !important;
        }

        /* =========================================================
           DARK MODE GLOBAL STYLES
           ========================================================= */
        html.dark {
            color-scheme: dark;
        }
        html.dark body {
            background-color: #0b0f19 !important;
            color: #f1f5f9 !important;
        }
        html.dark .top-utility-bar {
            background-color: #060911 !important;
            border-color: #1e293b !important;
            color: #cbd5e1 !important;
        }
        html.dark nav.navbar-main {
            background-color: rgba(11, 15, 25, 0.95) !important;
            border-color: #1e293b !important;
        }
        html.dark .nav-brand-title {
            color: #f8fafc !important;
        }
        html.dark .nav-brand-subtitle {
            color: #a78bfa !important;
        }
        html.dark .nav-link {
            color: #94a3b8 !important;
        }
        html.dark .nav-link:hover {
            color: #c4b5fd !important;
        }
        html.dark .bg-white {
            background-color: #111827 !important;
        }
        html.dark .bg-gray-50 {
            background-color: #0b0f19 !important;
        }
        html.dark .bg-purple-50 {
            background-color: #1e1b4b !important;
        }
        html.dark .text-gray-800,
        html.dark .text-gray-900 {
            color: #f8fafc !important;
        }
        html.dark .text-gray-700 {
            color: #e2e8f0 !important;
        }
        html.dark .text-gray-600,
        html.dark .text-gray-500 {
            color: #94a3b8 !important;
        }
        html.dark .border-gray-100,
        html.dark .border-gray-200,
        html.dark .border-purple-100,
        html.dark .border-purple-200 {
            border-color: #1e293b !important;
        }
        html.dark .article-card {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
        }
        html.dark .article-card:hover {
            box-shadow: 0 12px 30px rgba(139, 92, 246, 0.25) !important;
        }
        html.dark #langDropdown {
            background-color: #111827 !important;
            border-color: #1f2937 !important;
        }
        html.dark #langDropdown button {
            color: #e2e8f0 !important;
        }
        html.dark #langDropdown button:hover {
            background-color: #1f2937 !important;
        }
        html.dark .prose {
            color: #cbd5e1 !important;
        }
        html.dark .prose h1, html.dark .prose h2, html.dark .prose h3, html.dark .prose h4 {
            color: #f8fafc !important;
        }
        html.dark .prose strong {
            color: #f8fafc !important;
        }

        /* =========================================================
           WCAG 2.1 (LEVEL AAA) ACCESSIBILITY TOKENS
           ========================================================= */
        /* WCAG 2.1 Text Resizing Tokens */
        html.font-size-md { font-size: 112% !important; }
        html.font-size-lg { font-size: 125% !important; }
        html.font-size-xl { font-size: 140% !important; }

        /* WCAG AAA High Contrast Theme (Standar Aksesibilitas WCAG 2.1 Level AAA) */
        html.high-contrast {
            filter: invert(90%) hue-rotate(180deg) contrast(150%) !important;
            background-color: #000000 !important;
        }
        html.high-contrast img,
        html.high-contrast video {
            filter: invert(100%) hue-rotate(180deg) contrast(110%) !important;
        }
        html.high-contrast #wcag-widget-container,
        html.high-contrast #help-center-widget,
        html.high-contrast #modal-help-login-required,
        html.high-contrast #modal-help-offline {
            filter: invert(100%) hue-rotate(180deg) !important;
        }
        html.high-contrast a {
            text-decoration: underline !important;
            text-underline-offset: 3px !important;
        }

        /* Dyslexia-Friendly Readable Font */
        html.dyslexia-font,
        html.dyslexia-font body,
        html.dyslexia-font *:not(i):not([class*="fa-"]):not([class*="fas"]):not([class*="far"]) {
            font-family: 'OpenDyslexic', 'Comic Sans MS', 'Trebuchet MS', Arial, sans-serif !important;
            letter-spacing: 0.05em !important;
            word-spacing: 0.12em !important;
            line-height: 1.85 !important;
        }

        /* Distinct Hyperlink Underline (Bantuan Buta Warna) */
        html.underline-links a,
        html.underline-links a * {
            text-decoration: underline !important;
            text-underline-offset: 4px !important;
            text-decoration-thickness: 2px !important;
        }
    </style>
    @livewireStyles
</head>
<body class="bg-white text-gray-800 transition-colors duration-200">

<!-- Accessibility Skip Link (WCAG 2.1 Level AAA) -->
<a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[99999] focus:px-5 focus:py-3 focus:bg-amber-400 focus:text-slate-950 focus:font-black focus:text-sm focus:rounded-xl focus:shadow-2xl focus:ring-4 focus:ring-amber-500 focus:outline-none">
    Lewati ke Konten Utama (Skip to Content)
</a>

<!-- Header Landmark (Banner) -->
<header role="banner">
{{-- ===== TOP UTILITY BAR (DI ATAS MENU NAVBAR) ===== --}}
<div class="top-utility-bar bg-purple-50/70 dark:bg-slate-950 border-b border-purple-100 dark:border-slate-800 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-1.5 flex items-center justify-between gap-3">
        
        {{-- SISI KIRI: TAUTAN INSTITUSI (DARI TOP MENU / DEFAULT) --}}
        <div class="flex items-center space-x-2.5 sm:space-x-3.5 text-xs font-semibold">
            @if(isset($topMenuItems) && $topMenuItems->isNotEmpty())
                @foreach($topMenuItems as $idx => $tItem)
                    <a href="{{ $tItem->getUrl() }}" 
                       target="{{ $tItem->target }}" 
                       rel="noopener noreferrer" 
                       class="inline-flex items-center space-x-1.5 px-3.5 py-1 rounded-full bg-purple-600 text-white font-bold tracking-wide shadow hover:bg-purple-700 hover:shadow-md transition-all cursor-pointer"
                       title="{{ $tItem->title }}">
                        @if($tItem->icon)
                            <i class="{{ $tItem->icon }} text-[11px] text-purple-200"></i>
                        @endif
                        <span>{{ $tItem->title }}</span>
                        @if($tItem->target === '_blank')
                            <svg class="w-3 h-3 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        @endif
                    </a>
                @endforeach
            @else
                <a href="https://uinssc.ac.id" target="_blank" rel="noopener noreferrer" 
                   class="inline-flex items-center space-x-1.5 px-3.5 py-1 rounded-full bg-purple-600 text-white font-bold tracking-wide shadow hover:bg-purple-700 hover:shadow-md transition-all cursor-pointer"
                   title="Buka Website Resmi UIN Siber Syekh Nurjati Cirebon (Tab Baru)">
                    <i class="fa-solid fa-building-columns text-[11px] text-purple-200"></i>
                    <span>UINSSC</span>
                    <svg class="w-3 h-3 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                <a href="https://ppid.uinssc.ac.id" target="_blank" rel="noopener noreferrer" 
                   class="inline-flex items-center space-x-1.5 px-3.5 py-1 rounded-full bg-purple-600 text-white font-bold tracking-wide shadow hover:bg-purple-700 hover:shadow-md transition-all cursor-pointer"
                   title="Buka PPID UIN Siber Syekh Nurjati Cirebon (Tab Baru)">
                    <i class="fa-solid fa-shield-halved text-[11px] text-purple-200"></i>
                    <span>PPID UINSSC</span>
                    <svg class="w-3 h-3 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
            @endif
        </div>

        {{-- SISI KANAN: FITUR GELAP/TERANG, PILIHAN BAHASA & FITUR SEARCH --}}
        <div class="flex items-center space-x-2 sm:space-x-3">
            
            {{-- Fitur Gelap dan Terang (Di Sebelah Kiri Pilihan Bahasa) --}}
            <button id="themeToggleBtn" type="button" onclick="toggleTheme()"
                    class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border transition shadow-sm bg-white border-purple-200 text-purple-900 hover:bg-purple-100/70 dark:bg-slate-800 dark:border-slate-700 dark:text-amber-300 dark:hover:bg-slate-700 cursor-pointer"
                    title="Alihkan Mode Gelap / Terang">
                <span id="themeIcon" class="text-sm leading-none">🌙</span>
                <span id="themeLabel" class="leading-none hidden sm:inline">Mode Gelap</span>
            </button>

            {{-- Pilihan Bahasa (Google Translate ID, EN, AR) --}}
            <div class="relative" id="googleTranslateDropdownContainer">
                <button id="langMenuBtn" type="button" 
                        class="flex items-center space-x-1.5 px-2.5 py-1 rounded-xl border border-purple-200 dark:border-slate-700 text-xs font-semibold text-gray-700 dark:text-slate-200 bg-white dark:bg-slate-800 hover:bg-purple-50 dark:hover:bg-slate-700 transition shadow-sm cursor-pointer"
                        aria-haspopup="true" aria-expanded="false" title="Pilih Bahasa / Language / اللغة">
                    <svg class="w-3.5 h-3.5 text-purple-600 dark:text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                    <span id="activeLangLabel" class="uppercase font-bold text-purple-900 dark:text-purple-300">ID</span>
                    <svg class="w-3 h-3 text-gray-400 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </button>

                {{-- Dropdown Menu --}}
                <div id="langDropdown" class="hidden absolute right-0 mt-1.5 w-44 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-purple-100 dark:border-slate-700 py-1.5 z-50 text-xs">
                    <button type="button" onclick="translateLanguage('id')" class="w-full text-left px-3.5 py-2 flex items-center justify-between hover:bg-purple-50 dark:hover:bg-slate-700 transition text-gray-700 dark:text-slate-200 cursor-pointer" data-lang="id">
                        <span class="flex items-center space-x-2">
                            <span class="text-base leading-none">🇮🇩</span>
                            <span class="font-medium">Indonesia</span>
                        </span>
                        <span class="check-icon text-purple-600 dark:text-purple-400 font-bold" id="check-id">✓</span>
                    </button>
                    <button type="button" onclick="translateLanguage('en')" class="w-full text-left px-3.5 py-2 flex items-center justify-between hover:bg-purple-50 dark:hover:bg-slate-700 transition text-gray-700 dark:text-slate-200 cursor-pointer" data-lang="en">
                        <span class="flex items-center space-x-2">
                            <span class="text-base leading-none">🇬🇧</span>
                            <span class="font-medium">English</span>
                        </span>
                        <span class="check-icon text-purple-600 dark:text-purple-400 font-bold hidden" id="check-en">✓</span>
                    </button>
                    <button type="button" onclick="translateLanguage('ar')" class="w-full text-left px-3.5 py-2 flex items-center justify-between hover:bg-purple-50 dark:hover:bg-slate-700 transition text-gray-700 dark:text-slate-200 cursor-pointer" data-lang="ar">
                        <span class="flex items-center space-x-2">
                            <span class="text-base leading-none">🇸🇦</span>
                            <span class="font-medium">العربية</span>
                        </span>
                        <span class="check-icon text-purple-600 dark:text-purple-400 font-bold hidden" id="check-ar">✓</span>
                    </button>
                </div>
            </div>

            {{-- Fitur Search (Sebelah Kanan Pilihan Bahasa) --}}
            <div class="relative">
                <form action="{{ route('public.berita') }}" method="GET" class="relative flex items-center">
                    <input type="text" 
                           name="q" 
                           value="{{ request('q') }}" 
                           placeholder="Cari berita..." 
                           class="w-32 sm:w-44 md:w-56 focus:w-64 transition-all duration-300 pl-7 pr-6 py-1 text-xs rounded-xl border border-purple-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-400 focus:bg-white focus:border-purple-500 focus:ring-1 focus:ring-purple-300 outline-none">
                    <button type="submit" class="absolute left-2 top-1/2 -translate-y-1/2 text-purple-500 dark:text-purple-400 hover:text-purple-700 transition cursor-pointer" title="Cari">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                    @if(request('q'))
                    <a href="{{ route('public.berita') }}" class="absolute right-1.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-white text-xs font-bold leading-none p-1" title="Hapus pencarian">
                        &times;
                    </a>
                    @endif
                </form>
            </div>
        </div>

    </div>
</div>

{{-- ===== NAVBAR UTAMA (MENU) ===== --}}
<nav role="navigation" class="navbar-main bg-white shadow-sm sticky top-0 z-40 border-b border-purple-100 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <a href="{{ route('landing') }}" class="flex items-center space-x-3 flex-shrink-0">
                @php 
                    $logoLight = \App\Models\AppSetting::get('app_logo_light'); 
                    $logoDark  = \App\Models\AppSetting::get('app_logo_dark');
                @endphp
                @if($logoLight)
                    <img src="{{ asset('storage/'.$logoLight) }}" class="h-10 w-auto object-contain max-w-[140px] dark:hidden" alt="Logo">
                @endif
                @if($logoDark)
                    <img src="{{ asset('storage/'.$logoDark) }}" class="h-10 w-auto object-contain max-w-[140px] hidden dark:block" alt="Logo">
                @elseif($logoLight)
                    <img src="{{ asset('storage/'.$logoLight) }}" class="h-10 w-auto object-contain max-w-[140px] hidden dark:block brightness-150" alt="Logo">
                @else
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-white text-xs shadow" style="background: linear-gradient(135deg,#7C3AED,#5B21B6)">PPG</div>
                @endif
                <div class="hidden sm:block">
                    <div class="nav-brand-title font-bold text-gray-800 text-sm leading-tight">{{ \App\Models\AppSetting::get('app_name', 'PPG UIN Siber') }}</div>
                    <div class="nav-brand-subtitle text-xs text-purple-600 font-medium">{{ \App\Models\AppSetting::get('app_subtitle', 'Syekh Nurjati Cirebon') }}</div>
                </div>
            </a>

            {{-- DESKTOP NAVIGATION ITEMS (DARI MAIN MENU) --}}
            <div class="hidden md:flex items-center space-x-1 sm:space-x-2">
                @if(isset($mainMenuItems) && $mainMenuItems->isNotEmpty())
                    @foreach($mainMenuItems as $mItem)
                        <x-public-menu-item :item="$mItem" />
                    @endforeach
                @else
                    <a href="{{ route('landing') }}" class="nav-link text-sm font-medium text-gray-600 hover:text-purple-700 py-1.5 px-3 rounded-xl">Beranda</a>
                    <a href="{{ route('public.berita') }}" class="nav-link text-sm font-medium text-gray-600 hover:text-purple-700 py-1.5 px-3 rounded-xl">Berita</a>
                @endif

                {{-- Masuk ke Portal --}}
                <a href="{{ route('login') }}" class="text-xs sm:text-sm font-semibold text-white px-3.5 sm:px-4 py-2 rounded-xl shadow transition whitespace-nowrap ml-2"
                    style="background: linear-gradient(135deg,#7C3AED,#5B21B6)"
                    onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
                    Masuk ke Portal
                </a>
            </div>

            {{-- MOBILE MENU BUTTON --}}
            <div class="flex items-center space-x-2 md:hidden">
                <a href="{{ route('login') }}" class="text-xs font-semibold text-white px-2.5 py-1.5 rounded-lg shadow whitespace-nowrap" style="background: linear-gradient(135deg,#7C3AED,#5B21B6)">
                    Portal
                </a>
                <button type="button" id="mobileMenuToggleBtn" onclick="toggleMobileMenu()" class="p-2 rounded-xl text-gray-600 dark:text-slate-300 hover:bg-purple-50 dark:hover:bg-slate-800 transition focus:outline-none" aria-label="Buka Menu Navigasi Mobile">
                    <svg id="mobileMenuIconOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg id="mobileMenuIconClose" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU DRAWER (RESPONSIF) --}}
    <div id="mobileMenuDrawer" class="hidden md:hidden border-t border-purple-100 dark:border-slate-800 bg-white dark:bg-slate-900 px-4 pt-3 pb-6 transition-all duration-300 shadow-xl">
        <div class="space-y-1">
            @if(isset($mainMenuItems) && $mainMenuItems->isNotEmpty())
                @foreach($mainMenuItems as $mItem)
                    @if($mItem->activeChildren && $mItem->activeChildren->isNotEmpty())
                        <div class="py-1">
                            <div class="px-3 py-2 text-xs font-bold uppercase tracking-wider text-purple-700 dark:text-purple-400 flex items-center space-x-2 bg-purple-50/50 dark:bg-slate-800/50 rounded-xl">
                                @if($mItem->icon)
                                    <i class="{{ $mItem->icon }} text-xs"></i>
                                @endif
                                <span>{{ $mItem->title }}</span>
                            </div>
                            <div class="pl-4 mt-1 space-y-1 border-l-2 border-purple-200 dark:border-slate-700 ml-3">
                                @foreach($mItem->activeChildren as $child)
                                <a href="{{ $child->getUrl() }}" target="{{ $child->target }}" class="block px-3 py-2 text-sm text-gray-700 dark:text-slate-300 hover:text-purple-700 dark:hover:text-purple-400 rounded-lg hover:bg-purple-50 dark:hover:bg-slate-800 transition flex items-center justify-between">
                                    <span class="flex items-center space-x-2">
                                        @if($child->icon)
                                            <i class="{{ $child->icon }} text-xs text-purple-500"></i>
                                        @endif
                                        <span>{{ $child->title }}</span>
                                    </span>
                                    @if($child->target === '_blank')
                                        <i class="fas fa-external-link-alt text-[9px] text-gray-400"></i>
                                    @endif
                                </a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $mItem->getUrl() }}" target="{{ $mItem->target }}" class="block px-3 py-2.5 text-sm font-semibold text-gray-700 dark:text-slate-200 hover:text-purple-700 dark:hover:text-purple-400 rounded-xl hover:bg-purple-50 dark:hover:bg-slate-800 transition flex items-center space-x-2">
                            @if($mItem->icon)
                                <i class="{{ $mItem->icon }} text-xs text-purple-600"></i>
                            @endif
                            <span>{{ $mItem->title }}</span>
                        </a>
                    @endif
                @endforeach
            @else
                <a href="{{ route('landing') }}" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-purple-50 rounded-xl">Beranda</a>
                <a href="{{ route('public.berita') }}" class="block px-3 py-2 text-sm font-medium text-gray-700 hover:bg-purple-50 rounded-xl">Berita</a>
            @endif

            <div class="pt-3 border-t border-purple-100 dark:border-slate-800 mt-2">
                <a href="{{ route('login') }}" class="block text-center text-sm font-bold text-white py-2.5 rounded-xl shadow" style="background: linear-gradient(135deg,#7C3AED,#5B21B6)">
                    Masuk ke Portal Lapor Diri
                </a>
            </div>
        </div>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        const drawer = document.getElementById('mobileMenuDrawer');
        const iconOpen = document.getElementById('mobileMenuIconOpen');
        const iconClose = document.getElementById('mobileMenuIconClose');
        
        if (drawer) {
            const isHidden = drawer.classList.contains('hidden');
            if (isHidden) {
                drawer.classList.remove('hidden');
                iconOpen.classList.add('hidden');
                iconClose.classList.remove('hidden');
            } else {
                drawer.classList.add('hidden');
                iconOpen.classList.remove('hidden');
                iconClose.classList.add('hidden');
            }
        }
    }
</script>
</header>

<!-- Main Landmark (Konten Utama) -->
<main id="main-content" role="main">
    @yield('content')
</main>

{{-- ===== FOOTER GELOMBANG LENGKUNG (WAVE DIVIDER) ===== --}}
<div class="w-full overflow-hidden leading-none mt-20 -mb-1 pointer-events-none select-none">
    <svg class="block w-full h-12 sm:h-20 md:h-28 text-[#1E1B4B] dark:text-[#060911] transition-colors duration-200" viewBox="0 0 1440 120" fill="none" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0,38 C140,0 260,2 400,40 C560,82 720,95 880,82 C1040,68 1200,20 1340,20 C1380,20 1415,26 1440,32 L1440,120 L0,120 Z" fill="currentColor"></path>
    </svg>
</div>

<!-- Footer Landmark (Contentinfo) -->
<footer role="contentinfo" class="bg-[#1E1B4B] dark:bg-[#060911] text-white pt-6 pb-12 transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-10">
            
            {{-- KOLOM 1: IDENTITAS & PROFIL PPG (TANPA CARD) --}}
            <div class="lg:col-span-1">
                <div class="flex items-center space-x-3 mb-4">
                    @php 
                        $logoDark  = \App\Models\AppSetting::get('app_logo_dark');
                        $logoLight = \App\Models\AppSetting::get('app_logo_light'); 
                    @endphp
                    @if($logoDark)
                        <img src="{{ asset('storage/'.$logoDark) }}" class="h-11 w-auto object-contain flex-shrink-0" alt="Logo">
                    @elseif($logoLight)
                        <img src="{{ asset('storage/'.$logoLight) }}" class="h-11 w-auto object-contain flex-shrink-0 brightness-150" alt="Logo">
                    @else
                        <div class="w-10 h-10 rounded-xl bg-purple-600/80 border border-purple-400/30 text-white flex items-center justify-center font-black text-xs flex-shrink-0">PPG</div>
                    @endif
                    <div>
                        <p class="font-bold text-white text-sm leading-tight">{{ \App\Models\AppSetting::get('app_name', 'Pendidikan Profesi Guru (PPG)') }}</p>
                        <p class="text-xs text-purple-300 dark:text-slate-400 font-medium leading-tight mt-1">{{ \App\Models\AppSetting::get('app_subtitle', 'UIN Siber Syekh Nurjati Cirebon') }}</p>
                    </div>
                </div>
                <p class="text-purple-200/90 dark:text-slate-400 text-xs leading-relaxed">
                    Portal resmi {{ \App\Models\AppSetting::get('app_name', 'Program Pendidikan Profesi Guru (PPG)') }} {{ \App\Models\AppSetting::get('app_subtitle', 'UIN Siber Syekh Nurjati Cirebon') }}. Menyelenggarakan layanan pendidikan profesi guru yang profesional, inklusif, fleksibel, dan berkualitas berbasis teknologi siber terintegrasi.
                </p>
            </div>

            {{-- KOLOM 2: PROGRAM PPG --}}
            <div>
                <h4 class="font-bold text-xs tracking-wider uppercase text-white mb-4 flex items-center space-x-1.5">
                    <span class="text-amber-400 font-black text-base">|</span>
                    <span class="tracking-wider">PROGRAM PPG</span>
                </h4>
                <div class="space-y-2.5 text-xs text-purple-200/90 dark:text-slate-300">
                    <p class="hover:text-white transition cursor-default">S1 & Profesi Guru PAI</p>
                    <p class="hover:text-white transition cursor-default">PPG Dalam Jabatan (Daljab)</p>
                    <p class="hover:text-white transition cursor-default">PPG Prajabatan</p>
                    <p class="hover:text-white transition cursor-default">PPG Guru Madrasah Kemenag</p>
                    <p class="hover:text-white transition cursor-default">Pendidikan Guru MI (PGMI)</p>
                </div>
            </div>

            {{-- KOLOM 3: PORTAL UTAMA (DARI FOOTER MENU / DEFAULT) --}}
            <div>
                <h4 class="font-bold text-xs tracking-wider uppercase text-white mb-4 flex items-center space-x-1.5">
                    <span class="text-amber-400 font-black text-base">|</span>
                    <span class="tracking-wider">PORTAL UTAMA</span>
                </h4>
                <div class="space-y-2.5 text-xs text-purple-200/90 dark:text-slate-300">
                    @if(isset($footerMenuItems) && $footerMenuItems->isNotEmpty())
                        @foreach($footerMenuItems as $fItem)
                            <a href="{{ $fItem->getUrl() }}" target="{{ $fItem->target }}" class="hover:text-white transition flex items-center space-x-2">
                                @if($fItem->icon)
                                    <i class="{{ $fItem->icon }} text-[10px] text-purple-300 w-3"></i>
                                @endif
                                <span>{{ $fItem->title }}</span>
                                @if($fItem->target === '_blank')
                                    <span class="text-[10px] opacity-60">↗</span>
                                @endif
                            </a>
                        @endforeach
                    @else
                        <a href="{{ route('landing') }}" class="block hover:text-white transition">Beranda Utama</a>
                        <a href="{{ route('public.berita') }}" class="block hover:text-white transition">Berita & Kegiatan</a>
                        <a href="{{ route('login') }}" class="block hover:text-white transition">Portal Lapor Diri Mahasiswa</a>
                        <a href="https://uinssc.ac.id" target="_blank" rel="noopener noreferrer" class="block hover:text-white transition">Website Resmi UINSSC ↗</a>
                        <a href="https://ppid.uinssc.ac.id" target="_blank" rel="noopener noreferrer" class="block hover:text-white transition">PPID UINSSC ↗</a>
                    @endif
                </div>
            </div>

            {{-- KOLOM 4: KONTAK & LAYANAN --}}
            <div>
                <h4 class="font-bold text-xs tracking-wider uppercase text-white mb-4 flex items-center space-x-1.5">
                    <span class="text-amber-400 font-black text-base">|</span>
                    <span class="tracking-wider">KONTAK & LAYANAN</span>
                </h4>
                <div class="space-y-2.5 text-xs text-purple-200/90 dark:text-slate-300">
                    <p class="flex items-start space-x-2">
                        <svg class="w-4 h-4 text-purple-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="leading-relaxed">Jl. Perjuangan By Pass Sunyaragi, Kota Cirebon, Jawa Barat 45132</span>
                    </p>
                    <p class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <span>info@uinssc.ac.id</span>
                    </p>
                    <p class="flex items-center space-x-2">
                        <svg class="w-4 h-4 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                        <span>uinssc.ac.id</span>
                    </p>
                </div>
            </div>

        </div>

        <div class="border-t border-purple-800/80 dark:border-slate-800 pt-6 text-center text-xs text-purple-300 dark:text-slate-400">
            &copy; {{ date('Y') }} UPT TIK UIN Siber Syekh Nurjati Cirebon. Seluruh hak cipta dilindungi.
        </div>
    </div>
</footer>

{{-- Google Translate Element (Hidden) --}}
<div id="google_translate_element" style="display:none !important;"></div>
<script type="text/javascript">
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'id',
            includedLanguages: 'id,en,ar',
            autoDisplay: false,
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
        }, 'google_translate_element');
    }
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script>
    /* =========================================================
       1. KONTROL MODE GELAP / TERANG (DARK / LIGHT MODE)
       ========================================================= */
    function updateThemeUI(isDark) {
        const themeLabel = document.getElementById('themeLabel');
        const themeIcon  = document.getElementById('themeIcon');
        const themeBtn   = document.getElementById('themeToggleBtn');
        if (isDark) {
            if (themeIcon)  themeIcon.textContent = '☀️';
            if (themeLabel) themeLabel.textContent = 'Mode Terang';
            if (themeBtn)   themeBtn.setAttribute('title', 'Alihkan ke Mode Terang');
        } else {
            if (themeIcon)  themeIcon.textContent = '🌙';
            if (themeLabel) themeLabel.textContent = 'Mode Gelap';
            if (themeBtn)   themeBtn.setAttribute('title', 'Alihkan ke Mode Gelap');
        }
    }

    function toggleTheme() {
        const isDark = document.documentElement.classList.toggle('dark');
        localStorage.setItem('ppg_theme', isDark ? 'dark' : 'light');
        updateThemeUI(isDark);
    }

    // Inisialisasi status UI tombol tema saat DOM siap
    document.addEventListener('DOMContentLoaded', function() {
        const isDark = document.documentElement.classList.contains('dark');
        updateThemeUI(isDark);
    });

    /* =========================================================
       2. PENEKANAN TOTAL BANNER, TOOLBAR & OPSI GOOGLE TRANSLATE
       ========================================================= */
    function enforceHideGoogleBanner() {
        // Cari dan sembunyikan semua elemen banner/frame/opsi bawaan Google Translate
        const targets = document.querySelectorAll(
            'iframe.goog-te-banner-frame, iframe.skiptranslate, .goog-te-banner-frame, ' +
            'body > .skiptranslate, body > div[class*="skiptranslate"], iframe[id^=":"]'
        );
        targets.forEach(function(el) {
            el.style.setProperty('display', 'none', 'important');
            el.style.setProperty('visibility', 'hidden', 'important');
            el.style.setProperty('height', '0px', 'important');
            el.style.setProperty('max-height', '0px', 'important');
            el.style.setProperty('width', '0px', 'important');
            el.style.setProperty('opacity', '0', 'important');
            el.style.setProperty('pointer-events', 'none', 'important');
            el.style.setProperty('z-index', '-99999', 'important');
        });

        // Paksa body tetap di posisi paling atas (Google suka memberi body top: 40px)
        if (document.body) {
            if (document.body.style.top && document.body.style.top !== '0px') {
                document.body.style.setProperty('top', '0px', 'important');
            }
            if (document.body.style.position === 'relative') {
                document.body.style.setProperty('position', 'static', 'important');
            }
        }
    }

    // Amati perubahan DOM seketika Google Translate menyuntikkan iframe
    const bannerObserver = new MutationObserver(enforceHideGoogleBanner);
    bannerObserver.observe(document.documentElement, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['style', 'class']
    });
    window.addEventListener('DOMContentLoaded', enforceHideGoogleBanner);
    window.addEventListener('load', enforceHideGoogleBanner);
    setInterval(enforceHideGoogleBanner, 250);

    /* =========================================================
       3. LOGIKA DROPDOWN BAHASA & GOOGLE TRANSLATE
       ========================================================= */
    const langBtn = document.getElementById('langMenuBtn');
    const langDropdown = document.getElementById('langDropdown');

    if (langBtn && langDropdown) {
        langBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            langDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', function(e) {
            if (!langDropdown.contains(e.target) && !langBtn.contains(e.target)) {
                langDropdown.classList.add('hidden');
            }
        });
    }

    function getTranslateCookie() {
        const name = "googtrans=";
        const decodedCookie = decodeURIComponent(document.cookie);
        const ca = decodedCookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i].trim();
            if (c.indexOf(name) === 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    function updateLangUI(lang) {
        const activeLabel = document.getElementById('activeLangLabel');
        if (activeLabel) {
            activeLabel.textContent = lang.toUpperCase();
        }

        ['id', 'en', 'ar'].forEach(function(l) {
            const check = document.getElementById('check-' + l);
            if (check) {
                if (l === lang) {
                    check.classList.remove('hidden');
                } else {
                    check.classList.add('hidden');
                }
            }
        });

        if (lang === 'ar') {
            document.documentElement.setAttribute('dir', 'rtl');
        } else {
            document.documentElement.removeAttribute('dir');
        }
    }

    function translateLanguage(lang) {
        const domain = window.location.hostname;
        const isLocalhost = domain === 'localhost' || domain === '127.0.0.1';

        if (lang === 'id') {
            document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            if (!isLocalhost) {
                document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=" + domain;
                document.cookie = "googtrans=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/; domain=." + domain;
            }
            document.cookie = "googtrans=/id/id; path=/;";
            localStorage.setItem('selected_lang', 'id');
        } else {
            const cookieVal = "/id/" + lang;
            document.cookie = "googtrans=" + cookieVal + "; path=/;";
            if (!isLocalhost) {
                document.cookie = "googtrans=" + cookieVal + "; path=/; domain=" + domain;
                document.cookie = "googtrans=" + cookieVal + "; path=/; domain=." + domain;
            }
            localStorage.setItem('selected_lang', lang);
        }

        updateLangUI(lang);
        if (langDropdown) langDropdown.classList.add('hidden');

        // Picu pembaruan elemen Google Translate
        const select = document.querySelector('.goog-te-combo');
        if (select) {
            select.value = lang;
            select.dispatchEvent(new Event('change'));
        } else {
            window.location.reload();
        }

        // Pastikan banner Google langsung ditekan
        enforceHideGoogleBanner();
    }

    // Inisialisasi bahasa saat halaman dibuka
    document.addEventListener('DOMContentLoaded', function() {
        const cookieVal = getTranslateCookie();
        let currentLang = 'id';
        if (cookieVal) {
            const parts = cookieVal.split('/');
            if (parts.length >= 3 && ['id', 'en', 'ar'].includes(parts[2])) {
                currentLang = parts[2];
            }
        } else {
            const saved = localStorage.getItem('selected_lang');
            if (saved && ['id', 'en', 'ar'].includes(saved)) {
                currentLang = saved;
            }
        }
        updateLangUI(currentLang);
    });
</script>

<!-- WCAG Accessibility Floating Toolbar -->
<div id="wcag-widget-container" class="fixed bottom-6 right-6 z-[9990]">
    <!-- Tombol Pemicu Mengambang (Tema Ungu & Emas PPG) -->
    <button id="wcag-toggle-btn" onclick="toggleWcagPanel()" aria-expanded="false" aria-controls="wcag-panel" aria-label="Buka Menu Alat Aksesibilitas (WCAG 2.1)" title="Alat Aksesibilitas WCAG 2.1" class="btn-wcag-toggle w-[60px] h-[60px] flex items-center justify-center bg-gradient-to-br from-[#4C1D95] via-[#3B0764] to-[#2E0854] border-[2.5px] border-[#F5C518] text-[#FDE68A] hover:text-white rounded-full shadow-[0_0_0_6px_rgba(245,197,24,0.28),0_12px_28px_rgba(59,7,100,0.45)] hover:shadow-[0_0_0_8px_rgba(245,197,24,0.42),0_16px_34px_rgba(59,7,100,0.55)] focus:ring-4 focus:ring-[#F5C518]/50 focus:outline-none transition-all duration-300 group cursor-pointer hover:scale-105 active:scale-95">
        <svg class="w-8 h-8 group-hover:scale-110 transition-transform" viewBox="0 0 32 32" fill="none">
            <!-- Gold Filled Disc -->
            <circle cx="16" cy="16" r="10" fill="#F5C518"/>
            <!-- Purple Silhouette Figure Inside Disc -->
            <circle cx="16" cy="11.2" r="1.5" fill="#3B0764"/>
            <path d="M10.8 15.2C12.4 14.7 14.2 14.4 16 14.4C17.8 14.4 19.6 14.7 21.2 15.2" stroke="#3B0764" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M16 14.5V19.2" stroke="#3B0764" stroke-width="1.8" stroke-linecap="round"/>
            <path d="M13.8 24L16 19.2L18.2 24" stroke="#3B0764" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>
    
    <!-- Panel Pengaturan Aksesibilitas -->
    <div id="wcag-panel" class="hidden absolute bottom-[148px] right-0 w-80 sm:w-96 max-h-[calc(100vh-180px)] overflow-y-auto bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl border border-purple-200/80 dark:border-purple-900/50 rounded-3xl shadow-[0_20px_60px_-15px_rgba(30,27,75,0.3)] p-5 text-gray-800 dark:text-slate-100 z-[9999] transition-all duration-200">
        <div class="flex items-center justify-between pb-3.5 border-b border-purple-100 dark:border-slate-800 mb-4">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 flex items-center justify-center shadow-sm">
                    <i class="fa-solid fa-universal-access text-base"></i>
                </div>
                <div>
                    <div class="font-extrabold text-sm text-purple-950 dark:text-purple-100 leading-tight">Aksesibilitas Web</div>
                    <div class="text-[10px] text-purple-600 dark:text-purple-400 font-bold tracking-wider uppercase">Standar WCAG 2.1 (Level AAA)</div>
                </div>
            </div>
            <button type="button" onclick="toggleWcagPanel()" aria-label="Tutup Pengaturan Aksesibilitas" class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-purple-700 dark:hover:text-purple-300 hover:bg-purple-50 dark:hover:bg-slate-800 transition cursor-pointer">
                <i class="fa-solid fa-xmark text-base"></i>
            </button>
        </div>
        <div class="space-y-3.5 text-xs">
            <!-- 1. Ukuran Teks / Zoom -->
            <div>
                <label class="font-bold text-gray-700 dark:text-slate-200 block mb-2 flex items-center justify-between">
                    <span>Ukuran Huruf (Zoom Teks)</span>
                    <span class="text-[10px] text-purple-600 dark:text-purple-400 font-semibold">Resizing WCAG</span>
                </label>
                <div class="grid grid-cols-4 gap-1.5">
                    <button type="button" onclick="setWcagFontSize('normal')" id="btn-font-normal" class="py-2 px-1 text-center font-bold rounded-xl border border-purple-100 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-purple-50 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 transition cursor-pointer">Normal</button>
                    <button type="button" onclick="setWcagFontSize('md')" id="btn-font-md" class="py-2 px-1 text-center font-bold rounded-xl border border-purple-100 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-purple-50 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 transition cursor-pointer">Sedang</button>
                    <button type="button" onclick="setWcagFontSize('lg')" id="btn-font-lg" class="py-2 px-1 text-center font-bold rounded-xl border border-purple-100 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-purple-50 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 transition cursor-pointer">Besar</button>
                    <button type="button" onclick="setWcagFontSize('xl')" id="btn-font-xl" class="py-2 px-1 text-center font-bold rounded-xl border border-purple-100 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-purple-50 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 transition cursor-pointer">Maks</button>
                </div>
            </div>
            <!-- 2. Kontras Tinggi -->
            <div class="flex items-center justify-between p-3 bg-purple-50/50 dark:bg-slate-800/60 rounded-2xl border border-purple-100/80 dark:border-slate-700/80">
                <div>
                    <div class="font-bold text-gray-800 dark:text-slate-200">Mode Kontras Tinggi (AAA)</div>
                    <div class="text-[11px] text-gray-500 dark:text-slate-400">Rasio tajam untuk low-vision / rabun</div>
                </div>
                <button type="button" onclick="toggleWcagHighContrast()" id="btn-contrast-toggle" class="px-3.5 py-1.5 rounded-xl font-bold bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs transition cursor-pointer">
                    Mati
                </button>
            </div>
            <!-- 3. Font Ramah Disleksia -->
            <div class="flex items-center justify-between p-3 bg-purple-50/50 dark:bg-slate-800/60 rounded-2xl border border-purple-100/80 dark:border-slate-700/80">
                <div>
                    <div class="font-bold text-gray-800 dark:text-slate-200">Font Ramah Disleksia</div>
                    <div class="text-[11px] text-gray-500 dark:text-slate-400">Spasi & jarak baca lebih lebar</div>
                </div>
                <button type="button" onclick="toggleWcagDyslexia()" id="btn-dyslexia-toggle" class="px-3.5 py-1.5 rounded-xl font-bold bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs transition cursor-pointer">
                    Mati
                </button>
            </div>
            <!-- 4. Garis Bawah Tautan -->
            <div class="flex items-center justify-between p-3 bg-purple-50/50 dark:bg-slate-800/60 rounded-2xl border border-purple-100/80 dark:border-slate-700/80">
                <div>
                    <div class="font-bold text-gray-800 dark:text-slate-200">Sorot Garis Bawah Tautan</div>
                    <div class="text-[11px] text-gray-500 dark:text-slate-400">Bantuan bagi disabilitas buta warna</div>
                </div>
                <button type="button" onclick="toggleWcagUnderline()" id="btn-underline-toggle" class="px-3.5 py-1.5 rounded-xl font-bold bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs transition cursor-pointer">
                    Mati
                </button>
            </div>
            <!-- 5. Text to Speech (TTS) Interaktif -->
            <div class="bg-gradient-to-br from-purple-50 via-purple-50/60 to-indigo-50/80 dark:from-purple-950/40 dark:to-indigo-950/30 border border-purple-200 dark:border-purple-800/60 rounded-2xl p-3 space-y-2.5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-bold text-purple-950 dark:text-purple-200 flex items-center gap-1.5">
                            <i class="fa-solid fa-volume-high text-purple-600 dark:text-purple-400"></i>
                            Baca Nyaring (Text-to-Speech)
                        </div>
                        <div class="text-[11px] text-purple-700/80 dark:text-purple-300/80 mt-0.5">Klik elemen teks di halaman untuk dibacakan</div>
                    </div>
                    <button type="button" onclick="toggleTts()" id="btn-tts-toggle" class="px-3.5 py-1.5 rounded-xl font-bold bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200 text-xs transition cursor-pointer">
                        Mati
                    </button>
                </div>
                <div id="tts-lang-info" class="hidden text-[10px] font-semibold text-purple-800 dark:text-purple-300 bg-purple-100/90 dark:bg-purple-900/50 border border-purple-200/50 dark:border-purple-700/40 px-2.5 py-1.5 rounded-xl flex items-center gap-1.5">
                    <i class="fa-solid fa-language text-purple-600 dark:text-purple-400"></i>
                    <span id="tts-lang-label">Logat: Indonesia (id-ID)</span>
                </div>
                <div id="tts-status" class="hidden text-[11px] text-purple-700 dark:text-purple-300 font-semibold animate-pulse flex items-center gap-1.5">
                    <i class="fa-solid fa-circle-notch fa-spin"></i>
                    <span>Membacakan teks...</span>
                </div>
            </div>
            <!-- 6. Reset Pengaturan -->
            <div class="pt-1">
                <button type="button" onclick="resetWcagSettings()" class="w-full py-2.5 text-center text-rose-600 dark:text-rose-400 hover:text-rose-700 hover:bg-rose-50 dark:hover:bg-rose-950/30 font-bold rounded-xl border border-rose-200 dark:border-rose-900/50 transition cursor-pointer">
                    <i class="fa-solid fa-rotate-left mr-1"></i> Kembalikan ke Standar Default
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // ====================================================================
    // WCAG 2.1 Accessibility Controller & Text-to-Speech Engine
    // ====================================================================

    // Toggle Buka/Tutup Panel
    function toggleWcagPanel() {
        const panel = document.getElementById('wcag-panel');
        const btn = document.getElementById('wcag-toggle-btn');
        if (!panel || !btn) return;
        const isHidden = panel.classList.contains('hidden');
        if (isHidden) {
            panel.classList.remove('hidden');
            btn.setAttribute('aria-expanded', 'true');
        } else {
            panel.classList.add('hidden');
            btn.setAttribute('aria-expanded', 'false');
        }
    }

    // 1. Ukuran Font
    function setWcagFontSize(size) {
        const root = document.documentElement;
        root.classList.remove('font-size-md', 'font-size-lg', 'font-size-xl');
        if (size !== 'normal') {
            root.classList.add('font-size-' + size);
        }
        localStorage.setItem('app_wcag_font_size', size);
        updateWcagUi();
    }

    // 2. High Contrast
    function toggleWcagHighContrast() {
        const root = document.documentElement;
        const isHc = root.classList.toggle('high-contrast');
        localStorage.setItem('app_wcag_contrast', isHc ? '1' : '0');
        updateWcagUi();
    }

    // 3. Dyslexia Font
    function toggleWcagDyslexia() {
        const root = document.documentElement;
        const isDys = root.classList.toggle('dyslexia-font');
        localStorage.setItem('app_wcag_dyslexia', isDys ? '1' : '0');
        updateWcagUi();
    }

    // 4. Underline Links
    function toggleWcagUnderline() {
        const root = document.documentElement;
        const isUnd = root.classList.toggle('underline-links');
        localStorage.setItem('app_wcag_underline', isUnd ? '1' : '0');
        updateWcagUi();
    }

    // Reset Settings
    function resetWcagSettings() {
        localStorage.removeItem('app_wcag_font_size');
        localStorage.removeItem('app_wcag_contrast');
        localStorage.removeItem('app_wcag_dyslexia');
        localStorage.removeItem('app_wcag_underline');
        localStorage.removeItem('app_tts_active');
        const root = document.documentElement;
        root.classList.remove('font-size-md', 'font-size-lg', 'font-size-xl', 'high-contrast', 'dyslexia-font', 'underline-links');
        ttsActive = false;
        stopTts();
        updateWcagUi();
    }

    // ====================================================================
    // Text-to-Speech (TTS) Engine — ResponsiveVoice + Web Speech Fallback
    // ====================================================================
    let ttsActive = localStorage.getItem('app_tts_active') === '1';

    function toggleTts() {
        ttsActive = !ttsActive;
        localStorage.setItem('app_tts_active', ttsActive ? '1' : '0');
        if (!ttsActive) {
            stopTts();
        } else {
            speakText('Fitur baca nyaring aktif. Klik teks apa saja pada layar untuk dibacakan.');
        }
        updateWcagUi();
    }

    function stopTts() {
        if (window.responsiveVoice) {
            responsiveVoice.cancel();
        } else if (window.speechSynthesis) {
            window.speechSynthesis.cancel();
        }
        const statusEl = document.getElementById('tts-status');
        if (statusEl) statusEl.classList.add('hidden');
    }

    function speakText(text) {
        if (!text || text.trim() === '') return;
        stopTts();
        const cleanText = text.replace(/<[^>]+>/g, ' ').replace(/\s+/g, ' ').trim();
        if (!cleanText || cleanText.length < 2) return;
        const statusEl = document.getElementById('tts-status');
        const statusSpan = statusEl ? statusEl.querySelector('span') : null;

        // Prioritas 1: Native Web Speech API (Standar Modern & Handal di semua browser)
        if (window.speechSynthesis) {
            try {
                window.speechSynthesis.cancel();
                const utterance = new SpeechSynthesisUtterance(cleanText);
                utterance.lang = 'id-ID';
                utterance.rate = 0.95;

                const voices = window.speechSynthesis.getVoices();
                const idVoice = voices.find(v => v.lang && (v.lang.startsWith('id') || v.lang.includes('ID')));
                if (idVoice) utterance.voice = idVoice;

                if (statusEl) statusEl.classList.remove('hidden');
                if (statusSpan) statusSpan.textContent = 'Membacakan teks…';

                utterance.onend = () => { if (statusEl) statusEl.classList.add('hidden'); };
                utterance.onerror = () => { if (statusEl) statusEl.classList.add('hidden'); };
                window.speechSynthesis.speak(utterance);
                return;
            } catch(e) {
                console.warn('SpeechSynthesis error, falling back to ResponsiveVoice:', e);
            }
        }

        // Prioritas 2: ResponsiveVoice jika tersedia
        if (window.responsiveVoice && responsiveVoice.voiceSupport()) {
            responsiveVoice.speak(cleanText, 'Indonesian Female', {
                rate: 0.95,
                pitch: 1,
                volume: 1,
                onstart: function() {
                    if (statusEl) statusEl.classList.remove('hidden');
                    if (statusSpan) statusSpan.textContent = 'Membacakan teks…';
                },
                onend: function()   { if (statusEl) statusEl.classList.add('hidden'); },
                onerror: function() { if (statusEl) statusEl.classList.add('hidden'); }
            });
        }
    }

    // Click listener untuk membaca elemen teks semantik
    document.addEventListener('click', function(e) {
        if (!ttsActive) return;
        if (e.target.closest('#wcag-widget-container')) return;
        const el = e.target.closest('p, h1, h2, h3, h4, h5, h6, li, a, span, td, th, label, blockquote, figcaption');
        if (el) {
            const text = el.innerText || el.textContent;
            if (text && text.trim().length > 1) {
                speakText(text);
            }
        }
    });

    // Cursor pembantu saat mode TTS menyala
    const ttsStyle = document.createElement('style');
    document.head.appendChild(ttsStyle);
    function applyTtsCursor() {
        ttsStyle.textContent = ttsActive
            ? 'p, h1, h2, h3, h4, h5, h6, li, a, span, td, th, label { cursor: cell !important; }'
            : '';
    }

    // Sinkronisasi status UI tombol
    function updateWcagUi() {
        applyTtsCursor();
        
        // Active / Inactive classes
        const activeClass = 'px-3.5 py-1.5 rounded-xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-sm border border-purple-500 text-xs transition cursor-pointer';
        const inactiveClass = 'px-3.5 py-1.5 rounded-xl font-bold bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 text-xs transition cursor-pointer';

        // TTS Button UI
        const btnTts = document.getElementById('btn-tts-toggle');
        const ttsLangInfo = document.getElementById('tts-lang-info');
        if (btnTts) {
            btnTts.textContent = ttsActive ? 'Aktif' : 'Mati';
            btnTts.className = ttsActive ? activeClass : inactiveClass;
        }
        if (ttsLangInfo) {
            if (ttsActive) {
                ttsLangInfo.classList.remove('hidden');
                ttsLangInfo.classList.add('flex');
            } else {
                ttsLangInfo.classList.add('hidden');
                ttsLangInfo.classList.remove('flex');
            }
        }

        // Font Size UI
        const fontSize = localStorage.getItem('app_wcag_font_size') || 'normal';
        ['normal', 'md', 'lg', 'xl'].forEach(s => {
            const el = document.getElementById('btn-font-' + s);
            if (el) {
                if (s === fontSize) {
                    el.className = 'py-2 px-1 text-center font-bold rounded-xl border border-purple-600 bg-gradient-to-r from-purple-600 to-indigo-600 text-white shadow-sm transition';
                } else {
                    el.className = 'py-2 px-1 text-center font-bold rounded-xl border border-purple-100 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-purple-50 dark:hover:bg-slate-700 text-gray-700 dark:text-slate-200 transition cursor-pointer';
                }
            }
        });

        // High Contrast Button UI
        const isHc = document.documentElement.classList.contains('high-contrast');
        const btnHc = document.getElementById('btn-contrast-toggle');
        if (btnHc) {
            btnHc.textContent = isHc ? 'Aktif' : 'Mati';
            btnHc.className = isHc ? activeClass : inactiveClass;
        }

        // Dyslexia Button UI
        const isDys = document.documentElement.classList.contains('dyslexia-font');
        const btnDys = document.getElementById('btn-dyslexia-toggle');
        if (btnDys) {
            btnDys.textContent = isDys ? 'Aktif' : 'Mati';
            btnDys.className = isDys ? activeClass : inactiveClass;
        }

        // Underline Button UI
        const isUnd = document.documentElement.classList.contains('underline-links');
        const btnUnd = document.getElementById('btn-underline-toggle');
        if (btnUnd) {
            btnUnd.textContent = isUnd ? 'Aktif' : 'Mati';
            btnUnd.className = isUnd ? activeClass : inactiveClass;
        }
    }

    // Inisialisasi otomatis pemulihan preferensi dari localStorage saat page load
    (function() {
        const fontSize = localStorage.getItem('app_wcag_font_size');
        if (fontSize && fontSize !== 'normal') {
            document.documentElement.classList.add('font-size-' + fontSize);
        }
        if (localStorage.getItem('app_wcag_contrast') === '1') {
            document.documentElement.classList.add('high-contrast');
        }
        if (localStorage.getItem('app_wcag_dyslexia') === '1') {
            document.documentElement.classList.add('dyslexia-font');
        }
        if (localStorage.getItem('app_wcag_underline') === '1') {
            document.documentElement.classList.add('underline-links');
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', updateWcagUi);
        } else {
            updateWcagUi();
        }
    })();

    // WCAG Keyboard & Click Outside accessibility handler
    document.addEventListener('click', function(e) {
        const container = document.getElementById('wcag-widget-container');
        const panel = document.getElementById('wcag-panel');
        const btn = document.getElementById('wcag-toggle-btn');
        if (panel && !panel.classList.contains('hidden')) {
            if (container && !container.contains(e.target)) {
                panel.classList.add('hidden');
                if (btn) btn.setAttribute('aria-expanded', 'false');
            }
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const panel = document.getElementById('wcag-panel');
            const btn = document.getElementById('wcag-toggle-btn');
            if (panel && !panel.classList.contains('hidden')) {
                panel.classList.add('hidden');
                if (btn) {
                    btn.setAttribute('aria-expanded', 'false');
                    btn.focus();
                }
            }
        }
    });

    // Universal Dropdown Toggle (Support Click on Mobile/Touch + Desktop)
    document.addEventListener('click', function(e) {
        const toggle = e.target.closest('.dropdown-toggle');
        const allGroups = document.querySelectorAll('.dropdown-group');
        if (toggle) {
            const group = toggle.closest('.dropdown-group');
            allGroups.forEach(g => { if (g !== group) g.classList.remove('is-open'); });
            if (group) group.classList.toggle('is-open');
        } else if (!e.target.closest('.dropdown-menu')) {
            allGroups.forEach(g => g.classList.remove('is-open'));
        }
    });
</script>

<x-help-center-floating-widget />

@livewireScripts
@stack('scripts')
</body>
</html>
