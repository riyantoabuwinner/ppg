{{-- Floating Incoming Call Alert & Web Audio API Ringtone Synthesizer for Admin --}}
@auth
@if(Auth::user()->role === 'admin')
<div id="incoming-call-container" class="fixed top-5 right-5 z-[99999] pointer-events-none transition-all duration-300 transform -translate-y-12 opacity-0 hidden" aria-live="assertive">
    <div class="pointer-events-auto w-96 max-w-[calc(100vw-2rem)] bg-white/95 dark:bg-slate-900/95 backdrop-blur-xl rounded-2xl shadow-[0_20px_60px_-15px_rgba(220,38,38,0.45)] border-2 border-red-500/80 p-4 relative overflow-hidden transition-all duration-300">
        
        <!-- Radar Pulse Glow Background -->
        <div class="absolute -top-12 -right-12 w-36 h-36 bg-red-500/20 rounded-full animate-ping pointer-events-none"></div>
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-red-500 via-amber-400 to-red-600 animate-pulse"></div>

        <div class="flex items-start gap-3.5 relative z-10">
            <!-- Pulsing Phone Icon & Live Beacon -->
            <div class="relative flex-shrink-0 mt-0.5">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-red-600 to-rose-700 text-white flex items-center justify-center shadow-lg shadow-red-500/40 animate-bounce">
                    <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                </div>
                <span class="absolute -top-1 -right-1 flex h-4 w-4">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 border-2 border-white dark:border-slate-900"></span>
                </span>
            </div>

            <!-- Call Details -->
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-1 mb-1">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider bg-red-100 dark:bg-red-950/70 text-red-700 dark:text-red-300 border border-red-300 dark:border-red-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 mr-1.5 animate-ping"></span>
                        Panggilan Live Masuk
                    </span>
                    <span id="call-wait-timer" class="text-[11px] font-semibold text-gray-500 dark:text-slate-400">00:00</span>
                </div>

                <h4 id="call-caller-name" class="font-bold text-gray-900 dark:text-white text-sm truncate leading-snug">
                    Memuat Pemanggil...
                </h4>
                <p id="call-caller-role" class="text-xs text-purple-600 dark:text-purple-400 font-medium truncate">
                    Mahasiswa PPG
                </p>

                <div class="mt-2 p-2 rounded-xl bg-gray-50 dark:bg-slate-800/80 border border-gray-200/70 dark:border-slate-700/60">
                    <div class="text-[10px] uppercase font-bold text-gray-400 tracking-wide">Topik Kendala</div>
                    <div id="call-caller-topic" class="text-xs font-semibold text-gray-800 dark:text-slate-200 line-clamp-2">
                        -
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-3 flex items-center gap-2">
                    <a id="btn-accept-call" href="#" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3.5 py-2 rounded-xl font-bold text-xs text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 shadow-md shadow-emerald-600/30 transition transform hover:-translate-y-0.5 active:translate-y-0 text-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span>Terima Panggilan</span>
                    </a>
                    <button type="button" id="btn-dismiss-call" class="inline-flex items-center justify-center gap-1 px-3 py-2 rounded-xl font-semibold text-xs text-gray-600 dark:text-slate-300 bg-gray-100 dark:bg-slate-800 hover:bg-gray-200 dark:hover:bg-slate-700 transition">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/></svg>
                        <span id="btn-mute-label">Mute</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
/**
 * Dual-Tone Frequency Synthesizer & Polling Notifier (440Hz + 480Hz)
 * Arsitektur nada dering telepon standar menggunakan Web Audio API murni.
 */
(function() {
    let audioCtx = null;
    let isRinging = false;
    let ringInterval = null;
    let isMuted = false;
    let mutedSessionIds = new Set();
    let currentCallingSession = null;
    let pollTimer = null;

    // Web Audio Dual-Tone Generator (440Hz + 480Hz)
    function initAudioContext() {
        if (!audioCtx) {
            const AudioContextClass = window.AudioContext || window.webkitAudioContext;
            if (AudioContextClass) {
                audioCtx = new AudioContextClass();
            }
        }
        if (audioCtx && audioCtx.state === 'suspended') {
            audioCtx.resume();
        }
    }

    function playPhoneBurst(durationMs = 1800) {
        if (!audioCtx || isMuted) return;

        try {
            const now = audioCtx.currentTime;
            const osc1 = audioCtx.createOscillator();
            const osc2 = audioCtx.createOscillator();
            const gain = audioCtx.createGain();

            // Standar Frekuensi Telepon Dual-Tone (440Hz & 480Hz)
            osc1.type = 'sine';
            osc1.frequency.setValueAtTime(440, now);

            osc2.type = 'sine';
            osc2.frequency.setValueAtTime(480, now);

            // Volume Envelope (halus di awal dan akhir untuk mencegah clicking audio)
            gain.gain.setValueAtTime(0, now);
            gain.gain.linearRampToValueAtTime(0.12, now + 0.05);
            gain.gain.setValueAtTime(0.12, now + (durationMs / 1000) - 0.05);
            gain.gain.linearRampToValueAtTime(0, now + (durationMs / 1000));

            osc1.connect(gain);
            osc2.connect(gain);
            gain.connect(audioCtx.destination);

            osc1.start(now);
            osc2.start(now);
            osc1.stop(now + (durationMs / 1000));
            osc2.stop(now + (durationMs / 1000));
        } catch (e) {
            console.warn('Audio synthesis warning:', e);
        }
    }

    function startRingtone() {
        if (isRinging || isMuted) return;
        initAudioContext();
        isRinging = true;

        // Mainkan burst pertama
        playPhoneBurst(1800);

        // Pola cadence telepon: 1.8 detik berbunyi, 3.2 detik hening (interval 5000ms)
        ringInterval = setInterval(() => {
            if (isRinging && !isMuted) {
                playPhoneBurst(1800);
            }
        }, 5000);
    }

    function stopRingtone() {
        isRinging = false;
        if (ringInterval) {
            clearInterval(ringInterval);
            ringInterval = null;
        }
    }

    // Topbar Bell Animation Trigger
    function toggleTopbarBell(active) {
        const bells = document.querySelectorAll('.admin-notification-bell, [data-bell-icon]');
        bells.forEach(b => {
            if (active) {
                b.classList.add('animate-bounce', 'text-red-500');
            } else {
                b.classList.remove('animate-bounce', 'text-red-500');
            }
        });
    }

    // Tampilkan Pop-Up Alert
    function showAlert(session) {
        const container = document.getElementById('incoming-call-container');
        if (!container) return;

        document.getElementById('call-caller-name').textContent = session.caller_name || 'Pengguna';
        document.getElementById('call-caller-role').textContent = session.caller_role || 'Mahasiswa';
        document.getElementById('call-caller-topic').textContent = session.topic || 'Kendala Umum';
        document.getElementById('call-wait-timer').textContent = session.wait_time_text || 'Baru saja';

        const btnAccept = document.getElementById('btn-accept-call');
        btnAccept.href = session.answer_url;
        btnAccept.onclick = () => {
            stopRingtone();
        };

        container.classList.remove('hidden');
        requestAnimationFrame(() => {
            container.classList.remove('-translate-y-12', 'opacity-0');
            container.classList.add('translate-y-0', 'opacity-100');
        });

        toggleTopbarBell(true);

        if (!isMuted && !mutedSessionIds.has(session.id)) {
            startRingtone();
        }
    }

    // Sembunyikan Pop-Up Alert
    function hideAlert() {
        const container = document.getElementById('incoming-call-container');
        if (!container || container.classList.contains('hidden')) return;

        container.classList.remove('translate-y-0', 'opacity-100');
        container.classList.add('-translate-y-12', 'opacity-0');
        setTimeout(() => {
            container.classList.add('hidden');
        }, 300);

        toggleTopbarBell(false);
        stopRingtone();
    }

    // Tombol Mute / Dismiss
    document.addEventListener('DOMContentLoaded', () => {
        const btnDismiss = document.getElementById('btn-dismiss-call');
        if (btnDismiss) {
            btnDismiss.addEventListener('click', () => {
                if (currentCallingSession) {
                    mutedSessionIds.add(currentCallingSession.id);
                }
                stopRingtone();
                hideAlert();
            });
        }

        // Buka AudioContext pada interaksi pertama pengguna untuk menghindari kebijakan autoplay browser
        const unlockAudio = () => {
            initAudioContext();
            document.removeEventListener('click', unlockAudio);
            document.removeEventListener('keydown', unlockAudio);
        };
        document.addEventListener('click', unlockAudio);
        document.addEventListener('keydown', unlockAudio);
    });

    // Background Polling loop (setiap 3.5 detik)
    async function checkIncomingCalls() {
        try {
            const endpoint = '{{ route("admin.help-center.incoming-check") }}';
            const response = await fetch(endpoint, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) return;
            const data = await response.json();

            if (data.has_calling && data.sessions && data.sessions.length > 0) {
                const latest = data.sessions[0];
                currentCallingSession = latest;

                // Jika sesi belum dimute oleh admin pada halaman ini
                if (!mutedSessionIds.has(latest.id)) {
                    showAlert(latest);
                }
            } else {
                currentCallingSession = null;
                hideAlert();
            }
        } catch (e) {
            // Polling fail silent
        }
    }

    // Jalankan polling
    pollTimer = setInterval(checkIncomingCalls, 3500);
    // Cek awal setelah 1 detik
    setTimeout(checkIncomingCalls, 1000);
})();
</script>
@endif
@endauth
