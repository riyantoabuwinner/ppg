<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Gallery;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ManajemenGaleri extends Component
{
    use WithFileUploads, WithPagination;

    public $search = '';
    public $filterKategori = '';
    public $filterSumber = '';

    // Bulk Actions state
    public $selectedGalleries = [];
    public $selectAll = false;
    public $bulkAction = '';

    // Upload modal state
    public $showUploadModal = false;
    public $judul = '';
    public $kategori = 'Umum';
    public $gambar_upload = null;

    // Preview lightbox modal state
    public $selectedImage = null;

    public function updatingSearch()
    {
        $this->resetPage();
        $this->deselectAll();
    }

    public function updatingFilterKategori()
    {
        $this->resetPage();
        $this->deselectAll();
    }

    public function updatingFilterSumber()
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
        $currentPageIds = $this->getCurrentPageGalleryIds();
        if ($value) {
            $this->selectedGalleries = array_values(array_unique(array_merge(
                array_map('strval', $this->selectedGalleries),
                $currentPageIds
            )));
        } else {
            $this->selectedGalleries = array_values(array_diff(
                array_map('strval', $this->selectedGalleries),
                $currentPageIds
            ));
        }
    }

    public function updatedSelectedGalleries()
    {
        $currentPageIds = $this->getCurrentPageGalleryIds();
        $selectedStr = array_map('strval', $this->selectedGalleries);
        if (!empty($currentPageIds) && count(array_intersect($currentPageIds, $selectedStr)) === count($currentPageIds)) {
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }
    }

    public function selectAllMatching()
    {
        $allIds = Gallery::query()
            ->when($this->search, fn($q) => $q->where('judul', 'like', '%' . $this->search . '%'))
            ->when($this->filterKategori, fn($q) => $q->where('kategori', $this->filterKategori))
            ->when($this->filterSumber, fn($q) => $q->where('sumber', $this->filterSumber))
            ->pluck('id')
            ->map(fn($id) => (string)$id)
            ->toArray();

        $this->selectedGalleries = $allIds;
        $this->selectAll = true;
    }

    public function deselectAll()
    {
        $this->selectedGalleries = [];
        $this->selectAll = false;
        $this->bulkAction = '';
    }

    protected function getCurrentPageGalleryIds(): array
    {
        return Gallery::query()
            ->when($this->search, fn($q) => $q->where('judul', 'like', '%' . $this->search . '%'))
            ->when($this->filterKategori, fn($q) => $q->where('kategori', $this->filterKategori))
            ->when($this->filterSumber, fn($q) => $q->where('sumber', $this->filterSumber))
            ->latest()
            ->paginate(12)
            ->pluck('id')
            ->map(fn($id) => (string)$id)
            ->toArray();
    }

    public function bulkDelete()
    {
        if (empty($this->selectedGalleries)) {
            session()->flash('error', 'Tidak ada foto/media yang dipilih untuk dihapus.');
            return;
        }

        $ids = array_map('intval', $this->selectedGalleries);
        $items = Gallery::whereIn('id', $ids)->get();

        $count = 0;
        foreach ($items as $item) {
            if (Storage::disk('public')->exists($item->file_path)) {
                Storage::disk('public')->delete($item->file_path);
            }
            $item->delete();
            $count++;
        }

        $this->deselectAll();
        session()->flash('message', "{$count} foto berhasil dihapus secara masal dari Galeri.");
    }

    public function executeBulkAction()
    {
        if (empty($this->bulkAction)) {
            session()->flash('error', 'Silakan pilih aksi masal yang ingin dijalankan.');
            return;
        }

        if (empty($this->selectedGalleries)) {
            session()->flash('error', 'Centang minimal satu foto terlebih dahulu.');
            return;
        }

        match ($this->bulkAction) {
            'delete' => $this->bulkDelete(),
            default  => null,
        };
    }

    public function openUploadModal()
    {
        $this->reset(['judul', 'gambar_upload']);
        $this->kategori = 'Umum';
        $this->showUploadModal = true;
    }

    public function closeUploadModal()
    {
        $this->showUploadModal = false;
    }

    public function saveUpload()
    {
        $this->validate([
            'gambar_upload' => 'required|image|max:5120', // max 5MB
            'judul'         => 'nullable|string|max:150',
            'kategori'      => 'required|string|max:50',
        ]);

        $ext = $this->gambar_upload->getClientOriginalExtension();
        $fileName = 'gallery/' . Str::random(20) . '.' . $ext;
        $this->gambar_upload->storeAs('', $fileName, 'public');

        $sizeBytes = $this->gambar_upload->getSize();
        $formattedSize = $this->formatBytes($sizeBytes);
        $mime = $this->gambar_upload->getMimeType();

        $title = $this->judul ?: pathinfo($this->gambar_upload->getClientOriginalName(), PATHINFO_FILENAME);

        $existing = Gallery::whereRaw('LOWER(TRIM(judul)) = ?', [strtolower(trim($title))])->first();
        if ($existing) {
            $this->addError('judul', 'Foto dengan judul/nama file ini sudah ada di Galeri.');
            return;
        }

        Gallery::create([
            'judul'      => $title,
            'file_path'  => $fileName,
            'file_url'   => asset('storage/' . $fileName),
            'kategori'   => $this->kategori,
            'sumber'     => 'manual',
            'ukuran'     => $formattedSize,
            'mime_type'  => $mime,
        ]);

        session()->flash('message', 'Foto berhasil diunggah ke Galeri!');
        $this->showUploadModal = false;
    }

    public function previewImage($id)
    {
        $this->selectedImage = Gallery::find($id);
    }

    public function closePreview()
    {
        $this->selectedImage = null;
    }

    public function delete($id)
    {
        $gal = Gallery::find($id);
        if ($gal) {
            // Delete file from disk if stored locally
            if (Storage::disk('public')->exists($gal->file_path)) {
                Storage::disk('public')->delete($gal->file_path);
            }
            $gal->delete();
            $this->selectedGalleries = array_values(array_diff($this->selectedGalleries, [(string)$id, (int)$id]));
            session()->flash('message', 'Foto berhasil dihapus dari Galeri.');
        }
    }

    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }

    public function render()
    {
        $query = Gallery::with('article')->latest();

        if (!empty($this->search)) {
            $query->where('judul', 'like', '%' . $this->search . '%');
        }

        if (!empty($this->filterKategori)) {
            $query->where('kategori', $this->filterKategori);
        }

        if (!empty($this->filterSumber)) {
            $query->where('sumber', $this->filterSumber);
        }

        $galleries = $query->paginate(12);

        // Get unique categories for filter
        $kategoriList = Gallery::select('kategori')->distinct()->pluck('kategori');

        return view('livewire.admin.manajemen-galeri', [
            'galleries'    => $galleries,
            'kategoriList' => $kategoriList,
            'totalCount'   => Gallery::count(),
            'wpCount'      => Gallery::where('sumber', 'wordpress_import')->count(),
            'manualCount'  => Gallery::where('sumber', 'manual')->count(),
        ]);
    }
}
