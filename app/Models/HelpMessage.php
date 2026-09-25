<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class HelpMessage extends Model
{
    use HasFactory;

    protected $table = 'help_messages';

    protected $fillable = [
        'help_session_id',
        'sender_id',
        'message',
        'attachment_path',
        'attachment_name',
        'is_admin',
        'is_read',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'is_read'  => 'boolean',
    ];

    /**
     * Parent HelpSession relationship
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(HelpSession::class, 'help_session_id');
    }

    /**
     * Message Sender relationship
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Check if message has an attachment
     */
    public function hasAttachment(): bool
    {
        return !empty($this->attachment_path);
    }

    /**
     * Attachment URL accessor
     */
    public function getAttachmentUrlAttribute(): ?string
    {
        if (!$this->attachment_path) {
            return null;
        }
        return Storage::url($this->attachment_path);
    }

    /**
     * Check if attachment is an image
     */
    public function isImageAttachment(): bool
    {
        if (!$this->attachment_path) {
            return false;
        }
        $ext = strtolower(pathinfo($this->attachment_path, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif']);
    }
}
