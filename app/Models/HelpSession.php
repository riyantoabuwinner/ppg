<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class HelpSession extends Model
{
    use HasFactory;

    protected $table = 'help_sessions';

    protected $fillable = [
        'session_code',
        'user_id',
        'admin_id',
        'status',
        'topic',
        'started_at',
        'ended_at',
        'last_message_at',
    ];

    protected $casts = [
        'started_at'      => 'datetime',
        'ended_at'        => 'datetime',
        'last_message_at' => 'datetime',
    ];

    /**
     * Generate unique session code: HC-YYYYMMDD-XXXX
     */
    public static function generateCode(): string
    {
        $date = now()->format('Ymd');
        do {
            $random = strtoupper(Str::random(4));
            $code = "HC-{$date}-{$random}";
        } while (static::where('session_code', $code)->exists());

        return $code;
    }

    /**
     * Scope for sessions with status 'calling'
     */
    public function scopeCalling($query)
    {
        return $query->where('status', 'calling');
    }

    /**
     * Scope for sessions with status 'active'
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for sessions with status 'calling' or 'active'
     */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', ['calling', 'active']);
    }

    /**
     * Caller User relationship
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Handling Admin relationship
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Chat Messages relationship
     */
    public function messages(): HasMany
    {
        return $this->hasMany(HelpMessage::class, 'help_session_id')->oldest();
    }

    /**
     * Latest Message relationship
     */
    public function latestMessage(): HasOne
    {
        return $this->hasOne(HelpMessage::class, 'help_session_id')->latestOfMany();
    }

    /**
     * Unread messages count for a specific user role
     */
    public function unreadCountForAdmin(): int
    {
        return $this->messages()->where('is_admin', false)->where('is_read', false)->count();
    }

    public function unreadCountForUser(): int
    {
        return $this->messages()->where('is_admin', true)->where('is_read', false)->count();
    }

    /**
     * Helpers for status check
     */
    public function isCalling(): bool
    {
        return $this->status === 'calling';
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isResolved(): bool
    {
        return $this->status === 'resolved';
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }

    /**
     * Elapsed wait time in seconds (from created_at to started_at or now)
     */
    public function getWaitSeconds(): int
    {
        $end = $this->started_at ?? now();
        return max(0, $this->created_at ? $this->created_at->diffInSeconds($end) : 0);
    }

    /**
     * Formatted wait time string (e.g., "45 dtk", "2 mnt 15 dtk")
     */
    public function getFormattedWaitTimeAttribute(): string
    {
        $seconds = $this->getWaitSeconds();
        if ($seconds < 60) {
            return "{$seconds} detik";
        }
        $minutes = floor($seconds / 60);
        $remSeconds = $seconds % 60;
        return "{$minutes}m {$remSeconds}s";
    }
}
