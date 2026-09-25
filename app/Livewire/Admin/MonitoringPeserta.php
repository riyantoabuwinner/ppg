<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\StudentProfile;
use App\Models\User;
use App\Services\LaporDiriImportService;
use Illuminate\Support\Facades\Storage;

class MonitoringPeserta extends Component
{
    use WithPagination;
    use WithFileUploads;

    public $search = '';
    public $statusFilter = '';
    public $periodeFilter = '';

    // Bulk action state
    public $selectedPesertas = [];
    public $selectAll = false;
    public $bulkAction = '';

    // Import modal states
    public $showImportModal = false;
    public $fileImport;
    public $importStatusDefault = 'submitted'; // submitted, verified, draft
    public $duplicateAction = 'update';        // update, skip
    public $passwordScheme = 'dob';            // dob, nik, default
    public $defaultPassword = '12345678';
    public $importResult = null;

    public function updatingSearch()
    {
        $this->resetPage();
        $this->deselectAll();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
        $this->deselectAll();
    }

    public function updatingPeriodeFilter()
    {
        $this->resetPage();
        $this->deselectAll();
    }

    public function updatingPage()
    {
        $this->selectAll = false;
    }

    public function updatedSelectAll($value)
    {
        $currentPageIds = $this->getCurrentPagePesertaIds();
        if ($value) {
            $this->selectedPesertas = array_values(array_unique(array_merge(
                array_map('strval', $this->selectedPesertas),
                $currentPageIds
            )));
        } else {
            $this->selectedPesertas = array_values(array_diff(
                array_map('strval', $this->selectedPesertas),
                $currentPageIds
            ));
        }
    }

    public function updatedSelectedPesertas()
    {
        $currentPageIds = $this->getCurrentPagePesertaIds();
        $selectedStr = array_map('strval', $this->selectedPesertas);
        if (!empty($currentPageIds) && count(array_intersect($currentPageIds, $selectedStr)) === count($currentPageIds)) {
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }
    }

    public function selectAllMatching()
    {
        $query = StudentProfile::where(function($q) {
            $q->where('nama', 'like', '%' . $this->search . '%')
              ->orWhere('nim', 'like', '%' . $this->search . '%');
        });

        if ($this->statusFilter) {
            $query->whereHas('user', function($q) {
                $q->where('status_lapor_diri', $this->statusFilter);
            });
        }

        $allIds = $query->pluck('user_id')
            ->map(fn($id) => (string) $id)
            ->toArray();

        $this->selectedPesertas = $allIds;
        $this->selectAll = true;
    }

    public function deselectAll()
    {
        $this->selectedPesertas = [];
        $this->selectAll = false;
        $this->bulkAction = '';
    }

    protected function getCurrentPagePesertaIds(): array
    {
        $query = StudentProfile::with(['user', 'education'])
            ->where(function($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('nim', 'like', '%' . $this->search . '%');
            });

        if ($this->statusFilter) {
            $query->whereHas('user', function($q) {
                $q->where('status_lapor_diri', $this->statusFilter);
            });
        }

        return $query->paginate(10)
            ->pluck('user_id')
            ->map(fn($id) => (string) $id)
            ->toArray();
    }

    // --- BULK ACTION METHODS ---

    public function bulkDelete()
    {
        if (empty($this->selectedPesertas)) {
            session()->flash('error', 'Tidak ada data mahasiswa yang dipilih untuk dihapus.');
            return;
        }

        $ids = array_map('intval', $this->selectedPesertas);
        $users = User::whereIn('id', $ids)->where('role', 'mahasiswa')->get();
        $count = 0;

        foreach ($users as $user) {
            if ($user->username) {
                Storage::disk('public')->deleteDirectory('dokumen/' . $user->username);
            }
            $user->delete();
            $count++;
        }

        $this->deselectAll();
        session()->flash('message', "{$count} data mahasiswa lapor diri berhasil dihapus secara masal.");
    }

    public function bulkVerify()
    {
        if (empty($this->selectedPesertas)) {
            session()->flash('error', 'Tidak ada data mahasiswa yang dipilih untuk diverifikasi.');
            return;
        }

        $ids = array_map('intval', $this->selectedPesertas);
        $count = User::whereIn('id', $ids)->where('role', 'mahasiswa')->update([
            'status_lapor_diri' => 'verified',
        ]);

        $this->deselectAll();
        session()->flash('message', "{$count} data mahasiswa berhasil diverifikasi secara masal.");
    }

    public function bulkDraft()
    {
        if (empty($this->selectedPesertas)) {
            session()->flash('error', 'Tidak ada data mahasiswa yang dipilih.');
            return;
        }

        $ids = array_map('intval', $this->selectedPesertas);
        $count = User::whereIn('id', $ids)->where('role', 'mahasiswa')->update([
            'status_lapor_diri' => 'draft',
        ]);

        $this->deselectAll();
        session()->flash('message', "{$count} data mahasiswa berhasil diubah ke status draft secara masal.");
    }

    public function executeBulkAction()
    {
        if (empty($this->selectedPesertas)) {
            session()->flash('error', 'Pilih minimal satu mahasiswa terlebih dahulu.');
            return;
        }

        switch ($this->bulkAction) {
            case 'delete':
                $this->bulkDelete();
                break;
            case 'verify':
                $this->bulkVerify();
                break;
            case 'draft':
                $this->bulkDraft();
                break;
            default:
                session()->flash('error', 'Pilih aksi masal yang valid.');
                break;
        }
    }

    public function verify($userId)
    {
        $user = User::find($userId);
        if ($user && $user->status_lapor_diri === 'submitted') {
            $user->status_lapor_diri = 'verified';
            $user->save();
            
            session()->flash('message', 'Data mahasiswa ' . $user->name . ' berhasil diverifikasi.');
        }
    }

    public function openImportModal()
    {
        $this->reset(['fileImport', 'importResult']);
        $this->resetErrorBag();
        $this->showImportModal = true;
    }

    public function closeImportModal()
    {
        $this->showImportModal = false;
        $this->reset(['fileImport', 'importResult']);
        $this->resetErrorBag();
    }

    public function downloadTemplate()
    {
        return redirect()->route('admin.master-data.template-import');
    }

    public function importLaporDiri(LaporDiriImportService $service)
    {
        $this->validate([
            'fileImport' => 'required|file|mimes:xlsx,xls,csv|max:20480',
        ], [
            'fileImport.required' => 'Silakan pilih berkas Excel atau CSV terlebih dahulu.',
            'fileImport.file'     => 'Berkas yang diunggah tidak valid.',
            'fileImport.mimes'    => 'Format berkas harus berupa .xlsx, .xls, atau .csv.',
            'fileImport.max'      => 'Ukuran berkas maksimal adalah 20MB.',
        ]);

        $options = [
            'duplicate_action' => $this->duplicateAction,
            'status_default'   => $this->importStatusDefault,
            'password_scheme'  => $this->passwordScheme,
            'default_password' => $this->defaultPassword,
        ];

        try {
            $path = $this->fileImport->getRealPath();
            $result = $service->import($path, $options);
            $this->importResult = $result;

            if ($result['imported'] > 0 || $result['updated'] > 0) {
                session()->flash('message', "Impor berhasil! {$result['imported']} data baru ditambahkan, {$result['updated']} data diperbarui, {$result['skipped']} dilewati.");
            }

            $this->resetPage();
        } catch (\Throwable $e) {
            $this->addError('fileImport', 'Gagal memproses berkas: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $query = StudentProfile::with(['user', 'education'])
            ->where(function($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('nim', 'like', '%' . $this->search . '%');
            });

        if ($this->statusFilter) {
            $query->whereHas('user', function($q) {
                $q->where('status_lapor_diri', $this->statusFilter);
            });
        }

        if ($this->periodeFilter) {
            $query->where('periode_id', $this->periodeFilter);
        }

        $pesertas = $query->paginate(10);

        $totalCount     = User::where('role', 'mahasiswa')->count();
        $submittedCount = User::where('role', 'mahasiswa')->where('status_lapor_diri', 'submitted')->count();
        $verifiedCount  = User::where('role', 'mahasiswa')->where('status_lapor_diri', 'verified')->count();
        $draftCount     = User::where('role', 'mahasiswa')->where('status_lapor_diri', 'draft')->count();

        return view('livewire.admin.monitoring-peserta', [
            'pesertas'       => $pesertas,
            'totalCount'     => $totalCount,
            'submittedCount' => $submittedCount,
            'verifiedCount'  => $verifiedCount,
            'draftCount'     => $draftCount,
            'periodes'       => \App\Models\Period::latest('tanggal_mulai')->get(),
        ]);
    }
}
