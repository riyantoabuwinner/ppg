<?php

namespace App\Notifications;

use App\Models\HelpSession;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class IncomingHelpCallNotification extends Notification
{
    use Queueable;

    public HelpSession $session;

    /**
     * Create a new notification instance.
     */
    public function __construct(HelpSession $session)
    {
        $this->session = $session;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $caller = $this->session->user;

        return [
            'type'            => 'incoming_help_call',
            'session_id'      => $this->session->id,
            'session_code'    => $this->session->session_code,
            'caller_id'       => $caller->id ?? null,
            'caller_name'     => $caller->name ?? 'Pengguna',
            'caller_username' => $caller->username ?? '',
            'topic'           => $this->session->topic,
            'title'           => 'Panggilan Bantuan Baru',
            'message'         => ($caller->name ?? 'Pengguna') . ' meminta live support: "' . $this->session->topic . '"',
            'action_label'    => 'Jawab Panggilan',
            'action_url'      => route('admin.helpdesk', [
                'session_id'  => $this->session->id,
                'auto_answer' => 1,
            ]),
            'created_at'      => now()->toISOString(),
        ];
    }
}
