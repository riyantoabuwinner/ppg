<?php

namespace App\Livewire\Admin;

use App\Models\HelpMessage;
use App\Models\HelpSession;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Services\HelpCenterService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Helpdesk extends Component
{
    use WithFileUploads, WithPagination;

    // Navigasi & Tab
    public string $activeTab = 'calling'; // 'calling', 'active', 'resolved', 'tickets'
    public bool $showScheduleModal = false;

    // Sesi Bantuan Terpilih (Live Call Desk)
    public ?int $selectedSessionId = null;
    public string $chatMessage = '';
    public $chatAttachment = null;
    public string $searchKeyword = '';

    // Legacy Ticket State
    public $selectedTicket = null;
    public string $replyPesan = '';
    public string $ticketStatusFilter = '';

    // Query String Binding
    protected $queryString = [
        'activeTab'         => ['except' => 'calling'],
        'selectedSessionId' => ['except' => null, 'as' => 'session_id'],
    ];

    public function mount(HelpCenterService $service): void
    {
        $autoAnswer = request()->query('auto_answer');
        $querySessionId = request()->query('session_id');

        if ($querySessionId) {
            $session = HelpSession::find($querySessionId);
            if ($session) {
                if ($autoAnswer && $session->status === 'calling') {
                    $this->acceptCall($session->id);
                } else {
                    $this->selectSession($session->id);
                    if ($session->status === 'active') {
                        $this->activeTab = 'active';
                    } elseif (in_array($session->status, ['resolved', 'closed'])) {
                        $this->activeTab = 'resolved';
                    }
                }
            }
        } else {
            // Default: jika ada calling, buka tab calling, jika tidak dan ada active, buka tab active
            if (HelpSession::calling()->exists()) {
                $this->activeTab = 'calling';
            } elseif (HelpSession::active()->exists()) {
                $this->activeTab = 'active';
                $firstActive = HelpSession::active()->latest()->first();
                if ($firstActive) {
                    $this->selectSession($firstActive->id);
                }
            }
        }
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->resetPage();

        if ($tab !== 'tickets') {
            $this->selectedTicket = null;
        }

        // Auto select first item if switching to active tab and none selected
        if ($tab === 'active' && !$this->selectedSessionId) {
            $first = HelpSession::active()->latest()->first();
            if ($first) {
                $this->selectSession($first->id);
            }
        }
    }

    public function selectSession(int $sessionId): void
    {
        $this->selectedSessionId = $sessionId;
        $this->markMessagesAsRead();
    }

    public function acceptCall(int $sessionId): void
    {
        $session = HelpSession::find($sessionId);
        if (!$session) return;

        $session->update([
            'status'     => 'active',
            'admin_id'   => Auth::id(),
            'started_at' => $session->started_at ?? now(),
        ]);

        // Kirim salam pembuka otomatis dari sistem/admin jika belum ada pesan
        if ($session->messages()->count() === 0) {
            HelpMessage::create([
                'help_session_id' => $session->id,
                'sender_id'       => Auth::id(),
                'message'         => 'Halo ' . ($session->user->name ?? 'Kak') . '! Saya ' . Auth::user()->name . ' dari Tim Helpdesk PPG. Ada yang bisa kami bantu terkait kendala "' . $session->topic . '"?',
                'is_admin'        => true,
                'is_read'         => false,
            ]);
            $session->update(['last_message_at' => now()]);
        }

        $this->selectedSessionId = $session->id;
        $this->activeTab = 'active';
        $this->markMessagesAsRead();

        session()->flash('success', "Panggilan dari {$session->user->name} berhasil diterima.");
    }

    public function sendChatMessage(): void
    {
        if (!$this->selectedSessionId) return;

        $this->validate([
            'chatMessage'    => 'required_without:chatAttachment|nullable|string|max:3000',
            'chatAttachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,webp,pdf,doc,docx,zip',
        ], [
            'chatMessage.required_without' => 'Pesan tidak boleh kosong jika tanpa lampiran.',
            'chatAttachment.max'           => 'Ukuran file lampiran maksimal 10MB.',
            'chatAttachment.mimes'         => 'Format lampiran harus berupa gambar, PDF, dokumen Word, atau ZIP.',
        ]);

        $attachmentPath = null;
        $attachmentName = null;

        if ($this->chatAttachment) {
            $attachmentName = $this->chatAttachment->getClientOriginalName();
            $attachmentPath = $this->chatAttachment->store('help-attachments/' . date('Y-m'), 'public');
        }

        $session = HelpSession::find($this->selectedSessionId);
        if (!$session) return;

        HelpMessage::create([
            'help_session_id' => $session->id,
            'sender_id'       => Auth::id(),
            'message'         => $this->chatMessage ?: '(Mengirim lampiran berkas)',
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'is_admin'        => true,
            'is_read'         => false,
        ]);

        $session->update([
            'last_message_at' => now(),
            'admin_id'        => $session->admin_id ?? Auth::id(),
        ]);

        $this->reset(['chatMessage', 'chatAttachment']);
        $this->markMessagesAsRead();
    }

    public function applyQuickReply(string $key, HelpCenterService $service): void
    {
        $replies = $service->getQuickReplies();
        if (isset($replies[$key])) {
            $this->chatMessage = $replies[$key];
        }
    }

    public function resolveSession(?int $sessionId = null): void
    {
        $id = $sessionId ?? $this->selectedSessionId;
        if (!$id) return;

        $session = HelpSession::find($id);
        if ($session) {
            $session->update([
                'status'   => 'resolved',
                'ended_at' => now(),
            ]);

            // Kirim pesan penutup
            HelpMessage::create([
                'help_session_id' => $session->id,
                'sender_id'       => Auth::id(),
                'message'         => 'Sesi panggilan bantuan ini telah diselesaikan oleh Admin (' . Auth::user()->name . '). Terima kasih telah menghubungi Help Center.',
                'is_admin'        => true,
                'is_read'         => true,
            ]);

            session()->flash('success', "Sesi {$session->session_code} berhasil diselesaikan.");
        }
    }

    public function closeSession(?int $sessionId = null): void
    {
        $id = $sessionId ?? $this->selectedSessionId;
        if (!$id) return;

        $session = HelpSession::find($id);
        if ($session) {
            $session->update([
                'status'   => 'closed',
                'ended_at' => now(),
            ]);
            session()->flash('info', "Sesi {$session->session_code} telah ditutup.");
        }
    }

    public function markMessagesAsRead(): void
    {
        if (!$this->selectedSessionId) return;

        HelpMessage::where('help_session_id', $this->selectedSessionId)
            ->where('is_admin', false)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    // ===== LEGACY TICKETS METHODS =====
    public function openTicket($id): void
    {
        $this->selectedTicket = Ticket::with(['replies.user', 'user'])->find($id);
        if ($this->selectedTicket?->status === 'open') {
            $this->selectedTicket->update(['status' => 'in_progress']);
            $this->selectedTicket->refresh();
        }
    }

    public function sendTicketReply(): void
    {
        $this->validate(['replyPesan' => 'required|min:3']);

        if (!$this->selectedTicket) return;

        TicketReply::create([
            'ticket_id' => $this->selectedTicket->id,
            'user_id'   => Auth::id(),
            'pesan'     => $this->replyPesan,
        ]);

        $this->replyPesan = '';
        $this->selectedTicket = Ticket::with('replies.user')->find($this->selectedTicket->id);
    }

    public function closeTicket($id): void
    {
        Ticket::find($id)?->update(['status' => 'closed']);
        $this->selectedTicket = null;
        session()->flash('info', 'Tiket pengaduan telah ditutup.');
    }

    public function render(HelpCenterService $service)
    {
        $statusInfo = $service->getStatusInfo();
        $stats = $service->getStatistics();
        $scheduleDetails = $service->getScheduleDetails();
        $quickReplies = $service->getQuickReplies();

        // Query Panggilan Masuk (Calling)
        $callingSessions = HelpSession::with(['user.profile'])
            ->calling()
            ->latest()
            ->get();

        // Query Obrolan Aktif (Active)
        $activeSessions = HelpSession::with(['user', 'admin', 'latestMessage'])
            ->active()
            ->latest('last_message_at')
            ->get();

        // Query Riwayat (Resolved / Closed)
        $historyQuery = HelpSession::with(['user', 'admin'])
            ->whereIn('status', ['resolved', 'closed'])
            ->when($this->searchKeyword, function ($q) {
                $kw = '%' . $this->searchKeyword . '%';
                $q->where(function ($sub) use ($kw) {
                    $sub->where('session_code', 'like', $kw)
                        ->orWhere('topic', 'like', $kw)
                        ->orWhereHas('user', fn($u) => $u->where('name', 'like', $kw)->orWhere('username', 'like', $kw));
                });
            })
            ->latest('ended_at');

        $historySessions = $historyQuery->paginate(12, ['*'], 'history_page');

        // Sesi yang sedang dibuka di ruang chat
        $activeSessionRecord = null;
        if ($this->selectedSessionId) {
            $activeSessionRecord = HelpSession::with(['user.profile', 'admin', 'messages.sender'])
                ->find($this->selectedSessionId);

            if ($activeSessionRecord) {
                $this->markMessagesAsRead();
            }
        }

        // Tiket pengaduan lama (asinkron)
        $ticketQuery = Ticket::with('user')->latest();
        if ($this->ticketStatusFilter) {
            $ticketQuery->where('status', $this->ticketStatusFilter);
        }
        $tickets = $ticketQuery->paginate(15, ['*'], 'tickets_page');

        return view('livewire.admin.helpdesk', compact(
            'statusInfo',
            'stats',
            'scheduleDetails',
            'quickReplies',
            'callingSessions',
            'activeSessions',
            'historySessions',
            'activeSessionRecord',
            'tickets'
        ));
    }
}
