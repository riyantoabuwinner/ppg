<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Announcement;

class ManajemenPengumuman extends Component
{
    public $showForm = false;
    public $editId = null;
    public $judul = '', $isi = '', $tipe = 'info', $tanggal_mulai = null, $tanggal_selesai = null, $is_active = true;

    public function openCreate()
    {
        $this->reset(['editId','judul','isi','tipe','tanggal_mulai','tanggal_selesai']);
        $this->is_active = true;
        $this->tipe = 'info';
        $this->showForm = true;
    }

    public function edit($id)
    {
        $a = Announcement::findOrFail($id);
        $this->editId = $a->id;
        $this->judul = $a->judul;
        $this->isi = $a->isi;
        $this->tipe = $a->tipe;
        $this->tanggal_mulai = $a->tanggal_mulai?->format('Y-m-d');
        $this->tanggal_selesai = $a->tanggal_selesai?->format('Y-m-d');
        $this->is_active = $a->is_active;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'judul' => 'required|min:3',
            'isi'   => 'required|min:5',
            'tipe'  => 'required|in:info,penting,peringatan',
        ]);

        $data = [
            'judul'           => $this->judul,
            'isi'             => $this->isi,
            'tipe'            => $this->tipe,
            'tanggal_mulai'   => $this->tanggal_mulai ?: null,
            'tanggal_selesai' => $this->tanggal_selesai ?: null,
            'is_active'       => $this->is_active,
        ];

        if ($this->editId) {
            Announcement::find($this->editId)->update($data);
            session()->flash('message', 'Pengumuman berhasil diperbarui.');
        } else {
            Announcement::create($data);
            session()->flash('message', 'Pengumuman baru berhasil ditambahkan.');
        }

        $this->showForm = false;
    }

    public function toggleActive($id)
    {
        $a = Announcement::find($id);
        $a->update(['is_active' => !$a->is_active]);
    }

    public function delete($id)
    {
        Announcement::find($id)?->delete();
        session()->flash('message', 'Pengumuman dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.manajemen-pengumuman', [
            'pengumumans' => Announcement::latest()->get(),
        ]);
    }
}
