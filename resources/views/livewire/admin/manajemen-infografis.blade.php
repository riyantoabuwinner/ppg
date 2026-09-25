<div>
    {{-- Header Page --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-black text-gray-900 dark:text-white">Manajemen Infografis</h1>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">Kelola konten poster dan panduan visual lapor diri yang tampil di beranda aplikasi.</p>
        </div>
        <button type="button" wire:click="openCreate" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl font-bold text-xs text-white bg-purple-700 hover:bg-purple-800 shadow-md shadow-purple-700/20 transition cursor-pointer">
            <i class="fa-solid fa-plus"></i> Tambah Infografis
        </button>
    </div>

    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="mb-5 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-2.5 text-xs font-bold">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span>{{ session('message') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 text-sm">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    {{-- Filter Bar --}}
    <div class="bg-white dark:bg-slate-900 rounded-2xl p-4 border border-gray-100 dark:border-slate-800 shadow-sm mb-6 flex flex-col sm:flex-row items-center justify-between gap-3">
        <div class="relative w-full sm:w-80">
            <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari judul infografis..." class="w-full pl-9 pr-4 py-2 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-xs focus:ring-2 focus:ring-purple-500 focus:outline-none dark:text-white">
        </div>

        <div class="flex items-center gap-3 w-full sm:w-auto">
            <select wire:model.live="filterKategori" class="w-full sm:w-auto px-3.5 py-2 bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl text-xs font-semibold focus:ring-2 focus:ring-purple-500 focus:outline-none dark:text-white">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
        </div>
    </div>

    {{-- Grid Infographics --}}
    @if($infographics->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($infographics as $info)
                <div class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-200 dark:border-slate-800 shadow-sm hover:shadow-md transition-all overflow-hidden flex flex-col">
                    {{-- Thumbnail Poster (3:4 ratio) --}}
                    <div class="relative aspect-[3/4] bg-slate-100 dark:bg-slate-800 overflow-hidden group">
                        <img src="{{ $info->image_url }}" alt="{{ $info->judul }}" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-300">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                            <button type="button" wire:click="preview({{ $info->id }})" class="p-2.5 rounded-xl bg-white text-gray-800 hover:text-purple-700 shadow-lg text-xs font-bold transition">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button type="button" wire:click="edit({{ $info->id }})" class="p-2.5 rounded-xl bg-purple-700 text-white hover:bg-purple-800 shadow-lg text-xs font-bold transition">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                        </div>
                        <div class="absolute top-2.5 left-2.5">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-extrabold uppercase bg-purple-700 text-white shadow">
                                {{ $info->kategori }}
                            </span>
                        </div>
                        <div class="absolute top-2.5 right-2.5">
                            <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $info->is_active ? 'bg-emerald-500 text-white' : 'bg-gray-400 text-white' }}">
                                {{ $info->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="p-4 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between text-[11px] text-gray-400 mb-1">
                                <span>Urutan: #{{ $info->urutan }}</span>
                                <span><i class="fa-solid fa-eye mr-0.5"></i> {{ $info->views_count }}</span>
                            </div>
                            <h3 class="font-extrabold text-sm text-gray-900 dark:text-white line-clamp-2 leading-snug">
                                {{ $info->judul }}
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 line-clamp-2 leading-relaxed">
                                {{ $info->deskripsi }}
                            </p>
                        </div>

                        {{-- Footer Buttons --}}
                        <div class="mt-4 pt-3 border-t border-gray-100 dark:border-slate-800 flex items-center justify-between">
                            <button type="button" wire:click="toggleActive({{ $info->id }})" class="text-xs font-bold {{ $info->is_active ? 'text-amber-600 hover:text-amber-700' : 'text-emerald-600 hover:text-emerald-700' }}">
                                {{ $info->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                            <div class="flex items-center gap-1.5">
                                <button type="button" wire:click="edit({{ $info->id }})" class="p-1.5 text-gray-400 hover:text-purple-600 rounded-lg transition" title="Edit">
                                    <i class="fa-solid fa-pen-to-square text-xs"></i>
                                </button>
                                <button type="button" wire:click="delete({{ $info->id }})" wire:confirm="Apakah Anda yakin ingin menghapus infografis ini?" class="p-1.5 text-gray-400 hover:text-rose-600 rounded-lg transition" title="Hapus">
                                    <i class="fa-solid fa-trash-can text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $infographics->links() }}
        </div>
    @else
        <div class="bg-white dark:bg-slate-900 rounded-2xl p-12 text-center border border-gray-200 dark:border-slate-800 text-gray-400">
            <i class="fa-solid fa-images text-4xl text-purple-200 mb-3"></i>
            <h4 class="font-bold text-base text-gray-700 dark:text-slate-300">Belum Ada Infografis</h4>
            <p class="text-xs text-gray-500 mt-1">Klik tombol 'Tambah Infografis' untuk mengunggah poster panduan baru.</p>
        </div>
    @endif

    {{-- Modal Form Create / Edit --}}
    @if($showForm)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fadeIn">
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-gray-100 dark:border-slate-800 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between pb-3.5 border-b border-gray-100 dark:border-slate-800 mb-4">
                    <h3 class="font-extrabold text-base text-gray-900 dark:text-white">
                        {{ $editId ? 'Edit Infografis' : 'Tambah Infografis Baru' }}
                    </h3>
                    <button type="button" wire:click="$set('showForm', false)" class="text-gray-400 hover:text-gray-600">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-4 text-xs">
                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-200 mb-1">Judul Infografis *</label>
                        <input type="text" wire:model="judul" placeholder="Contoh: Alur Lapor Diri PPG Daljab" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        @error('judul') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-200 mb-1">Kategori *</label>
                            <input type="text" wire:model="kategori" list="cat-list" placeholder="Pilih/ketik kategori" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-purple-500 focus:outline-none">
                            <datalist id="cat-list">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">
                                @endforeach
                            </datalist>
                            @error('kategori') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 dark:text-slate-200 mb-1">Nomor Urutan *</label>
                            <input type="number" wire:model="urutan" class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-purple-500 focus:outline-none">
                            @error('urutan') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-200 mb-1">Deskripsi & Catatan Penjelasan</label>
                        <textarea wire:model="deskripsi" rows="3" placeholder="Tuliskan ringkasan isi infografis atau petunjuk berkas..." class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 dark:bg-slate-800 dark:text-white focus:ring-2 focus:ring-purple-500 focus:outline-none"></textarea>
                    </div>

                    <div>
                        <label class="block font-bold text-gray-700 dark:text-slate-200 mb-1">
                            Berkas Gambar Poster (JPG / PNG / WEBP, Max 5MB) {{ $editId ? '(Biarkan kosong jika tidak diganti)' : '*' }}
                        </label>
                        <input type="file" wire:model="gambar_upload" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                        @error('gambar_upload') <span class="text-rose-500 text-[11px]">{{ $message }}</span> @enderror

                        @if ($gambar_upload)
                            <div class="mt-2 text-center p-2 bg-gray-50 dark:bg-slate-800 rounded-xl">
                                <p class="text-[11px] text-gray-500 mb-1 font-semibold">Preview Gambar Terpilih:</p>
                                <img src="{{ $gambar_upload->temporaryUrl() }}" class="max-h-48 mx-auto rounded-lg shadow">
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <input type="checkbox" id="is_active" wire:model="is_active" class="w-4 h-4 text-purple-600 rounded">
                        <label for="is_active" class="font-bold text-gray-700 dark:text-slate-200 cursor-pointer">
                            Tampilkan di Beranda (Aktif)
                        </label>
                    </div>

                    <div class="pt-4 border-t border-gray-100 dark:border-slate-800 flex items-center justify-end gap-2.5">
                        <button type="button" wire:click="$set('showForm', false)" class="px-4 py-2.5 rounded-xl font-semibold text-gray-600 dark:text-slate-300 hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-white bg-purple-700 hover:bg-purple-800 shadow-md shadow-purple-700/20 transition">
                            Simpan Infografis
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Modal Preview Poster --}}
    @if($selectedInfographic)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm animate-fadeIn" wire:click="closePreview">
            <div class="bg-white dark:bg-slate-900 rounded-3xl max-w-2xl w-full p-5 shadow-2xl relative" onclick="event.stopPropagation()">
                <button type="button" wire:click="closePreview" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 text-gray-700 flex items-center justify-center transition">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <div class="mb-3">
                    <span class="px-2.5 py-0.5 rounded-md text-[10px] font-extrabold uppercase bg-purple-100 text-purple-800">
                        {{ $selectedInfographic->kategori }}
                    </span>
                    <h3 class="font-black text-base text-gray-900 dark:text-white mt-1">{{ $selectedInfographic->judul }}</h3>
                </div>
                <div class="bg-slate-950 rounded-2xl overflow-hidden flex items-center justify-center max-h-[70vh] p-2">
                    <img src="{{ $selectedInfographic->image_url }}" alt="{{ $selectedInfographic->judul }}" class="max-h-[65vh] w-auto object-contain rounded-xl">
                </div>
            </div>
        </div>
    @endif
</div>
