<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Partner;
use Illuminate\Support\Facades\Storage;

class ManajemenMitra extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showForm = false;
    public $editId = null;
    
    public $name = '', $url = '', $is_active = true, $sort_order = 0;
    public $logo;
    public $oldLogo;

    public function openCreate()
    {
        $this->reset(['editId', 'name', 'url', 'logo', 'oldLogo']);
        $this->is_active = true;
        $this->sort_order = 0;
        $this->showForm = true;
    }

    public function edit($id)
    {
        $partner = Partner::findOrFail($id);
        $this->editId = $partner->id;
        $this->name = $partner->name;
        $this->url = $partner->url;
        $this->is_active = $partner->is_active;
        $this->sort_order = $partner->sort_order;
        $this->oldLogo = $partner->logo;
        $this->logo = null;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'logo' => $this->editId ? 'nullable|image|max:2048' : 'required|image|max:2048',
        ]);

        $partner = $this->editId ? Partner::find($this->editId) : new Partner();
        $partner->name = $this->name;
        $partner->url = $this->url;
        $partner->is_active = $this->is_active;
        $partner->sort_order = $this->sort_order;

        if ($this->logo) {
            if ($partner->logo) {
                Storage::disk('public')->delete($partner->logo);
            }
            $partner->logo = $this->logo->store('partners', 'public');
        }

        $partner->save();

        session()->flash('message', 'Mitra berhasil disimpan.');
        $this->showForm = false;
    }

    public function delete($id)
    {
        $partner = Partner::findOrFail($id);
        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
        }
        $partner->delete();
        session()->flash('message', 'Mitra berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->is_active = !$partner->is_active;
        $partner->save();
    }

    public function render()
    {
        $partners = Partner::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->paginate(10);
            
        return view('livewire.admin.manajemen-mitra', compact('partners'))->layout('layouts.app');
    }
}
