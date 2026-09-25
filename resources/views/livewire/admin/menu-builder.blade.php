<div>
    {{-- SortableJS CDN --}}
    @assets
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
    <style>
        .sortable-ghost {
            opacity: 0.35;
            background: #F3E8FF !important;
            border: 2px dashed #9333EA !important;
        }
        .sortable-chosen {
            box-shadow: 0 10px 25px -5px rgba(124, 58, 237, 0.25) !important;
        }
        .drag-handle {
            cursor: grab;
        }
        .drag-handle:active {
            cursor: grabbing;
        }
        .menu-sub-list:empty::before {
            content: 'Tarik item ke sini untuk menjadikannya sub-menu';
            display: block;
            padding: 8px 12px;
            font-size: 11px;
            color: #9CA3AF;
            text-align: center;
            border: 1px dashed #E5E7EB;
            border-radius: 8px;
            background: #F9FAFB;
        }
    </style>
    @endassets

    {{-- Top Action / Breadcrumbs Bar --}}
    <div class="bg-white rounded-2xl p-4 sm:p-5 shadow-sm border border-gray-100 mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center space-x-3.5">
            <a href="{{ route('admin.pengaturan.menu') }}" class="w-10 h-10 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 flex items-center justify-center transition shadow-sm" title="Kembali ke Daftar Menu">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div>
                <div class="flex items-center space-x-2">
                    <h2 class="text-lg font-bold text-gray-800">{{ $menu->name }}</h2>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $menu->location === 'main' ? 'bg-purple-100 text-purple-800 border border-purple-200' : ($menu->location === 'top' ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-800') }}">
                        {{ $menu->location_label }}
                    </span>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">Kelola dan susun item navigasi dengan drag-and-drop interaktif bertingkat.</p>
            </div>
        </div>

        <div class="flex items-center space-x-2.5 self-start md:self-auto">
            <div wire:loading wire:target="updateOrder" class="text-xs font-semibold text-purple-600 bg-purple-50 px-3 py-1.5 rounded-xl border border-purple-200 flex items-center space-x-2">
                <i class="fas fa-spinner fa-spin"></i>
                <span>Menyimpan urutan...</span>
            </div>
            <a href="{{ route('admin.pengaturan.menu') }}" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                Daftar Menu
            </a>
            <a href="{{ route('landing') }}" target="_blank" class="px-3.5 py-2 rounded-xl text-xs font-semibold text-purple-700 bg-purple-50 hover:bg-purple-100 border border-purple-200 transition inline-flex items-center space-x-1.5">
                <i class="fas fa-globe"></i>
                <span>Lihat Frontend</span>
            </a>
        </div>
    </div>

    {{-- Flash Notifications --}}
    @if(session()->has('success_message'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl mb-6 text-sm flex items-center justify-between shadow-sm animate-fade-in">
        <div class="flex items-center space-x-2.5">
            <span class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-bold">✓</span>
            <span class="font-medium">{{ session('success_message') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-sm font-bold">&times;</button>
    </div>
    @endif

    {{-- MAIN 2-COLUMN LAYOUT --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- KOLOM KIRI: FORM TAMBAH / EDIT ITEM --}}
        <div class="lg:col-span-5 space-y-4">
            <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-sm border border-gray-100 sticky top-4">
                
                {{-- Form Header --}}
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-5">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-sm font-bold">
                            <i class="{{ $itemId ? 'fas fa-edit' : 'fas fa-plus' }}"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-800 text-sm">
                                {{ $itemId ? 'Ubah Item Menu' : 'Tambah Item Menu Baru' }}
                            </h3>
                            <p class="text-[11px] text-gray-400">
                                {{ $itemId ? 'Perbarui data navigasi item ini' : 'Tambahkan halaman, modul, atau link kustom' }}
                            </p>
                        </div>
                    </div>
                    @if($itemId)
                    <button wire:click="cancelEdit" class="text-xs text-rose-500 hover:text-rose-700 font-semibold px-2 py-1 rounded-lg hover:bg-rose-50 transition">
                        Batal
                    </button>
                    @endif
                </div>

                <form wire:submit.prevent="saveItem" class="space-y-4">
                    
                    {{-- 1. Pilihan Tipe Sumber Menu (Tab Modern) --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Tipe Sumber Menu <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-1.5 p-1 bg-gray-50 rounded-xl border border-gray-100 text-xs">
                            <button type="button" wire:click="setType('module')" class="py-2 px-2 rounded-lg font-semibold transition text-center flex flex-col items-center justify-center gap-1 {{ $type === 'module' ? 'bg-white shadow text-purple-700 border border-purple-100' : 'text-gray-500 hover:text-gray-800' }}">
                                <i class="fas fa-cubes text-xs"></i>
                                <span>Modul</span>
                            </button>
                            <button type="button" wire:click="setType('page')" class="py-2 px-2 rounded-lg font-semibold transition text-center flex flex-col items-center justify-center gap-1 {{ $type === 'page' ? 'bg-white shadow text-purple-700 border border-purple-100' : 'text-gray-500 hover:text-gray-800' }}">
                                <i class="fas fa-file-alt text-xs"></i>
                                <span>Halaman</span>
                            </button>
                            <button type="button" wire:click="setType('category')" class="py-2 px-2 rounded-lg font-semibold transition text-center flex flex-col items-center justify-center gap-1 {{ $type === 'category' ? 'bg-white shadow text-purple-700 border border-purple-100' : 'text-gray-500 hover:text-gray-800' }}">
                                <i class="fas fa-folder text-xs"></i>
                                <span>Kategori</span>
                            </button>
                            <button type="button" wire:click="setType('custom_link')" class="py-2 px-2 rounded-lg font-semibold transition text-center flex flex-col items-center justify-center gap-1 {{ $type === 'custom_link' ? 'bg-white shadow text-purple-700 border border-purple-100' : 'text-gray-500 hover:text-gray-800' }}">
                                <i class="fas fa-link text-xs"></i>
                                <span>Custom</span>
                            </button>
                        </div>
                    </div>

                    {{-- 2. Input Sesuai Tipe yang Dipilih --}}
                    @if($type === 'module')
                    {{-- MODUL SISTEM --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Pilih Modul Sistem Aplikasi</label>
                        <div class="space-y-1.5 max-h-48 overflow-y-auto pr-1 border border-gray-100 rounded-xl p-2 bg-slate-50/50">
                            @foreach($systemModules as $mod)
                            <button type="button" wire:click="selectModule('{{ $mod['url'] }}', '{{ $mod['name'] }}', '{{ $mod['icon'] }}')" class="w-full text-left p-2 rounded-xl text-xs flex items-center justify-between transition border {{ $url === $mod['url'] ? 'bg-purple-100/70 border-purple-300 text-purple-900 font-bold' : 'bg-white border-gray-100 hover:border-purple-200 text-gray-700' }}">
                                <span class="flex items-center space-x-2">
                                    <i class="{{ $mod['icon'] }} text-purple-600 w-4 text-center"></i>
                                    <span>{{ $mod['name'] }}</span>
                                </span>
                                <span class="font-mono text-[10px] text-gray-400">{{ $mod['url'] }}</span>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @elseif($type === 'page')
                    {{-- HALAMAN STATIS --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Pilih Halaman Statis (CMS Pages) <span class="text-red-500">*</span></label>
                        <select wire:model.live="reference_id" class="w-full border border-gray-200 px-3.5 py-2.5 rounded-xl text-xs bg-white focus:outline-none focus:border-purple-400">
                            <option value="">-- Pilih Halaman Statis --</option>
                            @foreach($pages as $p)
                            <option value="{{ $p->id }}">{{ $p->judul }} (slug: /halaman/{{ $p->slug }})</option>
                            @endforeach
                        </select>
                        @error('reference_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    @elseif($type === 'category')
                    {{-- KATEGORI ARTIKEL --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Pilih Kategori Artikel / Berita <span class="text-red-500">*</span></label>
                        <select wire:model.live="reference_id" class="w-full border border-gray-200 px-3.5 py-2.5 rounded-xl text-xs bg-white focus:outline-none focus:border-purple-400">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('reference_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    {{-- 3. Label Teks Menu --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Label Navigasi <span class="text-red-500">*</span></label>
                        <input type="text" wire:model="title" class="w-full border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 px-3.5 py-2.5 rounded-xl text-xs transition outline-none" placeholder="Teks yang akan muncul di menu (contoh: Beranda, Profil)">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- 4. URL Tujuan --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            URL / Path Tujuan
                            @if($type === 'custom_link') <span class="text-red-500">*</span> @endif
                        </label>
                        <input type="text" wire:model="url" class="w-full border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 px-3.5 py-2.5 rounded-xl text-xs transition outline-none font-mono" placeholder="{{ $type === 'custom_link' ? 'https://... atau /path' : 'Otomatis digenerate sistem' }}" {{ ($type === 'page' || $type === 'category') ? 'readonly' : '' }}>
                        <p class="text-[11px] text-gray-400 mt-1">
                            @if($type === 'custom_link')
                                Bisa berupa relative URL (misal: <code>/kontak</code>) atau eksternal URL (misal: <code>https://kemenag.go.id</code>).
                            @elseif($type === 'page')
                                URL otomatis mengarah ke detail halaman statis.
                            @elseif($type === 'category')
                                URL otomatis mengarah ke artikel berdasar kategori.
                            @else
                                URL rute modul bawaan aplikasi.
                            @endif
                        </p>
                        @error('url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- 5. Target Link & Parent Selection --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Target Jendela</label>
                            <select wire:model="target" class="w-full border border-gray-200 px-3 py-2 rounded-xl text-xs bg-white focus:outline-none focus:border-purple-400">
                                <option value="_self">_self (Halaman yang sama)</option>
                                <option value="_blank">_blank (Tab baru)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1.5">Parent / Induk Menu</label>
                            <select wire:model="parent_id" class="w-full border border-gray-200 px-3 py-2 rounded-xl text-xs bg-white focus:outline-none focus:border-purple-400">
                                <option value="">-- Menu Utama (Root) --</option>
                                @foreach($allItems as $itemOpt)
                                    @if(!$itemOpt->parent_id)
                                    <option value="{{ $itemOpt->id }}">↳ {{ $itemOpt->title }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- 6. Pilihan Icon FontAwesome --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-gray-700">Icon Navigasi</label>
                            @if($icon)
                            <span class="text-xs text-purple-700 font-bold flex items-center space-x-1">
                                <i class="{{ $icon }}"></i>
                                <span class="font-mono text-[10px]">{{ $icon }}</span>
                                <button type="button" wire:click="$set('icon', '')" class="text-gray-400 hover:text-red-500 ml-1">&times;</button>
                            </span>
                            @endif
                        </div>

                        {{-- Quick Icon Grid --}}
                        <div class="grid grid-cols-8 gap-1.5 p-2 bg-slate-50 border border-gray-100 rounded-xl mb-2">
                            @foreach($popularIcons as $iClass => $iLabel)
                            <button type="button" wire:click="setIcon('{{ $iClass }}')" title="{{ $iLabel }} ({{ $iClass }})" class="h-8 rounded-lg flex items-center justify-center text-xs transition border {{ $icon === $iClass ? 'bg-purple-600 text-white border-purple-600 shadow' : 'bg-white border-gray-100 text-gray-600 hover:border-purple-300 hover:text-purple-600' }}">
                                <i class="{{ $iClass }}"></i>
                            </button>
                            @endforeach
                        </div>
                        <input type="text" wire:model="icon" class="w-full border border-gray-200 focus:border-purple-500 px-3 py-2 rounded-xl text-xs font-mono" placeholder="Atau ketik class FontAwesome, cth: fas fa-star">
                    </div>

                    {{-- 7. Status Aktif Checkbox --}}
                    <div class="pt-1">
                        <label class="inline-flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="rounded w-4 h-4 text-purple-600 focus:ring-purple-400 border-gray-300">
                            <span class="text-xs font-medium text-gray-700">Item menu ini aktif dan dapat dilihat pengunjung</span>
                        </label>
                    </div>

                    {{-- Tombol Submit Form --}}
                    <div class="pt-2 flex items-center space-x-2">
                        @if($itemId)
                        <button type="button" wire:click="cancelEdit" class="flex-1 py-2.5 px-4 rounded-xl text-xs font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200 transition text-center">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-2.5 px-4 rounded-xl text-xs font-bold text-white shadow-md transition hover:shadow-lg text-center" style="background: linear-gradient(135deg, #7C3AED, #5B21B6);">
                            Simpan Perubahan
                        </button>
                        @else
                        <button type="submit" class="w-full py-2.5 px-4 rounded-xl text-xs font-bold text-white shadow-md transition hover:shadow-lg text-center flex items-center justify-center space-x-2" style="background: linear-gradient(135deg, #7C3AED, #5B21B6);">
                            <i class="fas fa-plus text-xs"></i>
                            <span>Konfirmasi & Tambah ke Menu</span>
                        </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- KOLOM KANAN: CANVAS DRAG & DROP HIERARCHICAL TREE --}}
        <div class="lg:col-span-7">
            <div class="bg-white rounded-3xl p-5 sm:p-6 shadow-sm border border-gray-100 min-h-[500px]">
                
                {{-- Canvas Header --}}
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 mb-4">
                    <div>
                        <h3 class="font-bold text-gray-800 text-sm flex items-center space-x-2">
                            <i class="fas fa-network-wired text-purple-600"></i>
                            <span>Struktur Hierarki Menu</span>
                        </h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">
                            Seret ikon <i class="fas fa-grip-vertical text-gray-400 mx-0.5"></i> ke atas/bawah untuk ubah urutan, atau geser ke sub-menu anak.
                        </p>
                    </div>
                    <span class="text-xs font-bold text-purple-700 bg-purple-50 px-3 py-1 rounded-full border border-purple-200">
                        {{ $menu->items->count() }} Item
                    </span>
                </div>

                {{-- DRAG AND DROP ROOT CONTAINER --}}
                <div id="menu-builder-tree" class="space-y-3">
                    
                    {{-- Root List Sortable --}}
                    <div class="menu-sortable-list space-y-3 min-h-[100px] p-1" data-parent-id="">
                        
                        @forelse($menu->rootItems as $item)
                        <div class="menu-sortable-item bg-white border border-gray-200 hover:border-purple-300 rounded-2xl p-3.5 shadow-sm transition group" data-id="{{ $item->id }}">
                            
                            {{-- Item Row Card Content --}}
                            <div class="flex items-center justify-between gap-3">
                                
                                {{-- Drag Handle & Info --}}
                                <div class="flex items-center space-x-3 min-w-0">
                                    <div class="drag-handle text-gray-400 hover:text-purple-600 p-1.5 rounded-lg hover:bg-purple-50 transition" title="Tarik untuk memindahkan">
                                        <i class="fas fa-grip-vertical text-sm"></i>
                                    </div>

                                    @if($item->icon)
                                    <div class="w-8 h-8 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center text-xs flex-shrink-0">
                                        <i class="{{ $item->icon }}"></i>
                                    </div>
                                    @else
                                    <div class="w-8 h-8 rounded-xl bg-gray-50 text-gray-400 flex items-center justify-center text-xs flex-shrink-0">
                                        <i class="fas fa-bars"></i>
                                    </div>
                                    @endif

                                    <div class="min-w-0">
                                        <div class="flex items-center space-x-2">
                                            <span class="font-bold text-gray-800 text-sm truncate {{ !$item->is_active ? 'line-through text-gray-400' : '' }}">
                                                {{ $item->title }}
                                            </span>
                                            
                                            {{-- Type Badge --}}
                                            @if($item->type === 'module')
                                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-blue-50 text-blue-700 border border-blue-200">Modul</span>
                                            @elseif($item->type === 'page')
                                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-purple-50 text-purple-700 border border-purple-200">Halaman</span>
                                            @elseif($item->type === 'category')
                                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200">Kategori</span>
                                            @else
                                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-md bg-amber-50 text-amber-700 border border-amber-200">Custom</span>
                                            @endif

                                            @if($item->target === '_blank')
                                                <span class="text-[10px] text-gray-400" title="Buka di tab baru"><i class="fas fa-external-link-alt"></i></span>
                                            @endif
                                        </div>
                                        
                                        <div class="text-[11px] text-gray-400 truncate font-mono mt-0.5">
                                            {{ $item->getUrl() }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex items-center space-x-1.5 flex-shrink-0">
                                    {{-- Tambah Sub Item --}}
                                    <button type="button" wire:click="prepareAddSubItem({{ $item->id }})" class="p-1.5 rounded-lg text-xs font-semibold text-purple-600 hover:bg-purple-50 border border-transparent hover:border-purple-200 transition" title="Tambah Sub-item di bawah ini">
                                        <i class="fas fa-plus"></i> <span class="hidden sm:inline">Sub</span>
                                    </button>

                                    {{-- Toggle Aktif --}}
                                    <button type="button" wire:click="toggleActive({{ $item->id }})" class="p-1.5 rounded-lg text-xs transition {{ $item->is_active ? 'text-emerald-600 hover:bg-emerald-50' : 'text-gray-400 hover:bg-gray-100' }}" title="{{ $item->is_active ? 'Status Aktif (Klik untuk nonaktifkan)' : 'Status Nonaktif (Klik untuk aktifkan)' }}">
                                        <i class="fas {{ $item->is_active ? 'fa-eye' : 'fa-eye-slash' }}"></i>
                                    </button>

                                    {{-- Edit --}}
                                    <button type="button" wire:click="editItem({{ $item->id }})" class="p-1.5 rounded-lg text-xs text-gray-500 hover:text-purple-700 hover:bg-purple-50 transition" title="Ubah Item">
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>

                                    {{-- Hapus --}}
                                    <button type="button" wire:click="deleteItem({{ $item->id }})" wire:confirm="Hapus item '{{ $item->title }}' beserta sub-menunya?" class="p-1.5 rounded-lg text-xs text-gray-400 hover:text-red-600 hover:bg-red-50 transition" title="Hapus Item">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- SUB-ITEMS LIST (NESTED CHILDREN CONTAINER) --}}
                            <div class="menu-sortable-list menu-sub-list mt-3 pl-6 sm:pl-8 border-l-2 border-purple-200 space-y-2 pt-1" data-parent-id="{{ $item->id }}">
                                @foreach($item->children as $child)
                                <div class="menu-sortable-item bg-purple-50/40 border border-purple-100 hover:border-purple-300 rounded-xl p-2.5 shadow-2xs transition group/sub" data-id="{{ $child->id }}">
                                    <div class="flex items-center justify-between gap-2">
                                        
                                        <div class="flex items-center space-x-2.5 min-w-0">
                                            <div class="drag-handle text-gray-400 hover:text-purple-600 p-1 rounded hover:bg-purple-100/50 transition">
                                                <i class="fas fa-grip-vertical text-xs"></i>
                                            </div>

                                            @if($child->icon)
                                            <i class="{{ $child->icon }} text-purple-600 text-xs w-3.5 text-center"></i>
                                            @else
                                            <i class="fas fa-level-up-alt fa-rotate-90 text-purple-400 text-xs"></i>
                                            @endif

                                            <div class="min-w-0">
                                                <div class="flex items-center space-x-1.5">
                                                    <span class="font-semibold text-gray-800 text-xs truncate {{ !$child->is_active ? 'line-through text-gray-400' : '' }}">
                                                        {{ $child->title }}
                                                    </span>
                                                    <span class="text-[9px] px-1.5 py-0.2 rounded bg-white text-gray-500 border border-gray-200">
                                                        {{ $child->type_label }}
                                                    </span>
                                                </div>
                                                <div class="text-[10px] text-gray-400 truncate font-mono">
                                                    {{ $child->getUrl() }}
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center space-x-1 flex-shrink-0">
                                            <button type="button" wire:click="toggleActive({{ $child->id }})" class="p-1 rounded text-xs {{ $child->is_active ? 'text-emerald-600 hover:bg-emerald-50' : 'text-gray-400 hover:bg-gray-100' }}">
                                                <i class="fas {{ $child->is_active ? 'fa-eye' : 'fa-eye-slash' }} text-[11px]"></i>
                                            </button>
                                            <button type="button" wire:click="editItem({{ $child->id }})" class="p-1 rounded text-xs text-gray-500 hover:text-purple-700 hover:bg-purple-100/50">
                                                <i class="fas fa-pencil-alt text-[11px]"></i>
                                            </button>
                                            <button type="button" wire:click="deleteItem({{ $child->id }})" wire:confirm="Hapus sub-item '{{ $child->title }}'?" class="p-1 rounded text-xs text-gray-400 hover:text-red-600 hover:bg-red-50">
                                                <i class="fas fa-trash-alt text-[11px]"></i>
                                            </button>
                                        </div>

                                    </div>
                                </div>
                                @endforeach
                            </div>

                        </div>
                        @empty
                        <div class="py-16 text-center border-2 border-dashed border-gray-200 rounded-3xl p-6">
                            <div class="w-16 h-16 rounded-3xl bg-purple-50 text-purple-400 flex items-center justify-center text-2xl mx-auto mb-3">
                                <i class="fas fa-network-wired"></i>
                            </div>
                            <h4 class="font-bold text-gray-700 text-sm">Belum Ada Item di Menu Ini</h4>
                            <p class="text-xs text-gray-400 max-w-sm mx-auto mt-1 mb-4">
                                Gunakan formulir di sebelah kiri untuk menambahkan item menu pertama Anda (seperti Beranda, Berita, atau Halaman Profil).
                            </p>
                        </div>
                        @endforelse

                    </div>

                </div>

            </div>
        </div>

    </div>

    {{-- SORTABLEJS INITIALIZATION SCRIPT --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            initSortableMenu();
        });

        // Livewire v3/v4 update listeners
        document.addEventListener('livewire:initialized', () => {
            initSortableMenu();
        });

        document.addEventListener('menu-updated', () => {
            setTimeout(initSortableMenu, 150);
        });

        function initSortableMenu() {
            const sortableLists = document.querySelectorAll('.menu-sortable-list');
            
            sortableLists.forEach(list => {
                if (list._sortable) {
                    list._sortable.destroy();
                }

                list._sortable = new Sortable(list, {
                    group: 'nested-menu',
                    animation: 180,
                    handle: '.drag-handle',
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    emptyInsertThreshold: 8,
                    onEnd: function (evt) {
                        collectAndDispatchOrder();
                    }
                });
            });
        }

        function collectAndDispatchOrder() {
            const orderedItems = [];

            // Traverse all sortable lists
            document.querySelectorAll('.menu-sortable-list').forEach(list => {
                const parentId = list.getAttribute('data-parent-id') || null;
                
                // Get immediate sortable children
                const children = Array.from(list.children).filter(child => child.classList.contains('menu-sortable-item'));
                
                children.forEach((itemEl, index) => {
                    const itemId = itemEl.getAttribute('data-id');
                    if (itemId) {
                        orderedItems.push({
                            id: parseInt(itemId),
                            parent_id: parentId ? parseInt(parentId) : null,
                            order: index
                        });
                    }
                });
            });

            if (orderedItems.length > 0 && window.Livewire) {
                const component = Livewire.find(document.querySelector('[wire\\:id]').getAttribute('wire:id'));
                if (component) {
                    component.call('updateOrder', orderedItems);
                }
            }
        }
    </script>
</div>
