<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Models\AppSetting::get('app_name','Lapor Diri PPG') . ' — ' . \App\Models\AppSetting::get('app_subtitle','UIN Siber Syekh Nurjati Cirebon'))</title>
    <meta name="description" content="Portal Lapor Diri Mahasiswa Baru Program Pendidikan Profesi Guru (PPG) UIN Siber Syekh Nurjati Cirebon">
    @php $favicon = \App\Models\AppSetting::get('app_favicon'); @endphp
    @if($favicon)
    <link rel="icon" type="image/png" href="{{ asset('storage/'.$favicon) }}">
    @endif
    <script>
        // Inisialisasi instan preferensi aksesibilitas WCAG 2.1
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
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary:  { DEFAULT: '#7C3AED', light: '#A78BFA', dark: '#5B21B6', xdark: '#3B0764' },
                        accent:   { DEFAULT: '#F5C518', light: '#FDE68A' },
                        surface:  { DEFAULT: '#F8F5FF', card: '#FFFFFF' },
                    },
                    fontFamily: { sans: ['Plus Jakarta Sans', 'Inter', 'sans-serif'] },
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome 6 & ResponsiveVoice.js (Aksesibilitas WCAG 2.1 Level AAA) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.responsivevoice.org/responsivevoice.js?key=FREE" defer></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', 'Inter', sans-serif; background: linear-gradient(135deg, #F8F5FF 0%, #EDE9FE 40%, #F0F9FF 100%); min-height: 100vh; }

        /* ===== PURPLE SIDEBAR ===== */
        .sidebar { background: linear-gradient(180deg, #3B0764 0%, #4C1D95 40%, #5B21B6 100%); }
        .sidebar-section-label { color: rgba(167,139,250,0.7); font-size: 0.65rem; font-weight: 700; letter-spacing: 0.1em; text-transform: uppercase; padding: 0 0.75rem; margin-bottom: 0.25rem; }
        .sidebar-link { display: flex; align-items: center; padding: 0.6rem 0.75rem; border-radius: 0.625rem; color: rgba(216,180,254,0.8); font-size: 0.8125rem; font-weight: 500; transition: all 0.15s; text-decoration: none; }
        .sidebar-link:hover { background: rgba(255,255,255,0.1); color: white; }
        .sidebar-link.active { background: rgba(255,255,255,0.15); color: white; border-left: 3px solid #C4B5FD; }
        .sidebar-link svg { opacity: 0.8; }
        .sidebar-link:hover svg, .sidebar-link.active svg { opacity: 1; }
        .sidebar-divider { height: 1px; background: rgba(255,255,255,0.08); margin: 0.5rem 0.75rem; }

        /* ===== CARDS & UI ===== */
        .card { background: rgba(255,255,255,0.85); backdrop-filter: blur(8px); border-radius: 1rem; box-shadow: 0 2px 12px 0 rgba(124,58,237,0.07); border: 1px solid rgba(167,139,250,0.15); }
        .btn-primary { background: linear-gradient(135deg, #7C3AED, #5B21B6); color: white; border-radius: 0.625rem; font-weight: 600; transition: all 0.2s; box-shadow: 0 4px 12px rgba(124,58,237,0.25); display: inline-block; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(124,58,237,0.35); }
        .stat-card { background: linear-gradient(135deg, #7C3AED 0%, #5B21B6 100%); }
        .stat-card-gold { background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%); }
        .stat-card-teal { background: linear-gradient(135deg, #06B6D4 0%, #0891B2 100%); }
        .stat-card-green { background: linear-gradient(135deg, #10B981 0%, #059669 100%); }
        .badge-pending { background: #FEF3C7; color: #92400E; border: 1px solid #FCD34D; }
        .badge-verified { background: #D1FAE5; color: #065F46; border: 1px solid #6EE7B7; }
        .badge-rejected { background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; }
        .badge-draft { background: #F3F4F6; color: #374151; border: 1px solid #D1D5DB; }
        input[type=text], input[type=email], input[type=password], input[type=date], input[type=url], input[type=number], select, textarea {
            border-color: #DDD6FE; border-radius: 0.5rem; transition: border-color 0.2s, box-shadow 0.2s;
        }
        input:focus, select:focus, textarea:focus { outline: none; border-color: #7C3AED; box-shadow: 0 0 0 3px rgba(124,58,237,0.12); }
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 3px; }

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
<body class="antialiased">

<!-- Accessibility Skip Link (WCAG 2.1 Level AAA) -->
<a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-[99999] focus:px-5 focus:py-3 focus:bg-amber-400 focus:text-slate-950 focus:font-black focus:text-sm focus:rounded-xl focus:shadow-2xl focus:ring-4 focus:ring-amber-500 focus:outline-none">
    Lewati ke Konten Utama (Skip to Content)
</a>
<div class="min-h-screen flex">

    @auth
    {{-- ===== PURPLE SIDEBAR ===== --}}
    <aside class="sidebar w-64 flex flex-col flex-shrink-0 fixed h-full z-30 overflow-y-auto">

        {{-- Logo --}}
        <div class="px-5 py-5 border-b border-white/10">
            <div class="flex items-center space-x-3">
                @php $logoDark = \App\Models\AppSetting::get('app_logo_dark'); @endphp
                @if($logoDark)
                    <img src="{{ asset('storage/'.$logoDark) }}" class="h-9 w-auto" alt="Logo">
                @else
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center font-black text-white text-xs shadow-lg backdrop-blur">PPG</div>
                @endif
                <div class="min-w-0">
                    <div class="font-bold text-white text-sm leading-tight truncate">{{ \App\Models\AppSetting::get('app_name','Portal Lapor Diri') }}</div>
                    <div class="text-purple-300 text-xs truncate">{{ \App\Models\AppSetting::get('app_subtitle','UIN Siber Syekh Nurjati') }}</div>
                </div>
            </div>
        </div>

        {{-- User Badge --}}
        <div class="px-5 py-3 border-b border-white/10">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <div class="text-white text-xs font-semibold truncate">{{ Auth::user()->name }}</div>
                    <div class="text-purple-300 text-xs truncate">{{ Auth::user()->username }}</div>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav role="navigation" class="flex-1 px-3 py-4 space-y-0.5">

            @if(Auth::user()->role === 'admin')

                {{-- Dashboard --}}
                <p class="sidebar-section-label mt-1 mb-1">Utama</p>
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.helpdesk') }}" class="sidebar-link {{ request()->routeIs('admin.helpdesk') ? 'active' : '' }} flex items-center justify-between">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        Help Center & Live
                    </span>
                    @php $callingCount = \App\Models\HelpSession::calling()->count(); @endphp
                    @if($callingCount > 0)
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] font-extrabold bg-red-500 text-white animate-pulse">
                            {{ $callingCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.audit-log') }}" class="sidebar-link {{ request()->routeIs('admin.audit-log') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Log Aktivitas
                </a>

                {{-- Master Data --}}
                <div class="sidebar-divider"></div>
                <p class="sidebar-section-label mb-1">Master Data</p>
                <a href="{{ route('admin.periode') }}" class="sidebar-link {{ request()->routeIs('admin.periode*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Periode Lapor Diri
                </a>
                <a href="{{ route('admin.master-data') }}" class="sidebar-link {{ (request()->routeIs('admin.master-data*') || request()->routeIs('admin.verifikasi')) ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Mahasiswa Lapor Diri
                </a>

                {{-- Ekspor --}}
                <div class="sidebar-divider"></div>
                <p class="sidebar-section-label mb-1">Ekspor Data</p>
                <a href="{{ route('admin.export.pddikti') }}" class="sidebar-link">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Format PDDIKTI
                </a>
                <a href="{{ route('admin.export.siakad') }}" class="sidebar-link">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                    Format SIAKAD
                </a>

                {{-- Pengaturan Sistem --}}
                <div class="sidebar-divider"></div>
                <p class="sidebar-section-label mb-1">Pengaturan Sistem</p>
                <a href="{{ route('admin.pengaturan.identitas') }}" class="sidebar-link {{ request()->routeIs('admin.pengaturan.identitas') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Identitas Aplikasi
                </a>
                <a href="{{ route('admin.pengaturan.galeri') }}" class="sidebar-link {{ (request()->routeIs('admin.pengaturan.galeri') || request()->routeIs('admin.galeri')) ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Galeri Media
                </a>
                <a href="{{ route('admin.pengaturan.page') }}" class="sidebar-link {{ request()->routeIs('admin.pengaturan.page') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Halaman Statis (Pages)
                </a>
                <a href="{{ route('admin.pengaturan.artikel') }}" class="sidebar-link {{ (request()->routeIs('admin.pengaturan.artikel') || request()->routeIs('admin.cms.artikel')) ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    Berita & Artikel
                </a>
                <a href="{{ route('admin.pengaturan.pengumuman') }}" class="sidebar-link {{ (request()->routeIs('admin.pengaturan.pengumuman') || request()->routeIs('admin.cms.pengumuman')) ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    Pengumuman
                </a>
                <a href="{{ route('admin.pengaturan.slider') }}" class="sidebar-link {{ (request()->routeIs('admin.pengaturan.slider') || request()->routeIs('admin.cms.slider')) ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Manajemen Slider
                </a>
                <a href="{{ route('admin.pengaturan.infografis') }}" class="sidebar-link {{ request()->routeIs('admin.pengaturan.infografis*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Manajemen Infografis
                </a>
                <a href="{{ route('admin.pengaturan.testimoni') }}" class="sidebar-link {{ request()->routeIs('admin.pengaturan.testimoni*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    Manajemen Testimoni
                </a>
                <a href="{{ route('admin.pengaturan.mitra') }}" class="sidebar-link {{ request()->routeIs('admin.pengaturan.mitra*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Manajemen Mitra
                </a>
                <a href="{{ route('admin.pengaturan.menu') }}" class="sidebar-link {{ request()->routeIs('admin.pengaturan.menu*') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                    Manajemen Menu
                </a>

            @else
                {{-- MAHASISWA --}}
                <p class="sidebar-section-label mt-1 mb-1">Menu Saya</p>
                <a href="{{ route('mahasiswa.dashboard') }}" class="sidebar-link {{ request()->routeIs('mahasiswa.dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    Formulir Lapor Diri
                </a>
                <a href="{{ route('mahasiswa.helpdesk') }}" class="sidebar-link {{ request()->routeIs('mahasiswa.helpdesk') ? 'active' : '' }}">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 100-6 3 3 0 000 6z"/></svg>
                    Help Center & Live
                </a>

                <div class="sidebar-divider"></div>
                <p class="sidebar-section-label mb-1">Informasi</p>
                <a href="{{ route('landing') }}" class="sidebar-link" target="_blank">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Lihat Portal Publik
                </a>
            @endif
        </nav>

        {{-- Logout --}}
        <div class="px-3 py-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-link w-full text-left text-red-300 hover:text-red-100 hover:bg-red-900/30">
                    <svg class="w-4 h-4 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- ===== MAIN CONTENT ===== --}}
    <div class="flex-1 ml-64 flex flex-col min-h-screen">
        {{-- Topbar --}}
        <header role="banner" class="bg-white/80 backdrop-blur border-b border-purple-100 sticky top-0 z-20 px-6 py-3 flex items-center justify-between shadow-sm">
            <div>
                <h1 class="font-bold text-gray-800 text-base">@yield('page-title', 'Dashboard')</h1>
                <p class="text-xs text-gray-400">@yield('page-subtitle', \App\Models\AppSetting::get('app_name','Lapor Diri PPG'))</p>
            </div>
            <div class="flex items-center space-x-3">
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.helpdesk') }}" title="Help Center & Live Call Desk" class="relative p-2 rounded-xl text-gray-500 hover:text-purple-600 hover:bg-purple-50 transition flex items-center group">
                        <svg class="w-5 h-5 admin-notification-bell transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span class="sr-only">Helpdesk Panggilan</span>
                    </a>
                @endif
                <a href="{{ route('landing') }}" target="_blank" class="text-xs text-purple-500 hover:text-purple-700 font-medium flex items-center space-x-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    <span>Lihat Situs</span>
                </a>
                <span class="text-xs text-gray-400">{{ now()->translatedFormat('d F Y') }}</span>
            </div>
        </header>

        <main id="main-content" role="main" class="flex-1 p-6">
            @yield('content')
            {{ $slot ?? '' }}
        </main>

        <footer role="contentinfo" class="text-center text-xs text-gray-400 py-3 border-t border-purple-50 bg-white/50">
            &copy; {{ date('Y') }} {{ \App\Models\AppSetting::get('app_name','PPG') }} — {{ \App\Models\AppSetting::get('app_subtitle','UIN Siber Syekh Nurjati Cirebon') }}
        </footer>
    </div>

    @else
    <main id="main-content" role="main" class="flex-1">
        @yield('content')
        {{ $slot ?? '' }}
    </main>
    @endauth

</div>

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
</script>

<x-incoming-call-alert />

@if(Auth::check() && Auth::user()->role === 'mahasiswa' && !request()->routeIs('mahasiswa.helpdesk'))
    <x-help-center-floating-widget />
@endif

@livewireScripts
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
