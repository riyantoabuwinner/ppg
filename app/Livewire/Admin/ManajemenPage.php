<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ManajemenPage extends Component
{
    public $showForm = false;
    public $editId   = null;
    public $judul    = '';
    public $konten   = '';
    public $status   = 'draft';
    public $meta_description   = '';
    public $tampil_di_menu     = false;
    public $urutan             = 0;
    public $search = '';

    public function openCreate()
    {
        $this->reset(['editId','judul','konten','status','meta_description','tampil_di_menu','urutan']);
        $this->status = 'draft';
        $this->showForm = true;
    }

    public function edit($id)
    {
        $p = Page::findOrFail($id);
        $this->editId            = $p->id;
        $this->judul             = $p->judul;
        $this->konten            = $p->konten;
        $this->status            = $p->status;
        $this->meta_description  = $p->meta_description;
        $this->tampil_di_menu    = $p->tampil_di_menu;
        $this->urutan            = $p->urutan;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'judul'  => 'required|min:3',
            'konten' => 'required|min:5',
        ]);

        $data = [
            'author_id'          => Auth::id(),
            'judul'              => $this->judul,
            'konten'             => $this->konten,
            'status'             => $this->status,
            'meta_description'   => $this->meta_description,
            'tampil_di_menu'     => $this->tampil_di_menu,
            'urutan'             => $this->urutan,
        ];

        if ($this->editId) {
            Page::find($this->editId)->update($data);
            session()->flash('message', 'Halaman berhasil diperbarui.');
        } else {
            $data['slug'] = Str::slug($this->judul) . '-' . Str::random(4);
            Page::create($data);
            session()->flash('message', 'Halaman baru berhasil dibuat.');
        }

        $this->showForm = false;
    }

    public function toggleStatus($id)
    {
        $p = Page::find($id);
        $p->update(['status' => $p->status === 'published' ? 'draft' : 'published']);
    }

    public function delete($id)
    {
        Page::find($id)?->delete();
        session()->flash('message', 'Halaman dihapus.');
    }

    public function render()
    {
        $pages = Page::with('author')
            ->when($this->search, fn($q) => $q->where('judul', 'like', '%' . $this->search . '%'))
            ->orderBy('urutan')
            ->paginate(15);
        return view('livewire.admin.manajemen-page', compact('pages'));
    }
}
