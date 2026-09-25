<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Article;
use App\Models\Gallery;
use App\Services\WordPressImportService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ManajemenArtikel extends Component
{
    use WithFileUploads, WithPagination;

    public $showForm = false;
    public $editId = null;
    public $judul = '', $excerpt = '', $konten = '', $kategori = 'Berita', $status = 'draft';
    public $gambar_upload = null;
    public $selected_gallery_image = null;
    public $search = '', $filterStatus = '';

    // Bulk Actions state
    public $selectedArticles = [];
    public $selectAll = false;
    public $bulkAction = '';

    // WordPress XML Import state
    public $showImportModal = false;
    public $xml_file = null;
    public $download_images = false;
    public $importSummary = null;

    // Gallery Picker Modal
    public $showGalleryPicker = false;
    public $gallerySearch = '';

    // Bulk selection listeners & handlers
    public function updatedSearch()
    {
        $this->resetPage();
        $this->deselectAll();
    }

    public function updatedFilterStatus()
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
        $currentPageIds = $this->getCurrentPageArticleIds();
        if ($value) {
            $this->selectedArticles = array_values(array_unique(array_merge(
                array_map('strval', $this->selectedArticles),
                $currentPageIds
            )));
        } else {
            $this->selectedArticles = array_values(array_diff(
                array_map('strval', $this->selectedArticles),
                $currentPageIds
            ));
        }
    }

    public function updatedSelectedArticles()
    {
        $currentPageIds = $this->getCurrentPageArticleIds();
        $selectedStr = array_map('strval', $this->selectedArticles);
        if (!empty($currentPageIds) && count(array_intersect($currentPageIds, $selectedStr)) === count($currentPageIds)) {
            $this->selectAll = true;
        } else {
            $this->selectAll = false;
        }
    }

    public function selectAllMatching()
    {
        $allIds = Article::when($this->search, fn($q) => $q->where('judul', 'like', '%' . $this->search . '%'))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();

        $this->selectedArticles = $allIds;
        $this->selectAll = true;
    }

    public function deselectAll()
    {
        $this->selectedArticles = [];
        $this->selectAll = false;
        $this->bulkAction = '';
    }

    protected function getCurrentPageArticleIds(): array
    {
        return Article::when($this->search, fn($q) => $q->where('judul', 'like', '%' . $this->search . '%'))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(10)
            ->pluck('id')
            ->map(fn($id) => (string) $id)
            ->toArray();
    }

    // --- BULK ACTION METHODS ---
    public function bulkDelete()
    {
        if (empty($this->selectedArticles)) {
            session()->flash('error', 'Tidak ada artikel yang dipilih untuk dihapus.');
            return;
        }

        $ids = array_map('intval', $this->selectedArticles);
        $count = Article::whereIn('id', $ids)->delete();

        $this->deselectAll();
        session()->flash('message', "{$count} artikel berhasil dihapus secara masal.");
    }

    public function bulkPublish()
    {
        if (empty($this->selectedArticles)) {
            session()->flash('error', 'Tidak ada artikel yang dipilih untuk dipublikasikan.');
            return;
        }

        $ids = array_map('intval', $this->selectedArticles);
        $count = Article::whereIn('id', $ids)->update([
            'status' => 'published',
            'published_at' => Carbon::now(),
        ]);

        $this->deselectAll();
        session()->flash('message', "{$count} artikel berhasil dipublikasikan secara masal.");
    }

    public function bulkDraft()
    {
        if (empty($this->selectedArticles)) {
            session()->flash('error', 'Tidak ada artikel yang dipilih.');
            return;
        }

        $ids = array_map('intval', $this->selectedArticles);
        $count = Article::whereIn('id', $ids)->update([
            'status' => 'draft',
        ]);

        $this->deselectAll();
        session()->flash('message', "{$count} artikel berhasil diubah statusnya menjadi draft.");
    }

    public function executeBulkAction()
    {
        if (empty($this->bulkAction)) {
            session()->flash('error', 'Silakan pilih aksi masal yang ingin dijalankan.');
            return;
        }

        if (empty($this->selectedArticles)) {
            session()->flash('error', 'Centang minimal satu artikel terlebih dahulu.');
            return;
        }

        match ($this->bulkAction) {
            'delete'  => $this->bulkDelete(),
            'publish' => $this->bulkPublish(),
            'draft'   => $this->bulkDraft(),
            default   => null,
        };
    }

    public function openCreate()
    {
        $this->reset(['editId','judul','excerpt','konten','kategori','status','gambar_upload','selected_gallery_image']);
        $this->kategori = 'Berita';
        $this->status = 'draft';
        $this->showForm = true;
    }

    public function edit($id)
    {
        $a = Article::findOrFail($id);
        $this->editId = $a->id;
        $this->judul = $a->judul;
        $this->excerpt = $a->excerpt;
        $this->konten = $a->konten;
        $this->kategori = $a->kategori;
        $this->status = $a->status;
        $this->selected_gallery_image = $a->gambar;
        $this->gambar_upload = null;
        $this->showForm = true;
    }

    public function save()
    {
        $this->validate([
            'judul'         => 'required|min:5|unique:articles,judul,' . ($this->editId ?: 'NULL') . ',id',
            'konten'        => 'required|min:10',
            'gambar_upload' => 'nullable|image|max:3072',
        ], [
            'judul.unique'  => 'Artikel dengan judul ini sudah ada di sistem. Harap gunakan judul yang berbeda agar tidak duplikat.',
        ]);

        $data = [
            'author_id'    => Auth::id(),
            'judul'        => $this->judul,
            'slug'         => Str::slug($this->judul) . '-' . Str::random(4),
            'excerpt'      => $this->excerpt ?: Str::limit(strip_tags($this->konten), 150),
            'konten'       => $this->konten,
            'kategori'     => $this->kategori,
            'status'       => $this->status,
            'published_at' => $this->status === 'published' ? Carbon::now() : null,
        ];

        if ($this->gambar_upload) {
            $data['gambar'] = $this->gambar_upload->store('artikel', 'public');
        } elseif ($this->selected_gallery_image) {
            $data['gambar'] = $this->selected_gallery_image;
        }

        if ($this->editId) {
            $article = Article::find($this->editId);
            unset($data['slug']);
            if ($this->status === 'published' && $article->status === 'draft') {
                $data['published_at'] = Carbon::now();
            }
            $article->update($data);
            session()->flash('message', 'Artikel berhasil diperbarui.');
        } else {
            Article::create($data);
            session()->flash('message', 'Artikel baru berhasil ditambahkan.');
        }

        $this->showForm = false;
    }

    public function publish($id)
    {
        Article::find($id)?->update(['status' => 'published', 'published_at' => Carbon::now()]);
        session()->flash('message', 'Artikel dipublikasikan.');
    }

    public function unpublish($id)
    {
        Article::find($id)?->update(['status' => 'draft']);
        session()->flash('message', 'Artikel dikembalikan ke Draft.');
    }

    public function delete($id)
    {
        Article::find($id)?->delete();
        $this->selectedArticles = array_values(array_diff($this->selectedArticles, [(string)$id, (int)$id]));
        session()->flash('message', 'Artikel dihapus.');
    }

    // --- WORDPRESS IMPORT METHODS ---
    public function openImportModal()
    {
        $this->reset(['xml_file', 'importSummary']);
        $this->download_images = true;
        $this->showImportModal = true;
    }

    public function closeImportModal()
    {
        $this->showImportModal = false;
    }

    public function processImport(WordPressImportService $service)
    {
        if (function_exists('set_time_limit')) {
            @set_time_limit(300);
        }
        @ini_set('max_execution_time', '300');

        $this->validate([
            'xml_file' => 'required|file|max:30720', // max 30MB
        ]);

        $realPath = $this->xml_file->getRealPath();

        try {
            $result = $service->import($realPath, Auth::id(), $this->download_images);
            $this->importSummary = $result;

            session()->flash('message', "Import selesai! Berhasil mengimpor {$result['articles_count']} artikel dan menyimpan {$result['galleries_count']} gambar ke menu Galeri.");
            $this->resetPage();
        } catch (\Exception $e) {
            $this->addError('xml_file', 'Gagal memproses file XML: ' . $e->getMessage());
        }
    }

    // --- GALLERY PICKER METHODS ---
    public function openGalleryPicker()
    {
        $this->showGalleryPicker = true;
    }

    public function closeGalleryPicker()
    {
        $this->showGalleryPicker = false;
    }

    public function selectImageFromGallery($path)
    {
        $this->selected_gallery_image = $path;
        $this->gambar_upload = null;
        $this->showGalleryPicker = false;
    }

    public function removeSelectedImage()
    {
        $this->selected_gallery_image = null;
        $this->gambar_upload = null;
    }

    public function render()
    {
        $articles = Article::with('author')
            ->when($this->search, fn($q) => $q->where('judul', 'like', '%' . $this->search . '%'))
            ->when($this->filterStatus, fn($q) => $q->where('status', $this->filterStatus))
            ->latest()
            ->paginate(10);

        $galleries = [];
        if ($this->showGalleryPicker) {
            $galleries = Gallery::when($this->gallerySearch, fn($q) => $q->where('judul', 'like', '%' . $this->gallerySearch . '%'))
                ->latest()
                ->limit(24)
                ->get();
        }

        return view('livewire.admin.manajemen-artikel', [
            'articles'  => $articles,
            'galleries' => $galleries,
        ]);
    }
}
