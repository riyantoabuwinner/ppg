<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Category;

class MenuBuilder extends Component
{
    public $menuId;

    // Form fields
    public $itemId = null;
    public $title = '';
    public $type = 'module'; // 'module', 'page', 'category', 'custom_link'
    public $reference_id = null;
    public $url = '';
    public $target = '_self';
    public $icon = '';
    public $parent_id = null;
    public $is_active = true;

    // Selected module key
    public $selectedModule = '';

    // Quick icon options
    public $popularIcons = [
        'fas fa-house'            => 'Beranda',
        'fas fa-newspaper'        => 'Berita',
        'fas fa-info-circle'      => 'Tentang',
        'fas fa-graduation-cap'   => 'Akademik',
        'fas fa-book'             => 'Panduan',
        'fas fa-file-alt'         => 'Halaman',
        'fas fa-images'           => 'Galeri',
        'fas fa-bullhorn'         => 'Pengumuman',
        'fas fa-headset'          => 'Helpdesk',
        'fas fa-envelope'         => 'Kontak',
        'fas fa-link'             => 'Tautan',
        'fas fa-user-graduate'    => 'Mahasiswa',
        'fas fa-building-columns' => 'Kampus',
        'fas fa-calendar-alt'     => 'Periode',
        'fas fa-download'         => 'Unduhan',
        'fas fa-shield-alt'       => 'PPID',
    ];

    // System Modules definition
    public function getSystemModules()
    {
        return [
            [
                'key'   => 'landing',
                'name'  => 'Beranda Utama',
                'url'   => '/',
                'icon'  => 'fas fa-house',
            ],
            [
                'key'   => 'berita',
                'name'  => 'Berita & Kegiatan',
                'url'   => '/berita',
                'icon'  => 'fas fa-newspaper',
            ],
            [
                'key'   => 'login',
                'name'  => 'Portal Lapor Diri (Login)',
                'url'   => '/login',
                'icon'  => 'fas fa-right-to-bracket',
            ],
            [
                'key'   => 'helpdesk',
                'name'  => 'Helpdesk & Bantuan Mahasiswa',
                'url'   => '/mahasiswa/helpdesk',
                'icon'  => 'fas fa-headset',
            ],
            [
                'key'   => 'galeri',
                'name'  => 'Galeri Media Dokumentasi',
                'url'   => '/admin/galeri',
                'icon'  => 'fas fa-images',
            ],
            [
                'key'   => 'uinssc',
                'name'  => 'Website Resmi UINSSC',
                'url'   => 'https://uinssc.ac.id',
                'icon'  => 'fas fa-building-columns',
            ],
            [
                'key'   => 'ppid',
                'name'  => 'PPID UINSSC',
                'url'   => 'https://ppid.uinssc.ac.id',
                'icon'  => 'fas fa-shield-alt',
            ],
        ];
    }

    public function mount($menuId)
    {
        $this->menuId = $menuId;
    }

    public function setType($newType)
    {
        $this->type = $newType;
        if ($newType === 'custom_link') {
            $this->reference_id = null;
        }
    }

    public function selectModule($url, $name, $icon)
    {
        $this->url = $url;
        if (empty($this->title)) {
            $this->title = $name;
        }
        if (empty($this->icon)) {
            $this->icon = $icon;
        }
    }

    public function updatedReferenceId($value)
    {
        if ($this->type === 'page' && $value) {
            $page = Page::find($value);
            if ($page && empty($this->title)) {
                $this->title = $page->judul;
            }
            if (empty($this->icon)) {
                $this->icon = 'fas fa-file-alt';
            }
        } elseif ($this->type === 'category' && $value) {
            $cat = Category::find($value);
            if ($cat && empty($this->title)) {
                $this->title = $cat->name;
            }
            if (empty($this->icon)) {
                $this->icon = 'fas fa-folder';
            }
        }
    }

    public function setIcon($iconClass)
    {
        $this->icon = $iconClass;
    }

    public function prepareAddSubItem($parentId)
    {
        $this->cancelEdit();
        $this->parent_id = $parentId;
    }

    public function saveItem()
    {
        $this->validate([
            'title'  => 'required|min:1|max:150',
            'type'   => 'required|in:module,page,category,custom_link',
            'target' => 'required|in:_self,_blank',
        ], [
            'title.required' => 'Label navigasi wajib diisi.',
            'type.required'  => 'Tipe item menu wajib dipilih.',
        ]);

        if ($this->type === 'custom_link' && empty($this->url)) {
            $this->addError('url', 'URL kustom wajib diisi.');
            return;
        }

        if ($this->type === 'page' && empty($this->reference_id)) {
            $this->addError('reference_id', 'Pilih halaman statis rujukan.');
            return;
        }

        if ($this->type === 'category' && empty($this->reference_id)) {
            $this->addError('reference_id', 'Pilih kategori artikel rujukan.');
            return;
        }

        // Parent ID tidak boleh sama dengan item ID sendiri
        $parentId = $this->parent_id ? (int)$this->parent_id : null;
        if ($this->itemId && $parentId === (int)$this->itemId) {
            $parentId = null;
        }

        if ($this->itemId) {
            // Update
            $item = MenuItem::where('menu_id', $this->menuId)->findOrFail($this->itemId);
            $item->update([
                'parent_id'    => $parentId,
                'title'        => $this->title,
                'type'         => $this->type,
                'reference_id' => $this->reference_id ?: null,
                'url'          => $this->url,
                'target'       => $this->target,
                'icon'         => $this->icon,
                'is_active'    => $this->is_active,
            ]);

            session()->flash('success_message', 'Item "' . $this->title . '" berhasil diperbarui.');
        } else {
            // Create
            $maxOrder = MenuItem::where('menu_id', $this->menuId)
                ->where('parent_id', $parentId)
                ->max('order_column') ?? -1;

            MenuItem::create([
                'menu_id'      => $this->menuId,
                'parent_id'    => $parentId,
                'title'        => $this->title,
                'type'         => $this->type,
                'reference_id' => $this->reference_id ?: null,
                'url'          => $this->url,
                'target'       => $this->target,
                'icon'         => $this->icon,
                'order_column' => $maxOrder + 1,
                'is_active'    => $this->is_active,
            ]);

            session()->flash('success_message', 'Item baru "' . $this->title . '" berhasil ditambahkan.');
        }

        $this->cancelEdit();
        $this->dispatch('menu-updated');
    }

    public function editItem($id)
    {
        $item = MenuItem::where('menu_id', $this->menuId)->findOrFail($id);
        $this->itemId       = $item->id;
        $this->title        = $item->title;
        $this->type         = $item->type;
        $this->reference_id = $item->reference_id;
        $this->url          = $item->url;
        $this->target       = $item->target;
        $this->icon         = $item->icon;
        $this->parent_id    = $item->parent_id;
        $this->is_active    = $item->is_active;

        $this->resetErrorBag();
    }

    public function cancelEdit()
    {
        $this->reset([
            'itemId',
            'title',
            'reference_id',
            'url',
            'icon',
            'parent_id',
            'selectedModule',
        ]);
        $this->type = 'module';
        $this->target = '_self';
        $this->is_active = true;
        $this->resetErrorBag();
    }

    public function toggleActive($id)
    {
        $item = MenuItem::where('menu_id', $this->menuId)->find($id);
        if ($item) {
            $item->is_active = !$item->is_active;
            $item->save();
            session()->flash('success_message', 'Status item "' . $item->title . '" berhasil diubah.');
        }
    }

    public function deleteItem($id)
    {
        $item = MenuItem::where('menu_id', $this->menuId)->find($id);
        if ($item) {
            $title = $item->title;
            $item->delete();
            session()->flash('success_message', 'Item "' . $title . '" beserta sub-itemnya berhasil dihapus.');
            if ($this->itemId === $id) {
                $this->cancelEdit();
            }
            $this->dispatch('menu-updated');
        }
    }

    /**
     * Batch update urutan dan hirarki parent-child dari drag-and-drop SortableJS.
     */
    public function updateOrder($items)
    {
        if (!is_array($items)) {
            return;
        }

        foreach ($items as $itemData) {
            if (isset($itemData['id'])) {
                $parentId = !empty($itemData['parent_id']) ? (int)$itemData['parent_id'] : null;
                $order    = isset($itemData['order']) ? (int)$itemData['order'] : 0;

                MenuItem::where('menu_id', $this->menuId)
                    ->where('id', $itemData['id'])
                    ->update([
                        'parent_id'    => $parentId,
                        'order_column' => $order,
                    ]);
            }
        }

        session()->flash('success_message', 'Susunan & hirarki menu berhasil disimpan!');
        $this->dispatch('menu-updated');
    }

    public function render()
    {
        $menu = Menu::with([
            'rootItems' => function ($q) {
                $q->with(['children' => function ($cq) {
                    $cq->with('page')->orderBy('order_column');
                }, 'page'])->orderBy('order_column');
            }
        ])->findOrFail($this->menuId);

        // All items for parent dropdown
        $allItems = MenuItem::where('menu_id', $this->menuId)
            ->when($this->itemId, fn($q) => $q->where('id', '!=', $this->itemId))
            ->orderBy('order_column')
            ->get();

        // Pages published
        $pages = Page::published()->orderBy('judul')->get(['id', 'judul', 'slug']);

        // Categories
        $categories = Category::orderBy('name')->get(['id', 'name', 'slug']);

        return view('livewire.admin.menu-builder', [
            'menu'          => $menu,
            'allItems'      => $allItems,
            'pages'         => $pages,
            'categories'    => $categories,
            'systemModules' => $this->getSystemModules(),
        ]);
    }
}
