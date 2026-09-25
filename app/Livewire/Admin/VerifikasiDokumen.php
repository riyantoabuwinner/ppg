<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Models\StudentDocument;

class VerifikasiDokumen extends Component
{
    public $userId;
    public $peserta;
    public $dokumens;

    // Untuk modal catatan penolakan
    public $showRejectModal = false;
    public $selectedDocId = null;
    public $catatanRevisi = '';

    public function mount($id)
    {
        $this->userId = $id;
        $this->peserta = User::with('profile')->findOrFail($id);
        $this->loadDokumen();
    }

    public function loadDokumen()
    {
        $this->dokumens = StudentDocument::where('user_id', $this->userId)->get();
    }

    public function approve($docId)
    {
        $doc = StudentDocument::find($docId);
        if ($doc) {
            $doc->status = 'approved';
            $doc->catatan_revisi = null;
            $doc->save();
            $this->loadDokumen();
            
            // Cek apakah semua disetujui, jika ya otomatis verifikasi user
            $this->checkAllApproved();
        }
    }

    public function openRejectModal($docId)
    {
        $this->selectedDocId = $docId;
        $this->catatanRevisi = '';
        $this->showRejectModal = true;
    }

    public function reject()
    {
        if ($this->selectedDocId) {
            $doc = StudentDocument::find($this->selectedDocId);
            if ($doc) {
                $doc->status = 'rejected';
                $doc->catatan_revisi = $this->catatanRevisi;
                $doc->save();
                
                // Ubah status lapor diri user kembali ke draft/revision
                $this->peserta->status_lapor_diri = 'draft';
                $this->peserta->save();
            }
        }
        
        $this->showRejectModal = false;
        $this->loadDokumen();
    }

    private function checkAllApproved()
    {
        $totalDocs = $this->dokumens->count();
        $approvedDocs = $this->dokumens->where('status', 'approved')->count();

        if ($totalDocs > 0 && $totalDocs === $approvedDocs) {
            $this->peserta->status_lapor_diri = 'verified';
            $this->peserta->save();
            session()->flash('message', 'Semua dokumen valid. Mahasiswa otomatis terverifikasi.');
        }
    }

    public function render()
    {
        return view('livewire.admin.verifikasi-dokumen');
    }
}
