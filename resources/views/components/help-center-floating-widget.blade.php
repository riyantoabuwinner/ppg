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
        <div class="bg-white dark:bg-slate-900 rounded-[20px] max-w-[420px] w-full shadow-2xl relative overflow-hidden flex flex-col border border-purple-100 dark:border-slate-800">
            {{-- Header Bar --}}
            <div class="bg-gradient-to-r from-purple-800 to-indigo-900 px-5 py-3.5 flex items-center justify-between">
                <div class="flex items-center space-x-2 text-white font-bold text-sm">
                    <i class="fa-solid fa-headset text-amber-400"></i>
                    <span>Help Center PPG (Offline)</span>
                </div>
                <button type="button" onclick="closeHelpModals()" class="text-white/80 hover:text-white transition cursor-pointer">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            {{-- Content --}}
            <div class="p-6 flex flex-col items-center">
                {{-- Moon Icon --}}
                <div class="w-20 h-20 bg-purple-100 dark:bg-purple-900/40 rounded-full flex items-center justify-center mb-5 shadow-inner">
                    <i class="fa-solid fa-moon text-purple-600 dark:text-purple-400 text-3xl"></i>
                </div>
                
                <h3 class="text-xl font-black text-gray-900 dark:text-white text-center mb-3">Di Luar Jam Kerja Operasional</h3>
                <p class="text-sm text-gray-600 dark:text-slate-300 font-medium text-center mb-6 leading-relaxed">
                    Layanan panggilan langsung (<span class="italic">Live Support</span>) {{ \App\Models\AppSetting::get('app_subtitle', 'PPG UIN Siber Syekh Nurjati') }} saat ini sedang offline.
                </p>

                {{-- Schedule Box --}}
                <div class="w-full bg-purple-50/50 dark:bg-slate-800 border border-purple-100 dark:border-slate-700 rounded-xl p-5 mb-6 text-sm">
                    <div class="font-bold text-purple-900 dark:text-purple-300 flex items-center space-x-2 mb-3 text-sm">
                        <i class="fa-solid fa-clock"></i>
                        <span>Jadwal Jam Kerja Operasional (WIB)</span>
                    </div>
                    <div class="space-y-1.5 text-gray-700 dark:text-slate-300 text-[13px] leading-loose">
                        <p><span class="font-bold text-gray-900 dark:text-white">Senin – Kamis:</span> 08.00–11.30 • 13.00–16.00 WIB</p>
                        <p><span class="font-bold text-gray-900 dark:text-white">Jum'at:</span> 08.00–11.00 • 13.30–16.30 WIB</p>
                        <p><span class="font-bold text-gray-900 dark:text-white">Sabtu & Minggu:</span> Tutup / Libur Operasional</p>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="w-full flex flex-row gap-3 justify-center mb-3">
                    <a href="{{ route('landing') }}" class="flex-1 bg-purple-700 hover:bg-purple-800 text-white font-bold py-2.5 rounded-xl flex items-center justify-center space-x-2 transition text-sm shadow-md shadow-purple-700/20">
                        <i class="fa-solid fa-book-open"></i>
                        <span>Buka FAQ & Info</span>
                    </a>
                    @php $waContact = \App\Models\AppSetting::get('contact_whatsapp'); @endphp
                    @if($waContact)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $waContact) }}" target="_blank" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl flex items-center justify-center space-x-2 transition text-sm shadow-md shadow-green-600/20">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                        <span>WhatsApp Admin</span>
                    </a>
                    @else
                    <a href="{{ route('help-center') }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-bold py-2.5 rounded-xl flex items-center justify-center space-x-2 transition text-sm shadow-md shadow-green-600/20">
                        <i class="fa-solid fa-envelope text-lg"></i>
                        <span>Tinggalkan Pesan</span>
                    </a>
                    @endif
                </div>
                <button type="button" onclick="closeHelpModals()" class="bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 border border-gray-200 dark:border-slate-700 text-gray-700 dark:text-slate-300 font-bold py-2 px-6 rounded-xl transition text-sm cursor-pointer">
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
