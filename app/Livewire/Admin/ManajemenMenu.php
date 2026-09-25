<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Menu;

class ManajemenMenu extends Component
{
    use WithPagination;

    public $showForm = false;
    public $editId   = null;
    public $name     = '';
    public $location = 'main';
    public $search   = '';

    protected $rules = [
        'name'     => 'required|min:3|max:100',
        'location' => 'required|string|max:50',
    ];

    protected $messages = [
        'name.required'     => 'Nama menu wajib diisi.',
        'name.min'          => 'Nama menu minimal 3 karakter.',
        'location.required' => 'Pilih lokasi menu.',
    ];

    public function openCreate()
    {
        $this->reset(['editId', 'name', 'location']);
        $this->location = 'main';
        $this->resetErrorBag();
        $this->showForm = true;
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $this->editId   = $menu->id;
        $this->name     = $menu->name;
        $this->location = $menu->location;
        $this->resetErrorBag();
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name'     => $this->name,
            'location' => $this->location,
        ];

        if ($this->editId) {
            $menu = Menu::findOrFail($this->editId);
            $menu->update($data);
            session()->flash('message', 'Menu "' . $menu->name . '" berhasil diperbarui.');
        } else {
            $menu = Menu::create($data);
            session()->flash('message', 'Menu baru "' . $menu->name . '" berhasil ditambahkan.');
        }

        $this->showForm = false;
        $this->reset(['editId', 'name', 'location']);
    }

    public function delete($id)
    {
        $menu = Menu::find($id);
        if ($menu) {
            $name = $menu->name;
            $menu->delete();
            session()->flash('message', 'Menu "' . $name . '" beserta seluruh itemnya berhasil dihapus.');
        }
    }

    public function render()
    {
        $menus = Menu::withCount('items')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('location', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.admin.manajemen-menu', [
            'menus' => $menus,
        ]);
    }
}
