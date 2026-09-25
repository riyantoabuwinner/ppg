<div wire:poll.2s class="space-y-6">

    {{-- Alert Messages --}}
    @if (session()->has('success'))
        <div class="flex items-center gap-3 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-sm shadow-sm animate-fadeIn">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('info'))
        <div class="flex items-center gap-3 p-4 rounded-2xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200 text-sm shadow-sm">
            <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="font-medium">{{ session('info') }}</span>
        </div>
    @endif

    {{-- ===== HEADER STATUS & JADWAL OPERASIONAL ===== --}}
    <div class="card p-6 bg-gradient-to-r from-purple-900/90 via-indigo-900/90 to-purple-950 text-white relative overflow-hidden shadow-xl border border-purple-500/20">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2.5 mb-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase {{ $statusInfo['is_open'] ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/40' : 'bg-rose-500/20 text-rose-300 border border-rose-400/40' }}">
                        <span class="w-2 h-2 rounded-full {{ $statusInfo['is_open'] ? 'bg-emerald-400 animate-ping' : 'bg-rose-400' }}"></span>
                        {{ $statusInfo['label'] }}
                    </span>
                    <span class="text-xs text-purple-200/80 font-medium">
                        • {{ $statusInfo['current_date'] }} | {{ $statusInfo['current_time'] }}
                    </span>
                </div>
                <h2 class="text-xl md:text-2xl font-extrabold tracking-tight">
                    Pusat Bantuan & Live Call Desk
                </h2>
                <p class="text-xs md:text-sm text-purple-200/80 mt-1 max-w-2xl">
                    Pantau panggilan masuk dari mahasiswa secara real-time, lakukan obrolan langsung, dan tangani kendala lapor diri PPG dengan cepat.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" wire:click="$set('showScheduleModal', true)" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white/10 hover:bg-white/20 text-white border border-white/20 backdrop-blur-sm transition flex items-center gap-2 shadow-sm cursor-pointer">
                    <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Jadwal Operasional</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ===== KARTU STATISTIK REAL-TIME ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Panggilan Masuk (Calling) --}}
        <div wire:click="switchTab('calling')" class="card p-5 cursor-pointer transition-all duration-200 hover:-translate-y-1 hover:shadow-lg relative overflow-hidden {{ $activeTab === 'calling' ? 'ring-2 ring-red-500 bg-red-50/30 dark:bg-red-950/20' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Panggilan Masuk</p>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-3xl font-extrabold {{ $stats['calling_count'] > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-800 dark:text-white' }}">
                            {{ $stats['calling_count'] }}
                        </span>
                        @if($stats['calling_count'] > 0)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-red-100 dark:bg-red-950 text-red-700 dark:text-red-300 animate-pulse border border-red-300">
                                Berdering!
                            </span>
                        @endif
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl {{ $stats['calling_count'] > 0 ? 'bg-red-500 text-white shadow-lg shadow-red-500/40 animate-bounce' : 'bg-red-100 text-red-600 dark:bg-red-950/50 dark:text-red-400' }} flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
            </div>
            <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-3">
                {{ $stats['calling_count'] > 0 ? 'Segera jawab panggilan sebelum waktu tunggu habis' : 'Tidak ada panggilan yang mengantre' }}
            </p>
        </div>

        {{-- Obrolan Live Aktif (Active) --}}
        <div wire:click="switchTab('active')" class="card p-5 cursor-pointer transition-all duration-200 hover:-translate-y-1 hover:shadow-lg {{ $activeTab === 'active' ? 'ring-2 ring-emerald-500 bg-emerald-50/30 dark:bg-emerald-950/20' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Obrolan Live</p>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-3xl font-extrabold text-emerald-600 dark:text-emerald-400">
                            {{ $stats['active_count'] }}
                        </span>
                        <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">ruang aktif</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
            </div>
            <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-3">
                Sesi konsultasi langsung yang sedang berjalan
            </p>
        </div>

        {{-- Sesi Selesai Hari Ini (Resolved) --}}
        <div wire:click="switchTab('resolved')" class="card p-5 cursor-pointer transition-all duration-200 hover:-translate-y-1 hover:shadow-lg {{ $activeTab === 'resolved' ? 'ring-2 ring-purple-500 bg-purple-50/30 dark:bg-purple-950/20' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Selesai Hari Ini</p>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-3xl font-extrabold text-purple-600 dark:text-purple-400">
                            {{ $stats['resolved_today'] }}
                        </span>
                        <span class="text-xs font-medium text-purple-600 dark:text-purple-400">sesi</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-100 text-purple-600 dark:bg-purple-950/50 dark:text-purple-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-3">
                Terselesaikan dan tuntas oleh Customer Support
            </p>
        </div>

        {{-- Tiket Pengaduan (Legacy) --}}
        <div wire:click="switchTab('tickets')" class="card p-5 cursor-pointer transition-all duration-200 hover:-translate-y-1 hover:shadow-lg {{ $activeTab === 'tickets' ? 'ring-2 ring-indigo-500 bg-indigo-50/30 dark:bg-indigo-950/20' : '' }}">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-slate-400 uppercase tracking-wider">Tiket Asinkron</p>
                    <div class="flex items-baseline gap-2 mt-1">
                        <span class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-400">
                            {{ $tickets->total() }}
                        </span>
                        <span class="text-xs font-medium text-indigo-600 dark:text-indigo-400">tiket</span>
                    </div>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 dark:bg-indigo-950/50 dark:text-indigo-400 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
            <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-3">
                Pesan bantuan offline & formulir pengaduan
            </p>
        </div>
    </div>

    {{-- ===== NAVIGASI TAB UTAMA ===== --}}
    <div class="flex items-center gap-2 border-b border-purple-100 dark:border-slate-800 pb-2 overflow-x-auto">
        <button type="button" wire:click="switchTab('calling')" class="px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 cursor-pointer {{ $activeTab === 'calling' ? 'bg-red-600 text-white shadow-md shadow-red-500/20' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-700' }}">
            <svg class="w-4 h-4 {{ $stats['calling_count'] > 0 ? 'animate-bounce' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            <span>Panggilan Masuk</span>
            @if($stats['calling_count'] > 0)
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-white text-red-600 shadow-sm animate-pulse">
                    {{ $stats['calling_count'] }}
                </span>
            @endif
        </button>

        <button type="button" wire:click="switchTab('active')" class="px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 cursor-pointer {{ $activeTab === 'active' ? 'bg-emerald-600 text-white shadow-md shadow-emerald-500/20' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-700' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            <span>Obrolan Live</span>
            @if($stats['active_count'] > 0)
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800">
                    {{ $stats['active_count'] }}
                </span>
            @endif
        </button>

        <button type="button" wire:click="switchTab('resolved')" class="px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 cursor-pointer {{ $activeTab === 'resolved' ? 'bg-purple-600 text-white shadow-md shadow-purple-500/20' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-700' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            <span>Riwayat Selesai</span>
        </button>

        <button type="button" wire:click="switchTab('tickets')" class="px-4 py-2.5 rounded-xl font-bold text-xs transition flex items-center gap-2 cursor-pointer {{ $activeTab === 'tickets' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-200 hover:bg-gray-100 dark:hover:bg-slate-700' }}">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
            <span>Tiket Pengaduan</span>
        </button>
    </div>

    {{-- ===== KONTEN TAB UTAMA ===== --}}
    @if ($activeTab === 'calling')
        {{-- ===== TAB PANGGILAN MASUK (CALLING) ===== --}}
        <div>
            @if($callingSessions->isEmpty())
                <div class="card p-12 text-center">
                    <div class="w-20 h-20 mx-auto rounded-3xl bg-gray-100 dark:bg-slate-800 text-gray-400 flex items-center justify-center mb-4">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <h3 class="text-base font-bold text-gray-800 dark:text-white">Tidak Ada Panggilan Masuk Saat Ini</h3>
                    <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 max-w-md mx-auto">
                        Antrean panggilan kosong. Sistem akan berdering dan menampilkan notifikasi radar secara otomatis saat mahasiswa menekan tombol panggil bantuan live.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($callingSessions as $calling)
                        <div class="card p-5 border-2 border-red-500/80 bg-white dark:bg-slate-800/90 shadow-xl relative overflow-hidden flex flex-col justify-between">
                            <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-red-500 via-amber-400 to-red-600 animate-pulse"></div>

                            <div>
                                <div class="flex items-center justify-between gap-2 mb-3 pt-1">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-red-100 dark:bg-red-950 text-red-700 dark:text-red-300 border border-red-300 dark:border-red-800">
                                        <span class="w-2 h-2 rounded-full bg-red-500 mr-1.5 animate-ping"></span>
                                        Calling...
                                    </span>
                                    <span class="text-xs font-semibold text-gray-500 dark:text-slate-400">
                                        {{ $calling->formatted_wait_time }}
                                    </span>
                                </div>

                                <div class="flex items-center gap-3 mb-3">
                                    <div class="w-11 h-11 rounded-2xl bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300 flex items-center justify-center font-extrabold text-base flex-shrink-0">
                                        {{ strtoupper(substr($calling->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-extrabold text-gray-900 dark:text-white text-sm truncate">
                                            {{ $calling->user->name ?? 'Pengguna' }}
                                        </h4>
                                        <p class="text-xs text-gray-500 dark:text-slate-400 truncate">
                                            {{ $calling->user->username }} • {{ $calling->user->role === 'mahasiswa' ? 'Mahasiswa PPG' : 'User' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="p-3 rounded-xl bg-gray-50 dark:bg-slate-750/70 border border-gray-100 dark:border-slate-700/60 mb-4">
                                    <div class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Topik Kendala</div>
                                    <div class="text-xs font-bold text-purple-950 dark:text-purple-200 mt-0.5">
                                        {{ $calling->topic }}
                                    </div>
                                    <div class="text-[10px] text-gray-400 mt-1">
                                        Kode Sesi: <span class="font-mono text-gray-600 dark:text-slate-300">{{ $calling->session_code }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 pt-2 border-t border-gray-100 dark:border-slate-700/60">
                                <button type="button" wire:click="acceptCall({{ $calling->id }})" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 shadow-md shadow-emerald-600/30 transition cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Terima Panggilan</span>
                                </button>
                                <button type="button" wire:click="closeSession({{ $calling->id }})" title="Tolak / Tutup Sesi" class="p-2.5 rounded-xl text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/40 transition cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

    @elseif ($activeTab === 'active')
        {{-- ===== TAB OBROLAN LIVE (ACTIVE DUA KOLOM SPLIT SCREEN) ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
            
            {{-- Kolom Kiri: Daftar Ruang Aktif --}}
            <div class="lg:col-span-4 space-y-3">
                <div class="flex items-center justify-between px-1">
                    <h3 class="font-bold text-gray-800 dark:text-white text-sm">
                        Ruang Obrolan Aktif ({{ $activeSessions->count() }})
                    </h3>
                </div>

                @if($activeSessions->isEmpty())
                    <div class="card p-8 text-center">
                        <div class="w-12 h-12 mx-auto rounded-2xl bg-gray-100 dark:bg-slate-800 text-gray-400 flex items-center justify-center mb-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <p class="text-xs font-semibold text-gray-600 dark:text-slate-300">Belum Ada Sesi Aktif</p>
                        <p class="text-[11px] text-gray-400 mt-1">Terima panggilan masuk pada tab "Panggilan Masuk" untuk memulai obrolan langsung.</p>
                    </div>
                @else
                    <div class="space-y-2 max-h-[650px] overflow-y-auto pr-1">
                        @foreach($activeSessions as $ses)
                            @php
                                $unread = $ses->unreadCountForAdmin();
                                $isSelected = $selectedSessionId === $ses->id;
                            @endphp
                            <div wire:click="selectSession({{ $ses->id }})" class="card p-3.5 cursor-pointer transition-all duration-150 hover:border-purple-300 dark:hover:border-purple-700 relative {{ $isSelected ? 'ring-2 ring-purple-600 bg-purple-50/50 dark:bg-purple-950/30' : 'bg-white dark:bg-slate-800' }}">
                                <div class="flex items-start gap-3">
                                    <div class="relative flex-shrink-0">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-600 to-indigo-600 text-white font-bold text-sm flex items-center justify-center shadow-sm">
                                            {{ strtoupper(substr($ses->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-500 border-2 border-white dark:border-slate-800"></span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-1">
                                            <h4 class="font-bold text-gray-900 dark:text-white text-xs truncate">
                                                {{ $ses->user->name ?? 'Pengguna' }}
                                            </h4>
                                            <span class="text-[10px] text-gray-400 whitespace-nowrap">
                                                {{ $ses->last_message_at ? $ses->last_message_at->format('H:i') : $ses->created_at->format('H:i') }}
                                            </span>
                                        </div>
                                        <p class="text-[11px] text-purple-600 dark:text-purple-400 font-semibold truncate mt-0.5">
                                            {{ $ses->topic }}
                                        </p>
                                        <p class="text-[11px] text-gray-500 dark:text-slate-400 truncate mt-1">
                                            {{ $ses->latestMessage ? $ses->latestMessage->message : 'Belum ada pesan' }}
                                        </p>
                                    </div>
                                    @if($unread > 0)
                                        <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-red-500 text-white text-[10px] font-extrabold flex-shrink-0 animate-pulse">
                                            {{ $unread }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Kolom Kanan: Ruang Percakapan Live --}}
            <div class="lg:col-span-8">
                @if($activeSessionRecord)
                    <div class="card bg-white dark:bg-slate-800 flex flex-col h-[700px] border border-purple-100 dark:border-slate-700 shadow-xl overflow-hidden rounded-2xl">
                        
                        {{-- Chat Room Header --}}
                        <div class="p-4 border-b border-purple-100 dark:border-slate-700/80 bg-purple-50/50 dark:bg-slate-900/60 flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-purple-600 to-indigo-700 text-white font-extrabold flex items-center justify-center text-base shadow-sm flex-shrink-0">
                                    {{ strtoupper(substr($activeSessionRecord->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-extrabold text-gray-900 dark:text-white text-sm truncate">
                                            {{ $activeSessionRecord->user->name ?? 'Pengguna' }}
                                        </h3>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1 animate-ping"></span>
                                            Terhubung Live
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-slate-400 mt-0.5">
                                        <span class="font-medium text-purple-600 dark:text-purple-400">{{ $activeSessionRecord->topic }}</span>
                                        <span>•</span>
                                        <span class="font-mono text-[11px]">{{ $activeSessionRecord->session_code }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2 flex-shrink-0">
                                <button type="button" wire:click="resolveSession({{ $activeSessionRecord->id }})" wire:confirm="Apakah Anda yakin ingin menyelesaikan sesi bantuan live ini?" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl font-bold text-xs text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 shadow-sm transition cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    <span>Akhiri & Selesaikan</span>
                                </button>
                                <button type="button" wire:click="closeSession({{ $activeSessionRecord->id }})" wire:confirm="Tutup sesi panggilan ini?" title="Tutup Sesi" class="p-2 rounded-xl text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-slate-700 transition cursor-pointer">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Quick Replies Bar --}}
                        <div class="px-4 py-2 bg-gray-50 dark:bg-slate-900/40 border-b border-gray-100 dark:border-slate-800 flex items-center gap-2 overflow-x-auto text-xs">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-purple-600 dark:text-purple-400 flex items-center gap-1 whitespace-nowrap">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Balas Cepat:
                            </span>
                            @foreach($quickReplies as $qTitle => $qText)
                                <button type="button" wire:click="applyQuickReply('{{ $qTitle }}')" title="{{ $qText }}" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-800 hover:bg-purple-100 dark:hover:bg-purple-900/40 text-gray-700 dark:text-slate-200 border border-gray-200 dark:border-slate-700 text-[11px] font-medium transition whitespace-nowrap cursor-pointer">
                                    {{ $qTitle }}
                                </button>
                            @endforeach
                        </div>

                        {{-- Chat Messages Scrollable Area --}}
                        <div id="admin-chat-messages-container" class="flex-1 p-4 overflow-y-auto space-y-3.5 bg-gradient-to-b from-gray-50/50 to-white dark:from-slate-900/20 dark:to-slate-900/60">
                            @forelse($activeSessionRecord->messages as $msg)
                                <div class="flex {{ $msg->is_admin ? 'justify-end' : 'justify-start' }} items-end gap-2">
                                    
                                    @if(!$msg->is_admin)
                                        <div class="w-7 h-7 rounded-lg bg-purple-200 dark:bg-purple-900/60 text-purple-800 dark:text-purple-200 text-xs font-bold flex items-center justify-center flex-shrink-0">
                                            {{ strtoupper(substr($msg->sender->name ?? 'U', 0, 1)) }}
                                        </div>
                                    @endif

                                    <div class="max-w-[78%] rounded-2xl p-3.5 shadow-sm text-xs {{ $msg->is_admin ? 'bg-gradient-to-r from-purple-700 to-indigo-700 text-white rounded-br-none' : 'bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-100 border border-gray-200/80 dark:border-slate-700 rounded-bl-none' }}">
                                        
                                        @if(!$msg->is_admin)
                                            <div class="text-[10px] font-bold text-purple-600 dark:text-purple-400 mb-1">
                                                {{ $msg->sender->name ?? 'Pengguna' }}
                                            </div>
                                        @endif

                                        <p class="whitespace-pre-line leading-relaxed">{{ $msg->message }}</p>

                                        {{-- Attachment Preview --}}
                                        @if($msg->hasAttachment())
                                            <div class="mt-2.5 pt-2 border-t {{ $msg->is_admin ? 'border-white/20' : 'border-gray-200 dark:border-slate-700' }}">
                                                @if($msg->isImageAttachment())
                                                    <a href="{{ $msg->attachment_url }}" target="_blank" class="block group overflow-hidden rounded-xl">
                                                        <img src="{{ $msg->attachment_url }}" alt="Lampiran" class="max-h-48 rounded-xl object-cover hover:scale-105 transition-transform">
                                                    </a>
                                                @else
                                                    <a href="{{ $msg->attachment_url }}" target="_blank" download class="inline-flex items-center gap-2 p-2 rounded-xl {{ $msg->is_admin ? 'bg-white/10 text-white hover:bg-white/20' : 'bg-gray-100 dark:bg-slate-700 text-gray-800 dark:text-slate-200 hover:bg-gray-200' }} transition">
                                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                        <span class="truncate font-semibold max-w-[180px]">{{ $msg->attachment_name ?: 'Unduh Berkas' }}</span>
                                                    </a>
                                                @endif
                                            </div>
                                        @endif

                                        {{-- Timestamp & Read Receipt --}}
                                        <div class="flex items-center justify-end gap-1 mt-1 text-[10px] {{ $msg->is_admin ? 'text-purple-200' : 'text-gray-400' }}">
                                            <span>{{ $msg->created_at->format('H:i') }}</span>
                                            @if($msg->is_admin)
                                                @if($msg->is_read)
                                                    <span title="Telah dibaca pengguna" class="text-blue-300">✓✓</span>
                                                @else
                                                    <span title="Terkirim" class="text-purple-300">✓</span>
                                                @endif
                                            @endif
                                        </div>
                                    </div>

                                    @if($msg->is_admin)
                                        <div class="w-7 h-7 rounded-lg bg-purple-700 text-white text-xs font-bold flex items-center justify-center flex-shrink-0">
                                            CS
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center py-10 text-gray-400 text-xs">
                                    Belum ada percakapan dalam sesi ini. Kirimkan pesan atau salam pembuka di bawah.
                                </div>
                            @endforelse
                        </div>

                        {{-- Attachment Preview Pending Upload --}}
                        @if($chatAttachment)
                            <div class="px-4 py-2 bg-purple-50 dark:bg-purple-950/40 border-t border-purple-200 dark:border-purple-800 flex items-center justify-between text-xs">
                                <div class="flex items-center gap-2 text-purple-800 dark:text-purple-200 truncate">
                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                    <span class="truncate font-semibold">{{ $chatAttachment->getClientOriginalName() }}</span>
                                </div>
                                <button type="button" wire:click="$set('chatAttachment', null)" class="text-red-500 hover:text-red-700 font-bold p-1 cursor-pointer">
                                    &times; Batal
                                </button>
                            </div>
                        @endif

                        {{-- Chat Input Form --}}
                        <form wire:submit.prevent="sendChatMessage" class="p-3 bg-white dark:bg-slate-800 border-t border-purple-100 dark:border-slate-700 flex items-center gap-2">
                            {{-- Upload Button --}}
                            <label class="p-2.5 rounded-xl text-gray-500 hover:text-purple-600 hover:bg-purple-50 dark:hover:bg-slate-700 transition cursor-pointer relative" title="Lampirkan Dokumen / Gambar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                                <input type="file" wire:model="chatAttachment" class="hidden" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.zip">
                            </label>

                            {{-- Input Text --}}
                            <input type="text" wire:model="chatMessage" placeholder="Ketik pesan balasan... (Tekan Enter untuk mengirim)" class="flex-1 px-4 py-2.5 rounded-xl border border-purple-200 dark:border-slate-700 text-xs focus:ring-2 focus:ring-purple-500 dark:bg-slate-900 dark:text-white">

                            {{-- Send Button --}}
                            <button type="submit" wire:loading.attr="disabled" class="px-5 py-2.5 rounded-xl font-bold text-xs text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 shadow-md shadow-purple-600/30 transition flex items-center gap-1.5 cursor-pointer disabled:opacity-50">
                                <span wire:loading.remove wire:target="sendChatMessage">Kirim</span>
                                <span wire:loading wire:target="sendChatMessage">...</span>
                                <svg wire:loading.remove wire:target="sendChatMessage" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </button>
                        </form>

                    </div>
                @else
                    <div class="card p-16 text-center">
                        <div class="w-16 h-16 mx-auto rounded-3xl bg-purple-100 dark:bg-purple-950/40 text-purple-600 flex items-center justify-center mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        </div>
                        <h3 class="text-base font-bold text-gray-800 dark:text-white">Pilih Ruang Obrolan di Sebelah Kiri</h3>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 max-w-sm mx-auto">
                            Klik salah satu ruang obrolan aktif untuk membuka percakapan real-time dengan mahasiswa.
                        </p>
                    </div>
                @endif
            </div>

        </div>

    @elseif ($activeTab === 'resolved')
        {{-- ===== TAB RIWAYAT SESI SELESAI ===== --}}
        <div class="space-y-4">
            {{-- Filter Bar --}}
            <div class="card p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="relative flex-1 max-w-md">
                    <input type="text" wire:model.live.debounce.300ms="searchKeyword" placeholder="Cari kode sesi, nama pemanggil, atau topik..." class="w-full pl-9 pr-4 py-2 rounded-xl text-xs border border-purple-200 dark:border-slate-700">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <div class="text-xs text-gray-500 dark:text-slate-400 font-medium">
                    Total: <strong class="text-gray-800 dark:text-white">{{ $historySessions->total() }}</strong> sesi selesai
                </div>
            </div>

            {{-- Table Riwayat --}}
            <div class="card overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-purple-50/75 dark:bg-slate-800 border-b border-purple-100 dark:border-slate-700 text-gray-600 dark:text-slate-300 font-bold uppercase tracking-wider">
                            <tr>
                                <th class="p-3.5">Kode Sesi</th>
                                <th class="p-3.5">Pemanggil</th>
                                <th class="p-3.5">Topik Masalah</th>
                                <th class="p-3.5">Ditangani Oleh</th>
                                <th class="p-3.5">Waktu Selesai</th>
                                <th class="p-3.5">Status</th>
                                <th class="p-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-slate-800">
                            @forelse($historySessions as $hist)
                                <tr class="hover:bg-purple-50/30 dark:hover:bg-slate-800/40 transition">
                                    <td class="p-3.5 font-mono font-bold text-purple-700 dark:text-purple-300">
                                        {{ $hist->session_code }}
                                    </td>
                                    <td class="p-3.5">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $hist->user->name ?? '-' }}</div>
                                        <div class="text-[11px] text-gray-400">{{ $hist->user->username ?? '' }}</div>
                                    </td>
                                    <td class="p-3.5 font-medium text-gray-800 dark:text-slate-200">
                                        {{ $hist->topic }}
                                    </td>
                                    <td class="p-3.5 text-gray-600 dark:text-slate-300">
                                        {{ $hist->admin->name ?? 'Admin' }}
                                    </td>
                                    <td class="p-3.5 text-gray-500 dark:text-slate-400">
                                        {{ $hist->ended_at ? $hist->ended_at->translatedFormat('d M Y, H:i') : '-' }}
                                    </td>
                                    <td class="p-3.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $hist->status === 'resolved' ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $hist->status === 'resolved' ? 'Selesai' : 'Ditutup' }}
                                        </span>
                                    </td>
                                    <td class="p-3.5 text-right">
                                        <button type="button" wire:click="selectSession({{ $hist->id }}); $set('activeTab', 'active');" class="px-3 py-1.5 rounded-lg bg-purple-50 dark:bg-slate-700 text-purple-700 dark:text-purple-300 hover:bg-purple-100 font-bold text-[11px] transition cursor-pointer">
                                            Lihat Transkrip
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-8 text-center text-gray-400 text-xs">
                                        Belum ada riwayat sesi bantuan selesai yang ditemukan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($historySessions->hasPages())
                    <div class="p-4 border-t border-purple-100 dark:border-slate-800">
                        {{ $historySessions->links() }}
                    </div>
                @endif
            </div>
        </div>

    @elseif ($activeTab === 'tickets')
        {{-- ===== TAB TIKET PENGADUAN LAMA (ASYNC) ===== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- List Tiket --}}
            <div class="lg:col-span-1 space-y-3">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-gray-800 dark:text-white text-sm">Daftar Tiket ({{ $tickets->total() }})</h3>
                    <select wire:model.live="ticketStatusFilter" class="text-xs py-1 px-2.5 rounded-lg border border-purple-200">
                        <option value="">Semua Status</option>
                        <option value="open">Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>

                <div class="space-y-2 max-h-[600px] overflow-y-auto">
                    @forelse($tickets as $t)
                        <div wire:click="openTicket({{ $t->id }})" class="card p-3.5 cursor-pointer hover:border-purple-400 transition {{ $selectedTicket?->id === $t->id ? 'ring-2 ring-purple-600 bg-purple-50/40' : '' }}">
                            <div class="flex justify-between items-start gap-1">
                                <h4 class="font-bold text-xs text-gray-900 truncate">{{ $t->subject }}</h4>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $t->status === 'open' ? 'bg-amber-100 text-amber-800' : ($t->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600') }}">
                                    {{ $t->status }}
                                </span>
                            </div>
                            <p class="text-[11px] text-gray-500 mt-1 truncate">{{ $t->user->name ?? '-' }} ({{ $t->kategori }})</p>
                            <span class="text-[10px] text-gray-400">{{ $t->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <div class="card p-6 text-center text-xs text-gray-400">Tidak ada tiket pengaduan.</div>
                    @endforelse
                </div>
            </div>

            {{-- Detail Tiket --}}
            <div class="lg:col-span-2">
                @if($selectedTicket)
                    <div class="card p-5 space-y-4">
                        <div class="flex justify-between items-start border-b pb-3">
                            <div>
                                <h3 class="font-bold text-gray-900 text-base">{{ $selectedTicket->subject }}</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Dari: {{ $selectedTicket->user->name }} • {{ $selectedTicket->kategori }} • {{ $selectedTicket->created_at->translatedFormat('d F Y, H:i') }}</p>
                            </div>
                            <button wire:click="closeTicket({{ $selectedTicket->id }})" class="text-xs text-red-600 hover:underline font-bold">Tutup Tiket</button>
                        </div>

                        <div class="p-3.5 rounded-xl bg-gray-50 text-xs text-gray-800 whitespace-pre-line leading-relaxed">
                            {{ $selectedTicket->pesan }}
                        </div>

                        {{-- Balasan --}}
                        <div class="space-y-3 max-h-72 overflow-y-auto pr-1">
                            @foreach($selectedTicket->replies as $r)
                                <div class="p-3 rounded-xl text-xs {{ $r->user_id === Auth::id() ? 'bg-purple-50 text-purple-900 border border-purple-200 ml-6' : 'bg-gray-100 text-gray-800 mr-6' }}">
                                    <div class="font-bold text-[11px] mb-1 flex justify-between">
                                        <span>{{ $r->user->name ?? 'User' }}</span>
                                        <span class="text-gray-400 font-normal">{{ $r->created_at->format('d/m H:i') }}</span>
                                    </div>
                                    <p class="whitespace-pre-line">{{ $r->pesan }}</p>
                                </div>
                            @endforeach
                        </div>

                        {{-- Form Balas --}}
                        <form wire:submit.prevent="sendTicketReply" class="space-y-2 pt-2 border-t">
                            <textarea wire:model="replyPesan" rows="3" placeholder="Tulis balasan tiket untuk mahasiswa..." class="w-full p-3 rounded-xl border border-purple-200 text-xs"></textarea>
                            <button type="submit" class="btn-primary px-4 py-2 text-xs font-bold">Kirim Balasan Tiket</button>
                        </form>
                    </div>
                @else
                    <div class="card p-12 text-center text-xs text-gray-400">
                        Pilih tiket di sebelah kiri untuk melihat percakapan dan membalas.
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- ===== MODAL JADWAL OPERASIONAL ===== --}}
    @if($showScheduleModal)
        <div class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm animate-fadeIn">
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-purple-100 dark:border-slate-800 relative">
                
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-slate-800 mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-base text-gray-900 dark:text-white">Jadwal Operasional Help Center</h3>
                            <p class="text-xs text-purple-600 dark:text-purple-400 font-semibold">Zona Waktu: Asia/Jakarta (WIB)</p>
                        </div>
                    </div>
                    <button type="button" wire:click="$set('showScheduleModal', false)" class="text-gray-400 hover:text-gray-600 dark:hover:text-slate-200 p-1 rounded-lg cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-3 text-xs mb-6">
                    @foreach($scheduleDetails as $sch)
                        <div class="p-3.5 rounded-2xl border {{ $sch['is_active'] ? 'bg-purple-50 dark:bg-purple-950/40 border-purple-200 dark:border-purple-800' : 'bg-gray-50 dark:bg-slate-800/60 border-gray-100 dark:border-slate-800' }}">
                            <div class="flex items-center justify-between font-bold text-gray-800 dark:text-white mb-1">
                                <span>{{ $sch['day'] }}</span>
                                @if($sch['is_active'])
                                    <span class="px-2 py-0.5 rounded-full text-[10px] bg-purple-600 text-white font-extrabold">Hari Ini</span>
                                @endif
                            </div>
                            <div class="text-purple-700 dark:text-purple-300 font-semibold">{{ $sch['hours'] }}</div>
                            <div class="text-gray-500 dark:text-slate-400 text-[11px] mt-0.5">Istirahat: {{ $sch['break'] }}</div>
                        </div>
                    @endforeach
                </div>

                <button type="button" wire:click="$set('showScheduleModal', false)" class="w-full py-2.5 rounded-xl font-bold text-xs text-white bg-purple-600 hover:bg-purple-700 transition cursor-pointer">
                    Tutup Pengaturan Jadwal
                </button>
            </div>
        </div>
    @endif

</div>
