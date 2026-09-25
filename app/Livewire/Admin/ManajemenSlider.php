<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Slider;

class ManajemenSlider extends Component
{
    use WithFileUploads;

    public $showForm = false;
    public $editId = null;
    public $judul = '', $subjudul = '', $link_tombol = '', $teks_tombol = 'Selengkapnya', $urutan = 0, $is_active = true;
    public $gambar_upload = null;

    public function openCreate()
    {
        $this->reset(['editId','judul','subjudul','link_tombol','teks_tombol','urutan','is_active','gambar_upload']);
        $this->is_active = true;
        $this->teks_tombol = 'Selengkapnya';
        $this->showForm = true;
    }

    public function edit($id)
    {
        $s = Slider::findOrFail($id);
        $this->editId = $s->id;
        $this->judul = $s->judul;
        $this->subjudul = $s->subjudul;
        $this->link_tombol = $s->link_tombol;
        $this->teks_tombol = $s->teks_tombol;
        $this->urutan = $s->urutan;
        $this->is_active = $s->is_active;
        $this->gambar_upload = null;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'judul'          => 'required|min:3',
            'gambar_upload'  => $this->editId ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ]);

        $data = [
            'judul'       => $this->judul,
            'subjudul'    => $this->subjudul,
            'link_tombol' => $this->link_tombol,
            'teks_tombol' => $this->teks_tombol,
            'urutan'      => $this->urutan,
            'is_active'   => $this->is_active,
        ];

        if ($this->gambar_upload) {
            $data['gambar'] = $this->gambar_upload->store('sliders', 'public');
        }

        if ($this->editId) {
            Slider::find($this->editId)->update($data);
            session()->flash('message', 'Slider berhasil diperbarui.');
        } else {
            Slider::create($data);
            session()->flash('message', 'Slider baru berhasil ditambahkan.');
        }

        $this->showForm = false;
    }

    public function toggleActive($id)
    {
        $s = Slider::find($id);
        $s->update(['is_active' => !$s->is_active]);
    }

    public function delete($id)
    {
        Slider::find($id)?->delete();
        session()->flash('message', 'Slider dihapus.');
    }

    public function render()
    {
        return view('livewire.admin.manajemen-slider', [
            'sliders' => Slider::orderBy('urutan')->get(),
        ]);
    }
}
