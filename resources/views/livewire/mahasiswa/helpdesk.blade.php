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

    @if (session()->has('error'))
        <div class="flex items-center gap-3 p-4 rounded-2xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 text-sm shadow-sm">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ===== HEADER STATUS JAM OPERASIONAL ===== --}}
    <div class="card p-6 bg-gradient-to-r from-purple-900 via-indigo-900 to-purple-950 text-white relative overflow-hidden shadow-xl border border-purple-500/20">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase {{ $statusInfo['is_open'] ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-400/40' : 'bg-rose-500/20 text-rose-300 border border-rose-400/40' }}">
                        <span class="w-2 h-2 rounded-full {{ $statusInfo['is_open'] ? 'bg-emerald-400 animate-ping' : 'bg-rose-400' }}"></span>
                        {{ $statusInfo['label'] }}
                    </span>
                    <span class="text-xs text-purple-200/80 font-medium">
                        • {{ $statusInfo['current_date'] }} | {{ $statusInfo['current_time'] }}
                    </span>
                </div>
                <h2 class="text-xl md:text-2xl font-extrabold tracking-tight">
                    Pusat Bantuan & Live Call Support
                </h2>
                <p class="text-xs md:text-sm text-purple-200/80 mt-1 max-w-2xl">
                    Hubungi Customer Support Panitia PPG UIN Siber Syekh Nurjati Cirebon untuk konsultasi langsung kendala berkas, verifikasi dokumen, atau kendala sistem.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <button type="button" wire:click="$set('showScheduleModal', true)" class="px-4 py-2 rounded-xl text-xs font-semibold bg-white/10 hover:bg-white/20 text-white border border-white/20 backdrop-blur-sm transition flex items-center gap-2 cursor-pointer shadow-sm">
                    <svg class="w-4 h-4 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Jadwal Buka</span>
                </button>
            </div>
        </div>
    </div>

    {{-- =========================================================
         LOGIKA 4 KONDISI TAMPILAN PENGGUNA (USER)
         ========================================================= --}}

    @if($activeSession && $activeSession->status === 'calling')
        {{-- =========================================================
             KONDISI 3: STATUS CALLING (MENUNGGU ADMIN MENJAWAB)
             ========================================================= --}}
        <div class="card p-8 md:p-12 text-center bg-white dark:bg-slate-900 border-2 border-purple-500/30 shadow-2xl relative overflow-hidden">
            
            {{-- Radar Pulse Audio Wave Animation --}}
            <div class="relative w-36 h-36 mx-auto mb-6 flex items-center justify-center">
                <!-- Ping Outer Rings -->
                <div class="absolute inset-0 rounded-full bg-purple-500/15 animate-ping duration-1000"></div>
                <div class="absolute -inset-4 rounded-full bg-purple-600/10 animate-pulse"></div>
                <div class="absolute -inset-8 rounded-full border border-purple-400/30 animate-pulse"></div>
                
                <!-- Center Glowing Beacon -->
                <div class="relative w-20 h-20 rounded-full bg-gradient-to-tr from-purple-700 via-purple-600 to-indigo-600 text-white flex items-center justify-center shadow-xl shadow-purple-500/40">
                    <svg class="w-9 h-9 animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
            </div>

            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-red-100 dark:bg-red-950/60 text-red-700 dark:text-red-300 border border-red-300 mb-3">
                <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                Memanggil Admin Customer Support...
            </div>

            <h3 class="text-xl md:text-2xl font-black text-gray-900 dark:text-white tracking-tight">
                Panggilan Live Anda Sedang Berdering
            </h3>
            
            <p class="text-xs md:text-sm text-gray-500 dark:text-slate-400 max-w-lg mx-auto mt-2 leading-relaxed">
                Pemberitahuan panggilan bantuan langsung telah dikirimkan ke layar Admin panitia. Mohon jangan menutup halaman ini, panggilan akan terhubung otomatis saat Admin menjawab.
            </p>

            <div class="mt-6 inline-flex flex-col sm:flex-row items-center gap-3 p-4 rounded-2xl bg-purple-50/70 dark:bg-slate-800 border border-purple-100 dark:border-slate-700 text-xs">
                <div>
                    <span class="text-gray-400 font-medium">Kode Sesi:</span>
                    <strong class="font-mono text-purple-700 dark:text-purple-300 ml-1">{{ $activeSession->session_code }}</strong>
                </div>
                <span class="hidden sm:inline text-gray-300">•</span>
                <div>
                    <span class="text-gray-400 font-medium">Topik:</span>
                    <strong class="text-gray-800 dark:text-slate-200 ml-1">{{ $activeSession->topic }}</strong>
                </div>
                <span class="hidden sm:inline text-gray-300">•</span>
                <div>
                    <span class="text-gray-400 font-medium">Waktu Tunggu:</span>
                    <strong class="text-emerald-600 dark:text-emerald-400 ml-1">{{ $activeSession->formatted_wait_time }}</strong>
                </div>
            </div>

            <div class="mt-8 flex justify-center">
                <button type="button" wire:click="cancelCall" wire:confirm="Batalkan panggilan bantuan live ini?" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold text-xs text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-950/40 hover:bg-red-100 dark:hover:bg-red-900/60 border border-red-200 dark:border-red-800 transition cursor-pointer shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    <span>Batalkan Panggilan</span>
                </button>
            </div>
        </div>

    @elseif($activeSession && $activeSession->status === 'active')
        {{-- =========================================================
             KONDISI 4: TERHUBUNG LIVE (ACTIVE ROOM)
             ========================================================= --}}
        <div class="card bg-white dark:bg-slate-800 border border-purple-100 dark:border-slate-700 shadow-2xl rounded-3xl overflow-hidden flex flex-col h-[720px]">
            
            {{-- Room Header --}}
            <div class="p-4 md:p-5 border-b border-purple-100 dark:border-slate-700 bg-purple-50/60 dark:bg-slate-900/60 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3.5 min-w-0">
                    <div class="relative flex-shrink-0">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-600 text-white font-extrabold flex items-center justify-center text-lg shadow-md shadow-emerald-600/30">
                            {{ strtoupper(substr($activeSession->admin->name ?? 'CS', 0, 1)) }}
                        </div>
                        <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-white dark:border-slate-900"></span>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2">
                            <h3 class="font-extrabold text-gray-900 dark:text-white text-sm md:text-base truncate">
                                {{ $activeSession->admin->name ?? 'Admin Customer Support' }}
                            </h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-300">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1 animate-ping"></span>
                                Live Room
                            </span>
                        </div>
                        <p class="text-xs text-purple-600 dark:text-purple-400 font-medium truncate mt-0.5">
                            Topik: {{ $activeSession->topic }} • <span class="font-mono text-[11px] text-gray-400">{{ $activeSession->session_code }}</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0">
                    <button type="button" wire:click="endCall" wire:confirm="Akhiri sesi panggilan live ini?" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl font-bold text-xs text-white bg-gradient-to-r from-red-600 to-rose-600 hover:from-red-500 hover:to-rose-500 shadow-md shadow-red-500/20 transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M5 3a2 2 0 00-2 2v1c0 8.284 6.716 15 15 15h1a2 2 0 002-2v-3.28a1 1 0 00-.684-.948l-4.493-1.498a1 1 0 00-1.21.502l-1.13 2.257a11.042 11.042 0 01-5.516-5.517l2.257-1.128a1 1 0 00.502-1.21L9.228 3.684A1 1 0 008.28 3H5z"/></svg>
                        <span class="hidden sm:inline">Akhiri Panggilan</span>
                    </button>
                </div>
            </div>

            {{-- Thread Pesan Real-time --}}
            <div id="user-chat-messages" class="flex-1 p-4 md:p-6 overflow-y-auto space-y-4 bg-gradient-to-b from-gray-50/50 to-white dark:from-slate-900/30 dark:to-slate-900/70">
                @forelse($activeSession->messages as $m)
                    <div class="flex {{ $m->is_admin ? 'justify-start' : 'justify-end' }} items-end gap-2.5">
                        
                        @if($m->is_admin)
                            <div class="w-8 h-8 rounded-xl bg-purple-700 text-white font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-sm">
                                CS
                            </div>
                        @endif

                        <div class="max-w-[82%] sm:max-w-[70%] rounded-2xl p-4 shadow-sm text-xs leading-relaxed {{ $m->is_admin ? 'bg-white dark:bg-slate-800 text-gray-800 dark:text-slate-100 border border-gray-200/80 dark:border-slate-700 rounded-bl-none' : 'bg-gradient-to-r from-purple-700 to-indigo-700 text-white rounded-br-none' }}">
                            
                            @if($m->is_admin)
                                <div class="text-[10px] font-extrabold text-purple-600 dark:text-purple-400 mb-1 flex items-center gap-1.5">
                                    <span>{{ $m->sender->name ?? 'Panitia PPG' }}</span>
                                    <span class="px-1.5 py-0.2 rounded bg-purple-100 dark:bg-purple-950 text-purple-700 text-[9px] font-bold">Admin CS</span>
                                </div>
                            @endif

                            <p class="whitespace-pre-line">{{ $m->message }}</p>

                            {{-- Lampiran Berkas --}}
                            @if($m->hasAttachment())
                                <div class="mt-2.5 pt-2.5 border-t {{ $m->is_admin ? 'border-gray-200 dark:border-slate-700' : 'border-white/20' }}">
                                    @if($m->isImageAttachment())
                                        <a href="{{ $m->attachment_url }}" target="_blank" class="block overflow-hidden rounded-xl group">
                                            <img src="{{ $m->attachment_url }}" alt="Lampiran" class="max-h-56 rounded-xl object-cover hover:scale-105 transition-transform">
                                        </a>
                                    @else
                                        <a href="{{ $m->attachment_url }}" target="_blank" download class="inline-flex items-center gap-2 p-2 rounded-xl {{ $m->is_admin ? 'bg-gray-100 dark:bg-slate-700 text-gray-800 dark:text-slate-100' : 'bg-white/10 text-white hover:bg-white/20' }} transition">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span class="truncate font-semibold max-w-[200px]">{{ $m->attachment_name ?: 'Unduh Berkas' }}</span>
                                        </a>
                                    @endif
                                </div>
                            @endif

                            <div class="flex items-center justify-end gap-1 mt-1 text-[10px] {{ $m->is_admin ? 'text-gray-400' : 'text-purple-200' }}">
                                <span>{{ $m->created_at->format('H:i') }}</span>
                                @if(!$m->is_admin)
                                    @if($m->is_read)
                                        <span title="Dibaca admin" class="text-blue-300 font-bold">✓✓</span>
                                    @else
                                        <span title="Terkirim" class="text-purple-300">✓</span>
                                    @endif
                                @endif
                            </div>
                        </div>

                        @if(!$m->is_admin)
                            <div class="w-8 h-8 rounded-xl bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 font-bold text-xs flex items-center justify-center flex-shrink-0 shadow-sm">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12 text-gray-400 text-xs">
                        Ruang obrolan live siap. Kirimkan pesan atau ajukan pertanyaan kepada Admin.
                    </div>
                @endforelse
            </div>

            {{-- Lampiran Pending Upload --}}
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
            <form wire:submit.prevent="sendChatMessage" class="p-3 md:p-4 bg-white dark:bg-slate-800 border-t border-purple-100 dark:border-slate-700 flex items-center gap-2">
                <label class="p-2.5 rounded-xl text-gray-500 hover:text-purple-600 hover:bg-purple-50 dark:hover:bg-slate-700 transition cursor-pointer relative" title="Kirim Screenshot / Berkas">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                    <input type="file" wire:model="chatAttachment" class="hidden" accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.zip">
                </label>

                <input type="text" wire:model="chatMessage" placeholder="Ketik pesan Anda... (Tekan Enter untuk kirim)" class="flex-1 px-4 py-2.5 rounded-xl border border-purple-200 dark:border-slate-700 text-xs focus:ring-2 focus:ring-purple-500 dark:bg-slate-900 dark:text-white">

                <button type="submit" wire:loading.attr="disabled" class="px-5 py-2.5 rounded-xl font-bold text-xs text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 shadow-md shadow-purple-600/30 transition flex items-center gap-1.5 cursor-pointer disabled:opacity-50">
                    <span wire:loading.remove wire:target="sendChatMessage">Kirim</span>
                    <span wire:loading wire:target="sendChatMessage">...</span>
                    <svg wire:loading.remove wire:target="sendChatMessage" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </form>
        </div>

    @elseif($activeSession && in_array($activeSession->status, ['resolved', 'closed']))
        {{-- =========================================================
             SESI SELESAI / TRANSAKSI DITUTUP
             ========================================================= --}}
        <div class="card p-8 md:p-12 text-center bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800 shadow-xl rounded-3xl">
            <div class="w-20 h-20 mx-auto rounded-3xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-4">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-xl font-extrabold text-gray-900 dark:text-white">Sesi Panggilan Telah Selesai</h3>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-2 max-w-md mx-auto">
                Terima kasih telah berkonsultasi dengan Help Center Lapor Diri PPG. Kami harap kendala Anda telah tertangani dengan baik.
            </p>
            <div class="mt-6 flex justify-center gap-3">
                <button type="button" wire:click="startNewCallSession" class="btn-primary px-6 py-2.5 text-xs font-bold cursor-pointer">
                    Mulai Panggilan Baru
                </button>
            </div>
        </div>

    @else
        {{-- =========================================================
             KONDISI 1 & 2: STATUS STANDBY / BELUM ADA CALLING
             ========================================================= --}}
        
        @if(!$statusInfo['is_open'])
            {{-- KONDISI 1: DI LUAR JAM KERJA (OFFLINE) --}}
            <div class="card p-8 bg-gradient-to-br from-amber-500/10 via-rose-500/10 to-purple-500/10 border-2 border-amber-300 dark:border-amber-700/60 rounded-3xl">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <div class="w-20 h-20 rounded-3xl bg-amber-500 text-white flex items-center justify-center flex-shrink-0 shadow-lg shadow-amber-500/30">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase bg-amber-200 dark:bg-amber-950 text-amber-900 dark:text-amber-200 mb-1">
                            Layanan Sedang Tutup (Offline)
                        </span>
                        <h3 class="text-lg md:text-xl font-black text-gray-900 dark:text-white">
                            Help Center Sedang Di Luar Jam Operasional
                        </h3>
                        <p class="text-xs text-gray-600 dark:text-slate-300 mt-1.5 leading-relaxed">
                            Panggilan Live Support saat ini sedang offline. Layanan customer support beroperasi setiap hari kerja:
                            <strong class="text-purple-700 dark:text-purple-300">Senin – Kamis (08:00–11:30 & 13:00–16:00 WIB)</strong> dan 
                            <strong class="text-purple-700 dark:text-purple-300">Jum'at (08:00–11:00 & 13:30–16:30 WIB)</strong>.
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row md:flex-col gap-2 w-full md:w-auto">
                        <button type="button" wire:click="$set('userViewMode', 'ticket_form')" class="px-4 py-2.5 rounded-xl font-bold text-xs text-white bg-purple-700 hover:bg-purple-800 transition text-center shadow-md shadow-purple-700/20 cursor-pointer">
                            Kirim Pesan Bantuan Offline
                        </button>
                        <a href="{{ route('landing') }}" class="px-4 py-2.5 rounded-xl font-bold text-xs text-gray-700 dark:text-slate-200 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-700 hover:bg-gray-50 transition text-center">
                            Lihat Info & FAQ Publik
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- Mode Switcher (Panggilan Live vs Kirim Pesan Offline) --}}
        <div class="flex items-center gap-2 border-b border-purple-100 dark:border-slate-800 pb-3">
            <button type="button" wire:click="$set('userViewMode', 'live')" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer {{ $userViewMode === 'live' ? 'bg-purple-600 text-white shadow-md shadow-purple-600/20' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-200 hover:bg-gray-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <span>Panggilan Bantuan Langsung (Live Desk)</span>
            </button>
            <button type="button" wire:click="$set('userViewMode', 'ticket_form')" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer {{ $userViewMode === 'ticket_form' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-200 hover:bg-gray-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Formulir Pesan Offline</span>
            </button>
            <button type="button" wire:click="$set('userViewMode', 'history')" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-1.5 cursor-pointer {{ $userViewMode === 'history' ? 'bg-purple-900 text-white shadow-md shadow-purple-900/20' : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-200 hover:bg-gray-100' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>Riwayat Sesi Saya</span>
            </button>
        </div>

        @if($userViewMode === 'live')
            {{-- KONDISI 2: FORM INISIASI PANGGILAN LIVE --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Form Inisiasi Panggilan --}}
                <div class="lg:col-span-2 card p-6 md:p-8 bg-white dark:bg-slate-800 border border-purple-100 dark:border-slate-700 shadow-xl rounded-3xl">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 flex items-center justify-center shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-extrabold text-base md:text-lg text-gray-900 dark:text-white">Mulai Panggilan Bantuan Langsung</h3>
                            <p class="text-xs text-gray-500 dark:text-slate-400">Pilih topik masalah yang Anda hadapi dan hubungkan ke Customer Support.</p>
                        </div>
                    </div>

                    <form wire:submit.prevent="startCall" class="space-y-5">
                        {{-- Topik Masalah --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-200 mb-2">
                                Topik Kendala / Masalah <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="selectedTopic" class="w-full p-3 rounded-xl border border-purple-200 dark:border-slate-700 text-xs font-medium dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500">
                                @foreach($topicOptions as $topic)
                                    <option value="{{ $topic }}">{{ $topic }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Pesan Awal (Opsional) --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-700 dark:text-slate-200 mb-2">
                                Uraian Singkat Kendala (Opsional)
                            </label>
                            <textarea wire:model="initialMessage" rows="3" placeholder="Contoh: Dokumen ijazah gagal terunggah pada tab berkas..." class="w-full p-3 rounded-xl border border-purple-200 dark:border-slate-700 text-xs dark:bg-slate-900 dark:text-white focus:ring-2 focus:ring-purple-500"></textarea>
                            <p class="text-[11px] text-gray-400 mt-1">Uraian ini akan otomatis menjadi pesan pembuka saat admin menjawab panggilan Anda.</p>
                        </div>

                        {{-- Tombol Panggil Admin --}}
                        <div class="pt-2">
                            <button type="submit" wire:loading.attr="disabled" class="w-full py-4 rounded-2xl font-extrabold text-sm text-white bg-gradient-to-r from-purple-700 via-purple-600 to-indigo-600 hover:from-purple-600 hover:to-indigo-500 shadow-xl shadow-purple-600/30 transition transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50">
                                <span wire:loading.remove wire:target="startCall" class="flex items-center gap-2">
                                    <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <span>Panggil Admin Sekarang (Live Call)</span>
                                </span>
                                <span wire:loading wire:target="startCall" class="flex items-center gap-2">
                                    <svg class="animate-spin w-5 h-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span>Menghubungkan ke Layanan Live Support...</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Panduan Layanan & Jam Kerja --}}
                <div class="card p-6 bg-gradient-to-br from-purple-50 to-indigo-50/50 dark:from-slate-800 dark:to-slate-800/60 border border-purple-100 dark:border-slate-700 rounded-3xl space-y-4">
                    <h4 class="font-extrabold text-sm text-purple-950 dark:text-purple-200 flex items-center gap-2">
                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Ketentuan Live Support
                    </h4>
                    <ul class="text-xs text-gray-600 dark:text-slate-300 space-y-2.5 leading-relaxed">
                        <li class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-600 mt-1.5 flex-shrink-0"></span>
                            <span>Panggilan dilayani langsung oleh staf dan Customer Support Panitia PPG Lapor Diri.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-600 mt-1.5 flex-shrink-0"></span>
                            <span>Siapkan data nomor pendaftaran, NIK, dan tangkapan layar berkas jika terdapat error.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-purple-600 mt-1.5 flex-shrink-0"></span>
                            <span>Anda dapat mengunggah file lampiran gambar atau PDF secara langsung di ruang obrolan.</span>
                        </li>
                    </ul>

                    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-purple-100 dark:border-slate-800">
                        <div class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Status Layanan Saat Ini</div>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="w-2.5 h-2.5 rounded-full {{ $statusInfo['is_open'] ? 'bg-emerald-500 animate-ping' : 'bg-rose-500' }}"></span>
                            <span class="font-extrabold text-xs {{ $statusInfo['is_open'] ? 'text-emerald-700 dark:text-emerald-400' : 'text-rose-700 dark:text-rose-400' }}">
                                {{ $statusInfo['label'] }}
                            </span>
                        </div>
                    </div>
                </div>

            </div>

        @elseif($userViewMode === 'ticket_form')
            {{-- FORMULIR TIKET BANTUAN ASINKRON --}}
            <div class="card p-6 md:p-8 bg-white dark:bg-slate-800 border border-purple-100 dark:border-slate-700 shadow-xl rounded-3xl max-w-2xl mx-auto">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-11 h-11 rounded-2xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-extrabold text-base text-gray-900 dark:text-white">Kirim Pesan Bantuan Offline</h3>
                        <p class="text-xs text-gray-500 dark:text-slate-400">Admin akan membaca dan membalas pengaduan Anda pada jam kerja.</p>
                    </div>
                </div>

                <form wire:submit.prevent="submitOfflineTicket" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-200 mb-1.5">Judul Pengaduan <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="ticketSubject" placeholder="Contoh: Kesalahan input nama pada ijazah" class="w-full p-3 rounded-xl border border-purple-200 dark:border-slate-700 text-xs">
                        @error('ticketSubject') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-200 mb-1.5">Kategori Masalah <span class="text-red-500">*</span></label>
                        <select wire:model="ticketCategory" class="w-full p-3 rounded-xl border border-purple-200 dark:border-slate-700 text-xs">
                            <option value="Kendala Formulir">Kendala Formulir & Biodata</option>
                            <option value="Unggah Berkas">Unggah Berkas & Dokumen</option>
                            <option value="Verifikasi">Verifikasi & Status Kelulusan</option>
                            <option value="Akun & Password">Akun & Password</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 dark:text-slate-200 mb-1.5">Isi Pesan Bantuan <span class="text-red-500">*</span></label>
                        <textarea wire:model="ticketMessage" rows="4" placeholder="Jelaskan secara lengkap kendala yang Anda alami..." class="w-full p-3 rounded-xl border border-purple-200 dark:border-slate-700 text-xs"></textarea>
                        @error('ticketMessage') <span class="text-red-500 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="pt-2 flex items-center justify-end gap-2">
                        <button type="button" wire:click="$set('userViewMode', 'live')" class="px-4 py-2.5 rounded-xl text-xs font-semibold text-gray-600 hover:bg-gray-100">Batal</button>
                        <button type="submit" class="btn-primary px-6 py-2.5 text-xs font-bold">Kirim Pesan Bantuan</button>
                    </div>
                </form>
            </div>

        @elseif($userViewMode === 'history')
            {{-- RIWAYAT SESI BANTUAN SAYA --}}
            <div class="space-y-6">
                <div class="card overflow-hidden">
                    <div class="p-4 border-b border-gray-100 dark:border-slate-800 font-bold text-sm text-gray-900 dark:text-white">
                        Riwayat Panggilan Bantuan Live Support
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-slate-800 text-xs">
                        @forelse($mySessions as $ms)
                            <div class="p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-gray-50 dark:hover:bg-slate-800/40 transition">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono font-bold text-purple-700 dark:text-purple-300">{{ $ms->session_code }}</span>
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $ms->status === 'resolved' ? 'bg-emerald-100 text-emerald-800' : ($ms->status === 'active' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-700') }}">
                                            {{ $ms->status }}
                                        </span>
                                    </div>
                                    <p class="font-semibold text-gray-800 dark:text-slate-200 mt-1">{{ $ms->topic }}</p>
                                    <p class="text-[11px] text-gray-400 mt-0.5">
                                        Ditangani: {{ $ms->admin->name ?? '-' }} • {{ $ms->created_at->translatedFormat('d F Y, H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <button type="button" wire:click="$set('currentSessionId', {{ $ms->id }})" class="px-3 py-1.5 rounded-xl bg-purple-50 dark:bg-slate-700 text-purple-700 dark:text-purple-300 font-bold text-xs hover:bg-purple-100 transition cursor-pointer">
                                        Lihat Percakapan
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-400 text-xs">
                                Anda belum memiliki riwayat panggilan bantuan live.
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Tiket Offline Saya --}}
                <div class="card overflow-hidden">
                    <div class="p-4 border-b border-gray-100 dark:border-slate-800 font-bold text-sm text-gray-900 dark:text-white">
                        Daftar Pesan Bantuan Offline (Tiket)
                    </div>
                    <div class="divide-y divide-gray-100 dark:divide-slate-800 text-xs">
                        @forelse($myTickets as $mt)
                            <div class="p-4 hover:bg-gray-50 dark:hover:bg-slate-800/40 transition">
                                <div class="flex items-center justify-between gap-2 mb-1">
                                    <h4 class="font-bold text-gray-900 dark:text-white">{{ $mt->subject }}</h4>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase {{ $mt->status === 'open' ? 'bg-amber-100 text-amber-800' : ($mt->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600') }}">
                                        {{ $mt->status }}
                                    </span>
                                </div>
                                <p class="text-gray-600 dark:text-slate-300 line-clamp-2">{{ $mt->pesan }}</p>
                                <span class="text-[10px] text-gray-400 mt-1 block">{{ $mt->created_at->diffForHumans() }}</span>
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-400 text-xs">
                                Belum ada tiket pengaduan offline.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif

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
                            <h3 class="font-extrabold text-base text-gray-900 dark:text-white">Jadwal Kerja Help Center</h3>
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
                    Tutup Informasi Jadwal
                </button>
            </div>
        </div>
    @endif

</div>
