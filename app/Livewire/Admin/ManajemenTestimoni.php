<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Storage;

class ManajemenTestimoni extends Component
{
    use WithPagination, WithFileUploads;

    public $search = '';
    public $showForm = false;
    public $editId = null;
    
    public $name = '', $subtitle = '', $type = 'text', $content = '', $media_url = '', $rating = 5, $is_active = true, $sort_order = 0;
    public $avatar;
    public $oldAvatar;

    public function openCreate()
    {
        $this->reset(['editId', 'name', 'subtitle', 'type', 'content', 'media_url', 'avatar', 'oldAvatar']);
        $this->rating = 5;
        $this->is_active = true;
        $this->sort_order = 0;
        $this->showForm = true;
    }

    public function edit($id)
    {
        $testi = Testimonial::findOrFail($id);
        $this->editId = $testi->id;
        $this->name = $testi->name;
        $this->subtitle = $testi->subtitle;
        $this->type = $testi->type;
        $this->content = $testi->content;
        $this->media_url = $testi->media_url;
        $this->rating = $testi->rating;
        $this->is_active = $testi->is_active;
        $this->sort_order = $testi->sort_order;
        $this->oldAvatar = $testi->avatar;
        $this->avatar = null;
        $this->showForm = true;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'type' => 'required|in:text,video',
            'rating' => 'required|integer|min:1|max:5',
        ];

        if ($this->type === 'text') {
            $rules['content'] = 'required|string';
        } else {
            $rules['media_url'] = 'required|string';
        }

        $this->validate($rules);

        $testi = $this->editId ? Testimonial::find($this->editId) : new Testimonial();
        $testi->name = $this->name;
        $testi->subtitle = $this->subtitle;
        $testi->type = $this->type;
        $testi->content = $this->content;
        $testi->media_url = $this->media_url;
        $testi->rating = $this->rating;
        $testi->is_active = $this->is_active;
        $testi->sort_order = $this->sort_order;

        if ($this->avatar) {
            if ($testi->avatar) {
                Storage::disk('public')->delete($testi->avatar);
            }
            $testi->avatar = $this->avatar->store('testimonials', 'public');
        }

        $testi->save();

        session()->flash('message', 'Testimoni berhasil disimpan.');
        $this->showForm = false;
    }

    public function delete($id)
    {
        $testi = Testimonial::findOrFail($id);
        if ($testi->avatar) {
            Storage::disk('public')->delete($testi->avatar);
        }
        $testi->delete();
        session()->flash('message', 'Testimoni berhasil dihapus.');
    }

    public function toggleActive($id)
    {
        $testi = Testimonial::findOrFail($id);
        $testi->is_active = !$testi->is_active;
        $testi->save();
    }

    public function render()
    {
        $testimonials = Testimonial::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->paginate(10);
            
        return view('livewire.admin.manajemen-testimoni', compact('testimonials'))->layout('layouts.app');
    }
}
