<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Period;
use Carbon\Carbon;

class PeriodeManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    // Modal state
    public $showModal = false;
    public $isEdit = false;
    public $periode_id = null;

    // Delete confirmation state
    public $showDeleteModal = false;
    public $deleteId = null;
    public $deleteNama = '';

    // Form fields
    public $nama = '';
    public $tahun_akademik = '';
    public $semester = 'Ganjil';
    public $tanggal_mulai = '';
    public $tanggal_selesai = '';
    public $is_active = true;
    public $deskripsi = '';

    protected $rules = [
        'nama'            => 'required|string|max:255',
        'tahun_akademik'  => 'nullable|string|max:50',
        'semester'        => 'nullable|string|max:20',
        'tanggal_mulai'   => 'required|date',
        'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        'is_active'       => 'boolean',
        'deskripsi'       => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'nama.required'                  => 'Nama periode wajib diisi.',
        'tanggal_mulai.required'         => 'Tanggal buka lapor diri wajib diisi.',
        'tanggal_selesai.required'       => 'Tanggal tutup lapor diri wajib diisi.',
        'tanggal_selesai.after_or_equal' => 'Tanggal tutup harus setelah atau sama dengan tanggal buka.',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->resetForm();
        $this->isEdit = false;
        $this->is_active = true;

        // Default: mulai sekarang, selesai 14 hari ke depan
        $this->tanggal_mulai = Carbon::now()->format('Y-m-d\TH:i');
        $this->tanggal_selesai = Carbon::now()->addDays(14)->endOfDay()->format('Y-m-d\TH:i');
        $this->tahun_akademik = Carbon::now()->year . '/' . (Carbon::now()->year + 1);

        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $period = Period::findOrFail($id);

        $this->periode_id      = $period->id;
        $this->nama            = $period->nama;
        $this->tahun_akademik  = $period->tahun_akademik;
        $this->semester        = $period->semester ?? 'Ganjil';
        $this->tanggal_mulai   = $period->tanggal_mulai ? $period->tanggal_mulai->format('Y-m-d\TH:i') : '';
        $this->tanggal_selesai = $period->tanggal_selesai ? $period->tanggal_selesai->format('Y-m-d\TH:i') : '';
        $this->is_active       = (bool) $period->is_active;
        $this->deskripsi       = $period->deskripsi;

        $this->isEdit = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'nama'            => $this->nama,
            'tahun_akademik'  => $this->tahun_akademik,
            'semester'        => $this->semester,
            'tanggal_mulai'   => Carbon::parse($this->tanggal_mulai),
            'tanggal_selesai' => Carbon::parse($this->tanggal_selesai),
            'is_active'       => (bool) $this->is_active,
            'deskripsi'       => $this->deskripsi,
        ];

        if ($this->isEdit && $this->periode_id) {
            $period = Period::findOrFail($this->periode_id);
            $period->update($data);
            session()->flash('message', 'Periode lapor diri "' . $this->nama . '" berhasil diperbarui.');
        } else {
            Period::create($data);
            session()->flash('message', 'Periode lapor diri baru "' . $this->nama . '" berhasil ditambahkan.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function toggleActive($id)
    {
        $period = Period::findOrFail($id);
        $period->is_active = !$period->is_active;
        $period->save();

        $statusText = $period->is_active ? 'diaktifkan' : 'dinonaktifkan';
        session()->flash('message', "Status periode \"{$period->nama}\" berhasil {$statusText}.");
    }

    public function confirmDelete($id)
    {
        $period = Period::findOrFail($id);
        $this->deleteId = $period->id;
        $this->deleteNama = $period->nama;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->deleteId) {
            $period = Period::findOrFail($this->deleteId);
            $nama = $period->nama;
            $period->delete();

            session()->flash('message', "Periode \"{$nama}\" berhasil dihapus.");
        }

        $this->showDeleteModal = false;
        $this->deleteId = null;
        $this->deleteNama = '';
    }

    public function resetForm()
    {
        $this->periode_id      = null;
        $this->nama            = '';
        $this->tahun_akademik  = '';
        $this->semester        = 'Ganjil';
        $this->tanggal_mulai   = '';
        $this->tanggal_selesai = '';
        $this->is_active       = true;
        $this->deskripsi       = '';
    }

    public function render()
    {
        $now = Carbon::now();

        // Ringkasan statistik
        $totalCount    = Period::count();
        $openCount     = Period::where('is_active', true)->where('tanggal_mulai', '<=', $now)->where('tanggal_selesai', '>=', $now)->count();
        $upcomingCount = Period::where('is_active', true)->where('tanggal_mulai', '>', $now)->count();
        $closedCount   = Period::where('is_active', true)->where('tanggal_selesai', '<', $now)->count();

        // Query tabel
        $query = Period::withCount('studentProfiles')->latest('tanggal_mulai');

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nama', 'like', '%' . $this->search . '%')
                  ->orWhere('tahun_akademik', 'like', '%' . $this->search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->statusFilter)) {
            switch ($this->statusFilter) {
                case 'open':
                    $query->where('is_active', true)
                          ->where('tanggal_mulai', '<=', $now)
                          ->where('tanggal_selesai', '>=', $now);
                    break;
                case 'upcoming':
                    $query->where('is_active', true)
                          ->where('tanggal_mulai', '>', $now);
                    break;
                case 'closed':
                    $query->where('is_active', true)
                          ->where('tanggal_selesai', '<', $now);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
            }
        }

        $periods = $query->paginate(10);

        return view('livewire.admin.periode-manager', [
            'periods'       => $periods,
            'totalCount'    => $totalCount,
            'openCount'     => $openCount,
            'upcomingCount' => $upcomingCount,
            'closedCount'   => $closedCount,
        ]);
    }
}
