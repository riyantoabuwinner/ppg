@php
    $helpCenterService = app(\App\Services\HelpCenterService::class);
    $status = $helpCenterService->getStatusInfo();
    $schedules = $helpCenterService->getScheduleDetails();
    $isLoggedIn = auth()->check();
    $isOpen = $status['is_open'];
@endphp

{{-- Floating Help Center & Live Support Widget (Matching Screenshot UI 1:1) --}}
<div id="help-center-widget" class="fixed bottom-[96px] right-6 z-[9990] flex items-center justify-center">
    
    {{-- Circular Floating Button (Theme: Ungu & Emas PPG) --}}
    <button type="button" 
            onclick="handleHelpCenterClick()"
            aria-label="Pusat Bantuan & Live Support"
            title="{{ $isOpen ? 'Customer Support Online — Hubungi Live Support' : 'Help Center Offline' }}"
            class="w-[60px] h-[60px] rounded-full bg-gradient-to-br from-[#4C1D95] via-[#3B0764] to-[#2E0854] border-[2.5px] border-[#F5C518] text-[#FDE68A] shadow-[0_0_0_6px_rgba(245,197,24,0.28),0_12px_28px_rgba(59,7,100,0.45)] hover:shadow-[0_0_0_8px_rgba(245,197,24,0.42),0_16px_34px_rgba(59,7,100,0.55)] flex items-center justify-center transition-all duration-300 transform hover:scale-105 active:scale-95 cursor-pointer relative group focus:outline-none focus:ring-4 focus:ring-[#F5C518]/50">
        
        <!-- Headset Icon with Mic -->
        <svg class="w-8 h-8 text-[#FDE68A] group-hover:scale-110 transition-transform" viewBox="0 0 32 32" fill="none">
            <!-- Headband Arc -->
            <path d="M7 16C7 11.0294 11.0294 7 16 7C20.9706 7 25 11.0294 25 16" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
            <!-- Left Earcup Capsule -->
            <rect x="5" y="14.5" width="4.2" height="7.5" rx="2.1" fill="currentColor"/>
            <!-- Right Earcup Capsule -->
            <rect x="22.8" y="14.5" width="4.2" height="7.5" rx="2.1" fill="currentColor"/>
            <!-- Mic Boom curved down from right earcup to mouth -->
            <path d="M24.5 20.5V22C24.5 23.933 22.933 25.5 21 25.5H18" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
            <!-- Mic Capsule at mouth -->
            <rect x="14.5" y="24" width="4.5" height="3" rx="1.5" fill="currentColor"/>
        </svg>

        <!-- Status Dot Badge (Hijau Mint dengan Rim Ungu Gelap) -->
        <span class="absolute -top-0.5 -right-0.5 flex h-[19px] w-[19px] pointer-events-none">
            @if($isOpen)
                <span class="relative inline-flex rounded-full h-[19px] w-[19px] bg-[#34D399] border-[2.5px] border-[#3B0764] shadow-sm"></span>
            @else
                <span class="relative inline-flex rounded-full h-[19px] w-[19px] bg-slate-400 border-[2.5px] border-[#3B0764] shadow-sm"></span>
            @endif
        </span>
    </button>

    {{-- =========================================================
         MODAL 1: LOGIN REQUIRED (JIKA BELUM LOGIN)
         ========================================================= --}}
    <div id="modal-help-login-required" class="hidden fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fadeIn" role="dialog" aria-modal="true">
        <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-md w-full p-6 sm:p-7 shadow-2xl border border-purple-200 dark:border-slate-800 text-center relative overflow-hidden">
            <div class="absolute -top-12 -right-12 w-32 h-32 bg-purple-500/10 rounded-full blur-2xl"></div>

            {{-- Close Button --}}
            <button type="button" onclick="closeHelpModals()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-slate-200 p-1.5 rounded-xl cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="w-16 h-16 mx-auto rounded-3xl bg-purple-100 dark:bg-purple-900/50 text-purple-700 dark:text-purple-300 flex items-center justify-center mb-4 shadow-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>

            <h3 class="text-lg font-black text-gray-900 dark:text-white">
                Access Help Center & Live Support
            </h3>
            <p class="text-xs text-purple-600 dark:text-purple-400 font-bold uppercase tracking-wider mt-0.5">
                Login Required
            </p>

            <p class="text-xs text-gray-600 dark:text-slate-300 mt-3 leading-relaxed">
                Untuk memulai panggilan live desk dan konsultasi kendala lapor diri PPG dengan Customer Support, Anda harus masuk ke akun Anda terlebih dahulu.
            </p>

            <div class="mt-6 flex flex-col gap-2.5">
                <a href="{{ route('login') }}" class="w-full py-3 rounded-2xl font-bold text-xs text-white bg-gradient-to-r from-purple-700 to-indigo-700 hover:from-purple-600 hover:to-indigo-600 shadow-lg shadow-purple-600/30 transition text-center">
                    Masuk / Login Sekarang
                </a>
                <button type="button" onclick="closeHelpModals()" class="w-full py-2.5 rounded-2xl font-semibold text-xs text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800 transition cursor-pointer">
                    Nanti Saja
                </button>
            </div>
        </div>
    </div>

    {{-- =========================================================
         MODAL 2: HELP CENTER OFFLINE (JIKA DI LUAR JAM KERJA)
         ========================================================= --}}
    <div id="modal-help-offline" class="hidden fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fadeIn" role="dialog" aria-modal="true">
        <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full p-6 sm:p-7 shadow-2xl border border-purple-200 dark:border-slate-800 relative overflow-hidden">
            
            {{-- Close Button --}}
            <button type="button" onclick="closeHelpModals()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-slate-200 p-1.5 rounded-xl cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="flex items-center gap-3.5 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-black text-base text-gray-900 dark:text-white">Help Center (Offline)</h3>
                    <p class="text-xs text-purple-600 dark:text-purple-400 font-bold">Waktu Sekarang: {{ $status['current_time'] }} (WIB)</p>
                </div>
            </div>

            <p class="text-xs text-gray-600 dark:text-slate-300 leading-relaxed mb-4">
                Mohon maaf, layanan Panggilan Live Support saat ini sedang berada di luar jam operasional. Silakan hubungi kami kembali sesuai jadwal berikut:
            </p>

            <div class="space-y-2.5 text-xs mb-6">
                @foreach($schedules as $sch)
                    <div class="p-3 rounded-2xl border {{ $sch['is_active'] ? 'bg-purple-50 dark:bg-purple-950/40 border-purple-200 dark:border-purple-800' : 'bg-gray-50 dark:bg-slate-800 border-gray-100 dark:border-slate-800' }}">
                        <div class="flex items-center justify-between font-bold text-gray-800 dark:text-white">
                            <span>{{ $sch['day'] }}</span>
                            @if($sch['is_active'])
                                <span class="px-2 py-0.5 rounded-full text-[10px] bg-purple-600 text-white font-extrabold">Hari Ini</span>
                            @endif
                        </div>
                        <div class="text-purple-700 dark:text-purple-300 font-semibold mt-0.5">{{ $sch['hours'] }}</div>
                        <div class="text-gray-400 text-[11px]">Istirahat: {{ $sch['break'] }}</div>
                    </div>
                @endforeach
            </div>

            <div class="flex flex-col sm:flex-row gap-2">
                <a href="{{ route('help-center') }}" class="flex-1 py-2.5 rounded-xl font-bold text-xs text-white bg-purple-700 hover:bg-purple-800 transition text-center shadow-md shadow-purple-700/20">
                    Kirim Pesan Bantuan Offline
                </a>
                <button type="button" onclick="closeHelpModals()" class="px-4 py-2.5 rounded-xl font-semibold text-xs text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800 transition cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>

<script>
/**
 * Validasi Interaktif Floating Help Center
 */
function handleHelpCenterClick() {
    const isLoggedIn = @json($isLoggedIn);
    const isOpen = @json($isOpen);

    if (!isLoggedIn) {
        // 1. Belum Login: Tampilkan modal login required
        openHelpModal('modal-help-login-required');
    } else if (!isOpen) {
        // 2. Sudah Login tapi Offline: Tampilkan modal jadwal offline
        openHelpModal('modal-help-offline');
    } else {
        // 3. Sudah Login & Operasional Buka: Redirect langsung ke Help Center dengan ?start_call=1
        window.location.href = "{{ route('help-center', ['start_call' => 1]) }}";
    }
}

function openHelpModal(modalId) {
    closeHelpModals();
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
    }
}

function closeHelpModals() {
    ['modal-help-login-required', 'modal-help-offline'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.classList.add('hidden');
    });
}

// Tutup modal jika tombol Escape ditekan
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeHelpModals();
    }
});
</script>
