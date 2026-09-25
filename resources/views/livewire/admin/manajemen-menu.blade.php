<div>
    {{-- Flash Message --}}
    @if(session()->has('message'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl mb-5 text-sm flex items-center justify-between shadow-sm animate-fade-in">
        <div class="flex items-center space-x-2.5">
            <span class="w-6 h-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-xs font-bold">✓</span>
            <span class="font-medium">{{ session('message') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 text-sm font-bold">&times;</button>
    </div>
    @endif

    {{-- Header Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-gray-800 flex items-center space-x-2">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                <span>Daftar Kategori Menu Navigasi</span>
            </h2>
            <p class="text-xs text-gray-500 mt-1">Kelola grup navigasi untuk header, top utility bar, footer, dan menu kustom lainnya.</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl font-semibold text-sm text-white shadow-md transition hover:shadow-lg active:scale-95" style="background: linear-gradient(135deg, #7C3AED, #5B21B6);">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Tambah Menu Baru</span>
        </button>
    </div>

    {{-- Modal Form --}}
    @if($showForm)
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-purple-100 transition-all">
            <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between bg-gradient-to-r from-purple-50/50 to-white">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-sm">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <h3 class="font-bold text-gray-800 text-base">{{ $editId ? 'Ubah Informasi Menu' : 'Tambah Menu Navigasi Baru' }}</h3>
                </div>
                <button wire:click="$set('showForm', false)" class="text-gray-400 hover:text-gray-600 w-8 h-8 rounded-full hover:bg-gray-100 flex items-center justify-center transition">
                    &times;
                </button>
            </div>

            <form wire:submit.prevent="save" class="p-6 space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Menu <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="name" class="w-full border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 px-3.5 py-2.5 rounded-xl text-sm transition outline-none" placeholder="Contoh: Menu Header Utama, Top Bar, Footer Links">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Lokasi / Jenis Penempatan <span class="text-red-500">*</span></label>
                    <select wire:model="location" class="w-full border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 px-3.5 py-2.5 rounded-xl text-sm transition outline-none bg-white">
                        <option value="main">Menu Utama (Main Navbar)</option>
                        <option value="top">Menu Atas (Top Utility Bar)</option>
                        <option value="footer">Menu Bawah (Footer Navigation)</option>
                        <option value="sub">Menu Sekunder (Sidebar / Sub)</option>
                        <option value="custom">Menu Kustom Lainnya</option>
                    </select>
                    <p class="text-[11px] text-gray-400 mt-1">Menentukan area di website di mana susunan item menu ini akan ditampilkan.</p>
                    @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end space-x-3 pt-3 border-t border-gray-100">
                    <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm hover:bg-gray-200 transition font-medium">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-xl text-sm font-semibold text-white shadow-sm transition hover:shadow" style="background: linear-gradient(135deg, #7C3AED, #5B21B6);">
                        {{ $editId ? 'Simpan Perubahan' : 'Simpan & Lanjutkan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Search Filter & Info Card --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mb-5">
        <div class="relative w-full sm:w-80">
            <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama menu atau lokasi..." class="w-full pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm bg-white focus:outline-none focus:border-purple-400 focus:ring-2 focus:ring-purple-100 transition shadow-sm">
        </div>
        <div class="text-xs text-gray-500 font-medium self-end sm:self-center">
            Total Menu: <span class="font-bold text-purple-700">{{ $menus->total() }}</span> grup
        </div>
    </div>

    {{-- Table Menu --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-slate-50/75">
                    <tr>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Menu</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Lokasi Penempatan</th>
                        <th class="px-5 py-3.5 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Item</th>
                        <th class="px-5 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Dibuat Pada</th>
                        <th class="px-5 py-3.5 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 bg-white">
                    @forelse($menus as $menu)
                    <tr class="hover:bg-purple-50/30 transition">
                        <td class="px-5 py-4 whitespace-nowrap">
                            <div class="flex items-center space-x-3">
                                <div class="w-9 h-9 rounded-xl bg-purple-100/70 text-purple-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                    <i class="fas fa-sitemap"></i>
                                </div>
                                <div>
                                    <span class="font-bold text-gray-800 text-sm block">{{ $menu->name }}</span>
                                    <span class="text-[11px] text-gray-400">ID: #{{ $menu->id }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if($menu->location === 'main')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-800 border border-purple-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-600 mr-1.5"></span>
                                    Menu Utama (Main)
                                </span>
                            @elseif($menu->location === 'top')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600 mr-1.5"></span>
                                    Menu Atas (Top)
                                </span>
                            @elseif($menu->location === 'footer')
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-600 mr-1.5"></span>
                                    Footer Bar
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-600 mr-1.5"></span>
                                    {{ ucfirst($menu->location) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-700">
                                {{ $menu->items_count }} item
                            </span>
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-xs text-gray-500">
                            {{ $menu->created_at ? $menu->created_at->translatedFormat('d M Y H:i') : '-' }}
                        </td>
                        <td class="px-5 py-4 whitespace-nowrap text-right text-xs">
                            <div class="flex items-center justify-end space-x-2">
                                {{-- Tombol Builder Utama --}}
                                <a href="{{ route('admin.pengaturan.menu.builder', $menu->id) }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-bold text-white shadow-sm transition hover:shadow hover:scale-[1.02]" style="background: linear-gradient(135deg, #7C3AED, #4C1D95);" title="Buka Visual Drag-and-Drop Menu Builder">
                                    <i class="fas fa-arrows-alt text-[11px]"></i>
                                    <span>Builder</span>
                                </a>

                                {{-- Ubah --}}
                                <button wire:click="edit({{ $menu->id }})" class="p-1.5 rounded-lg text-gray-500 hover:text-purple-700 hover:bg-purple-50 transition" title="Ubah Nama/Lokasi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                </button>

                                {{-- Hapus --}}
                                <button wire:click="delete({{ $menu->id }})" wire:confirm="Yakin ingin menghapus menu '{{ $menu->name }}' beserta seluruh item navigasinya?" class="p-1.5 rounded-lg text-gray-400 hover:text-red-600 hover:bg-red-50 transition" title="Hapus Menu">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center space-y-3">
                                <div class="w-14 h-14 rounded-2xl bg-purple-50 text-purple-400 flex items-center justify-center text-2xl">
                                    <i class="fas fa-sitemap"></i>
                                </div>
                                <div class="text-sm font-semibold text-gray-700">Belum Ada Menu Dibuat</div>
                                <p class="text-xs text-gray-400 max-w-sm">Mulai dengan menambahkan grup menu seperti "Menu Header Utama" untuk navbar situs Anda.</p>
                                <button wire:click="openCreate" class="text-xs px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition shadow">
                                    + Tambah Menu Sekarang
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($menus->hasPages())
        <div class="px-5 py-4 border-t border-gray-100">
            {{ $menus->links() }}
        </div>
        @endif
    </div>
</div>
