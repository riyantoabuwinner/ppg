<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Infographic;
use Illuminate\Support\Facades\Storage;

class ManajemenInfografis extends Component
{
    use WithFileUploads, WithPagination;

    public $showForm = false;
    public $editId = null;
    public $judul = '';
    public $kategori = 'Alur Lapor Diri';
    public $deskripsi = '';
    public $urutan = 0;
    public $is_active = true;
    public $gambar_upload = null;
    public $search = '';
    public $filterKategori = '';

    public $selectedInfographic = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterKategori()
    {
        $this->resetPage();
    }

    public function openCreate()
    {
        $this->reset(['editId', 'judul', 'kategori', 'deskripsi', 'urutan', 'is_active', 'gambar_upload']);
        $this->kategori = 'Alur Lapor Diri';
        $this->is_active = true;
        $this->urutan = Infographic::count() + 1;
        $this->showForm = true;
    }

    public function edit($id)
    {
        $info = Infographic::findOrFail($id);
        $this->editId = $info->id;
        $this->judul = $info->judul;
        $this->kategori = $info->kategori;
        $this->deskripsi = $info->deskripsi;
        $this->urutan = $info->urutan;
        $this->is_active = $info->is_active;
        $this->gambar_upload = null;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'judul'         => 'required|min:3',
            'kategori'      => 'required',
            'deskripsi'     => 'nullable',
            'urutan'        => 'required|integer',
            'gambar_upload' => $this->editId ? 'nullable|image|max:5120' : 'required|image|max:5120',
        ]);

        $data = [
            'judul'     => $this->judul,
            'kategori'  => $this->kategori,
            'deskripsi' => $this->deskripsi,
            'urutan'    => (int)$this->urutan,
            'is_active' => $this->is_active,
        ];

        if ($this->gambar_upload) {
            $path = $this->gambar_upload->store('infographics', 'public');
            $data['gambar'] = $path;
        }

        if ($this->editId) {
            $info = Infographic::findOrFail($this->editId);
            if ($this->gambar_upload && !empty($info->gambar) && Storage::disk('public')->exists($info->gambar)) {
                // Delete old uploaded image if replaced
                Storage::disk('public')->delete($info->gambar);
            }
            $info->update($data);
            session()->flash('message', 'Infografis berhasil diperbarui.');
        } else {
            Infographic::create($data);
            session()->flash('message', 'Infografis baru berhasil ditambahkan.');
        }

        $this->showForm = false;
        $this->reset(['editId', 'judul', 'kategori', 'deskripsi', 'urutan', 'is_active', 'gambar_upload']);
    }

    public function toggleActive($id)
    {
        $info = Infographic::find($id);
        if ($info) {
            $info->update(['is_active' => !$info->is_active]);
        }
    }

    public function delete($id)
    {
        $info = Infographic::find($id);
        if ($info) {
            if (!empty($info->gambar) && Storage::disk('public')->exists($info->gambar)) {
                Storage::disk('public')->delete($info->gambar);
            }
            $info->delete();
            session()->flash('message', 'Infografis berhasil dihapus.');
        }
    }

    public function preview($id)
    {
        $this->selectedInfographic = Infographic::find($id);
    }

    public function closePreview()
    {
        $this->selectedInfographic = null;
    }

    public function render()
    {
        $infographics = Infographic::query()
            ->when($this->search, fn($q) => $q->where(function($sub) {
                $sub->where('judul', 'like', "%{$this->search}%")
                    ->orWhere('deskripsi', 'like', "%{$this->search}%");
            }))
            ->when($this->filterKategori, fn($q) => $q->where('kategori', $this->filterKategori))
            ->orderBy('urutan')
            ->latest('id')
            ->paginate(12);

        $categories = [
            'Alur Lapor Diri',
            'Syarat Berkas',
            'Ketentuan Foto',
            'Layanan Bantuan',
            'Pedoman Akademik',
            'Umum'
        ];

        return view('livewire.admin.manajemen-infografis', compact('infographics', 'categories'));
    }
}
