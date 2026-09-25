<?php

namespace App\Services;

use App\Models\HelpSession;
use App\Models\User;
use App\Notifications\IncomingHelpCallNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class HelpCenterService
{
    public const TIMEZONE = 'Asia/Jakarta';

    /**
     * Cek apakah waktu saat ini berada dalam jam operasional layanan (WIB).
     *
     * Ketentuan Jadwal:
     * - Senin – Kamis : 08:00 – 11:30 & 13:00 – 16:00 WIB
     * - Jum'at        : 08:00 – 11:00 & 13:30 – 16:30 WIB
     * - Sabtu & Minggu: Tutup (Offline)
     */
    public function isOperatingHours(?Carbon $now = null): bool
    {
        $now = $now ? $now->copy()->setTimezone(self::TIMEZONE) : Carbon::now(self::TIMEZONE);

        // Hari dalam seminggu: 1 = Senin, 2 = Selasa, ..., 5 = Jumat, 6 = Sabtu, 7 = Minggu
        $dayOfWeek = $now->dayOfWeekIso;

        // Sabtu (6) dan Minggu (7) tutup
        if ($dayOfWeek >= 6) {
            return false;
        }

        $currentTime = $now->format('H:i');

        // Senin – Kamis (1, 2, 3, 4)
        if ($dayOfWeek >= 1 && $dayOfWeek <= 4) {
            $shift1 = ($currentTime >= '08:00' && $currentTime <= '11:30');
            $shift2 = ($currentTime >= '13:00' && $currentTime <= '16:00');
            return $shift1 || $shift2;
        }

        // Jum'at (5)
        if ($dayOfWeek === 5) {
            $shift1 = ($currentTime >= '08:00' && $currentTime <= '11:00');
            $shift2 = ($currentTime >= '13:30' && $currentTime <= '16:30');
            return $shift1 || $shift2;
        }

        return false;
    }

    /**
     * Mengembalikan array detail jadwal kerja dalam teks rapi.
     */
    public function getScheduleDetails(): array
    {
        return [
            [
                'day'       => 'Senin – Kamis',
                'hours'     => '08:00 – 11:30 & 13:00 – 16:00 WIB',
                'break'     => '11:30 – 13:00 WIB (Istirahat & Sholat)',
                'is_active' => in_array(Carbon::now(self::TIMEZONE)->dayOfWeekIso, [1, 2, 3, 4]),
            ],
            [
                'day'       => "Jum'at",
                'hours'     => '08:00 – 11:00 & 13:30 – 16:30 WIB',
                'break'     => '11:00 – 13:30 WIB (Istirahat & Sholat Jum\'at)',
                'is_active' => Carbon::now(self::TIMEZONE)->dayOfWeekIso === 5,
            ],
            [
                'day'       => 'Sabtu, Minggu & Hari Libur',
                'hours'     => 'Tutup (Offline)',
                'break'     => '-',
                'is_active' => Carbon::now(self::TIMEZONE)->dayOfWeekIso >= 6,
            ],
        ];
    }

    /**
     * Mengembalikan status real-time layanan.
     */
    public function getStatusInfo(?Carbon $now = null): array
    {
        $now = $now ? $now->copy()->setTimezone(self::TIMEZONE) : Carbon::now(self::TIMEZONE);
        $isOpen = $this->isOperatingHours($now);

        return [
            'is_open'      => $isOpen,
            'current_time' => $now->format('H:i') . ' WIB',
            'current_date' => $now->translatedFormat('l, d F Y'),
            'label'        => $isOpen ? 'Online & Siap Melayani' : 'Offline / Di Luar Jam Kerja',
            'short_status' => $isOpen ? 'Online' : 'Offline',
            'color'        => $isOpen ? 'emerald' : 'rose',
            'badge_class'  => $isOpen
                ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800'
                : 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800',
            'description'  => $isOpen
                ? 'Admin Customer Support sedang aktif bertugas menerima panggilan konsultasi langsung.'
                : 'Layanan Live Support saat ini sedang offline. Silakan hubungi kembali pada jam operasional kerja.',
        ];
    }

    /**
     * Kirim notifikasi database dan sistem ke seluruh Admin ketika ada panggilan masuk.
     */
    public function notifyAdminsOfIncomingCall(HelpSession $session): void
    {
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();

        if ($admins->isNotEmpty()) {
            Notification::send($admins, new IncomingHelpCallNotification($session));
        }
    }

    /**
     * Daftar panggilan calling dalam rentang 30 menit terakhir.
     */
    public function getIncomingCallingSessions()
    {
        return HelpSession::with('user')
            ->calling()
            ->where('created_at', '>=', now()->subMinutes(30))
            ->latest()
            ->get();
    }

    /**
     * Statistik ringkas Help Center hari ini.
     */
    public function getStatistics(): array
    {
        $today = Carbon::today(self::TIMEZONE);

        return [
            'calling_count'  => HelpSession::calling()->count(),
            'active_count'   => HelpSession::active()->count(),
            'resolved_today' => HelpSession::where('status', 'resolved')
                ->where('ended_at', '>=', $today)
                ->count(),
            'total_sessions' => HelpSession::count(),
        ];
    }

    /**
     * Opsi topik masalah default
     */
    public function getTopicOptions(): array
    {
        return [
            'Kendala Formulir & Berkas Lapor Diri',
            'Verifikasi Dokumen & Ijazah',
            'Akun, Password & Akses Login',
            'Informasi Jadwal & Periode PPG',
            'Format SIAKAD & PDDIKTI',
            'Kendala Teknis Aplikasi & Sistem',
            'Pertanyaan Umum / Lainnya',
        ];
    }

    /**
     * Template quick replies untuk Customer Support
     */
    public function getQuickReplies(): array
    {
        return [
            'Sapa Pengguna' => 'Halo! Selamat datang di Help Center Lapor Diri PPG UIN Siber Syekh Nurjati. Ada yang bisa kami bantu hari ini?',
            'Mohon Tunggu'  => 'Baik, mohon tunggu sebentar ya, kami sedang memeriksa riwayat berkas dan data akun Anda di sistem.',
            'Minta Bukti'   => 'Bisa tolong unggah screenshot layar atau bukti kendala yang Anda alami agar kami dapat menganalisis lebih detail?',
            'Solusi Berkas' => 'Dokumen Anda telah kami tinjau. Silakan periksa kembali halaman Formulir Lapor Diri dan simpan pembaruan berkas.',
            'Solusi Akun'   => 'Data akun Anda telah disinkronkan kembali. Silakan lakukan refresh halaman atau coba login ulang.',
            'Penutup Ramah' => 'Sama-sama! Terima kasih telah menghubungi Help Center PPG. Jika ada pertanyaan lanjutan, jangan ragu untuk menghubungi kami kembali. Sukses selalu!',
        ];
    }
}
