<?php

namespace App\Livewire\Mahasiswa;

use App\Models\HelpMessage;
use App\Models\HelpSession;
use App\Models\Ticket;
use App\Models\TicketReply;
use App\Services\HelpCenterService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class Helpdesk extends Component
{
    use WithFileUploads;

    // Sesi Aktif Panggilan / Live Support
    public ?int $currentSessionId = null;
    public string $selectedTopic = 'Kendala Formulir & Berkas Lapor Diri';
    public string $initialMessage = '';
    public bool $isInitiatingCall = false;

    // Chat Message dalam Active Room
    public string $chatMessage = '';
    public $chatAttachment = null;

    // Tab atau Mode Tampilan: 'live', 'history', 'ticket_form'
    public string $userViewMode = 'live';

    // Modal Jadwal
    public bool $showScheduleModal = false;

    // Offline / Tiket Form State
    public string $ticketSubject = '';
    public string $ticketCategory = 'Kendala Formulir';
    public string $ticketMessage = '';
    public $selectedTicket = null;
    public string $ticketReplyText = '';

    protected $queryString = [
        'currentSessionId' => ['except' => null, 'as' => 'session_id'],
    ];

    public function mount(HelpCenterService $service): void
    {
        // Cek apakah user memiliki sesi calling atau active yang belum selesai
        $existingSession = HelpSession::where('user_id', Auth::id())
            ->open()
            ->latest()
            ->first();

        if ($existingSession) {
            $this->currentSessionId = $existingSession->id;
            $this->markMessagesAsRead();
        } elseif (request()->query('start_call') && $service->isOperatingHours()) {
            // Parameter otomatis mulai panggilan dari floating widget
            $this->startCall($service);
        }
    }

    /**
     * Memulai panggilan live baru ke Admin / Customer Support.
     */
    public function startCall(HelpCenterService $service): void
    {
        // Validasi jam kerja
        if (!$service->isOperatingHours()) {
            session()->flash('error', 'Layanan Live Call saat ini sedang offline. Silakan lihat jadwal operasional atau kirim pesan bantuan.');
            return;
        }

        // Cek apakah sudah ada sesi open
        $existing = HelpSession::where('user_id', Auth::id())->open()->first();
        if ($existing) {
            $this->currentSessionId = $existing->id;
            return;
        }

        $this->isInitiatingCall = true;

        $session = HelpSession::create([
            'session_code'    => HelpSession::generateCode(),
            'user_id'         => Auth::id(),
            'admin_id'        => null,
            'status'          => 'calling',
            'topic'           => $this->selectedTopic,
            'last_message_at' => now(),
        ]);

        // Jika ada pesan awal, buatkan sebagai help_message pertama
        if (!empty(trim($this->initialMessage))) {
            HelpMessage::create([
                'help_session_id' => $session->id,
                'sender_id'       => Auth::id(),
                'message'         => $this->initialMessage,
                'is_admin'        => false,
                'is_read'         => false,
            ]);
        }

        // Kirim notifikasi ke seluruh admin
        $service->notifyAdminsOfIncomingCall($session);

        $this->currentSessionId = $session->id;
        $this->initialMessage = '';
        $this->isInitiatingCall = false;

        session()->flash('success', 'Panggilan Anda sedang berdering di layar Customer Support. Mohon tunggu admin menjawab.');
    }

    /**
     * Batalkan panggilan saat masih berstatus 'calling'.
     */
    public function cancelCall(): void
    {
        if (!$this->currentSessionId) return;

        $session = HelpSession::where('id', $this->currentSessionId)
            ->where('user_id', Auth::id())
            ->first();

        if ($session && $session->status === 'calling') {
            $session->update([
                'status'   => 'closed',
                'ended_at' => now(),
            ]);
            $this->currentSessionId = null;
            session()->flash('info', 'Panggilan bantuan telah dibatalkan.');
        }
    }

    /**
     * Mengakhiri panggilan live aktif oleh pengguna.
     */
    public function endCall(): void
    {
        if (!$this->currentSessionId) return;

        $session = HelpSession::where('id', $this->currentSessionId)
            ->where('user_id', Auth::id())
            ->first();

        if ($session) {
            $session->update([
                'status'   => 'resolved',
                'ended_at' => now(),
            ]);

            HelpMessage::create([
                'help_session_id' => $session->id,
                'sender_id'       => Auth::id(),
                'message'         => 'Panggilan bantuan diakhiri oleh Pengguna (' . Auth::user()->name . ').',
                'is_admin'        => false,
                'is_read'         => true,
            ]);

            session()->flash('info', 'Sesi panggilan telah diakhiri. Terima kasih telah menggunakan Help Center.');
        }
    }

    /**
     * Kirim pesan teks dan/atau attachment berkas dalam chat room aktif.
     */
    public function sendChatMessage(): void
    {
        if (!$this->currentSessionId) return;

        $this->validate([
            'chatMessage'    => 'required_without:chatAttachment|nullable|string|max:3000',
            'chatAttachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,webp,pdf,doc,docx,zip',
        ], [
            'chatMessage.required_without' => 'Pesan tidak boleh kosong jika tidak menyertakan lampiran.',
            'chatAttachment.max'           => 'Ukuran file lampiran maksimal 10MB.',
            'chatAttachment.mimes'         => 'Format lampiran harus gambar, PDF, Word, atau ZIP.',
        ]);

        $session = HelpSession::where('id', $this->currentSessionId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$session || !in_array($session->status, ['calling', 'active'])) {
            session()->flash('error', 'Sesi panggilan ini sudah tidak aktif.');
            return;
        }

        $attachmentPath = null;
        $attachmentName = null;

        if ($this->chatAttachment) {
            $attachmentName = $this->chatAttachment->getClientOriginalName();
            $attachmentPath = $this->chatAttachment->store('help-attachments/' . date('Y-m'), 'public');
        }

        HelpMessage::create([
            'help_session_id' => $session->id,
            'sender_id'       => Auth::id(),
            'message'         => $this->chatMessage ?: '(Mengirim lampiran berkas)',
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'is_admin'        => false,
            'is_read'         => false,
        ]);

        $session->update(['last_message_at' => now()]);

        $this->reset(['chatMessage', 'chatAttachment']);
    }

    /**
     * Tandai pesan dari admin sebagai telah dibaca pengguna.
     */
    public function markMessagesAsRead(): void
    {
        if (!$this->currentSessionId) return;

        HelpMessage::where('help_session_id', $this->currentSessionId)
            ->where('is_admin', true)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Reset sesi saat ini untuk memulai panggilan baru setelah sesi sebelumnya selesai.
     */
    public function startNewCallSession(): void
    {
        $this->currentSessionId = null;
    }

    // ===== OFFLINE TICKET METHODS =====
    public function submitOfflineTicket(): void
    {
        $this->validate([
            'ticketSubject'  => 'required|min:5|max:150',
            'ticketCategory' => 'required',
            'ticketMessage'  => 'required|min:10|max:2000',
        ]);

        Ticket::create([
            'user_id'  => Auth::id(),
            'subject'  => $this->ticketSubject,
            'kategori' => $this->ticketCategory,
            'pesan'    => $this->ticketMessage,
            'status'   => 'open',
        ]);

        $this->reset(['ticketSubject', 'ticketMessage']);
        $this->userViewMode = 'history';
        session()->flash('success', 'Pesan tiket bantuan offline Anda berhasil dikirim. Admin akan segera meninjau.');
    }

    public function openTicket(int $id): void
    {
        $this->selectedTicket = Ticket::with('replies.user')
            ->where('user_id', Auth::id())
            ->find($id);
    }

    public function sendTicketReply(): void
    {
        $this->validate(['ticketReplyText' => 'required|min:3']);

        if (!$this->selectedTicket) return;

        TicketReply::create([
            'ticket_id' => $this->selectedTicket->id,
            'user_id'   => Auth::id(),
            'pesan'     => $this->ticketReplyText,
        ]);

        $this->ticketReplyText = '';
        $this->selectedTicket = Ticket::with('replies.user')->find($this->selectedTicket->id);
    }

    public function render(HelpCenterService $service)
    {
        $statusInfo = $service->getStatusInfo();
        $scheduleDetails = $service->getScheduleDetails();
        $topicOptions = $service->getTopicOptions();

        $activeSession = null;
        if ($this->currentSessionId) {
            $activeSession = HelpSession::with(['admin', 'messages.sender'])
                ->where('user_id', Auth::id())
                ->find($this->currentSessionId);

            if ($activeSession && $activeSession->status === 'active') {
                $this->markMessagesAsRead();
            }
        }

        // Riwayat sesi panggilan bantuan pengguna
        $mySessions = HelpSession::with('admin')
            ->where('user_id', Auth::id())
            ->latest()
            ->take(10)
            ->get();

        // Riwayat tiket pengaduan asinkron
        $myTickets = Ticket::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('livewire.mahasiswa.helpdesk', compact(
            'statusInfo',
            'scheduleDetails',
            'topicOptions',
            'activeSession',
            'mySessions',
            'myTickets'
        ));
    }
}
