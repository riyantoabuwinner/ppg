<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Period extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'tahun_akademik',
        'semester',
        'tanggal_mulai',
        'tanggal_selesai',
        'is_active',
        'deskripsi',
    ];

    protected $casts = [
        'tanggal_mulai'   => 'datetime',
        'tanggal_selesai' => 'datetime',
        'is_active'       => 'boolean',
    ];

    /**
     * Relasi ke profil mahasiswa yang terdaftar pada periode ini
     */
    public function studentProfiles()
    {
        return $this->hasMany(StudentProfile::class, 'periode_id');
    }

    /**
     * Memeriksa apakah periode sedang dibuka saat ini
     */
    public function isOpen(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = Carbon::now();
        return $now->between($this->tanggal_mulai, $this->tanggal_selesai);
    }

    /**
     * Status terhitung: open, upcoming, closed, inactive
     */
    public function getComputedStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'inactive';
        }

        $now = Carbon::now();
        if ($now->lt($this->tanggal_mulai)) {
            return 'upcoming';
        }

        if ($now->gt($this->tanggal_selesai)) {
            return 'closed';
        }

        return 'open';
    }

    /**
     * Label teks status
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->computed_status) {
            'open'     => 'Dibuka',
            'upcoming' => 'Akan Datang',
            'closed'   => 'Ditutup',
            default    => 'Nonaktif',
        };
    }

    /**
     * CSS class badge status
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->computed_status) {
            'open'     => 'bg-emerald-100 text-emerald-800 border border-emerald-300',
            'upcoming' => 'bg-amber-100 text-amber-800 border border-amber-300',
            'closed'   => 'bg-rose-100 text-rose-800 border border-rose-300',
            default    => 'bg-gray-100 text-gray-700 border border-gray-300',
        };
    }

    /**
     * Titik dot warna badge
     */
    public function getStatusDotClassAttribute(): string
    {
        return match ($this->computed_status) {
            'open'     => 'bg-emerald-500',
            'upcoming' => 'bg-amber-500',
            'closed'   => 'bg-rose-500',
            default    => 'bg-gray-400',
        };
    }

    /**
     * Mengambil periode aktif yang sedang dibuka saat ini
     */
    public static function currentOpen(): ?self
    {
        $now = Carbon::now();
        return self::where('is_active', true)
            ->where('tanggal_mulai', '<=', $now)
            ->where('tanggal_selesai', '>=', $now)
            ->orderBy('tanggal_selesai', 'asc')
            ->first();
    }

    /**
     * Cek apakah ada minimal 1 periode yang sedang buka
     */
    public static function isAnyOpen(): bool
    {
        $now = Carbon::now();
        return self::where('is_active', true)
            ->where('tanggal_mulai', '<=', $now)
            ->where('tanggal_selesai', '>=', $now)
            ->exists();
    }

    /**
     * Mengambil periode mendatang terdekat
     */
    public static function nextUpcoming(): ?self
    {
        $now = Carbon::now();
        return self::where('is_active', true)
            ->where('tanggal_mulai', '>', $now)
            ->orderBy('tanggal_mulai', 'asc')
            ->first();
    }

    /**
     * Mengambil periode yang baru saja berakhir
     */
    public static function latestClosed(): ?self
    {
        $now = Carbon::now();
        return self::where('is_active', true)
            ->where('tanggal_selesai', '<', $now)
            ->orderBy('tanggal_selesai', 'desc')
            ->first();
    }
}
