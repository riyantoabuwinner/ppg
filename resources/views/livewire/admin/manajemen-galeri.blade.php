<div>
    @if(session()->has('message'))
    <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-6 text-sm flex items-center space-x-2 shadow-sm">
        <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>{{ session('message') }}</span>
    </div>
    @endif

    @if(session()->has('error'))
    <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-6 text-sm flex items-center space-x-2 shadow-sm">
        <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    {{-- Stats Bar --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="card p-4 flex items-center space-x-3 border-l-4 border-purple-500">
            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div class="text-xl font-extrabold text-gray-800">{{ $totalCount }}</div>
                <div class="text-xs text-gray-500 font-medium">Total Media di Galeri</div>
            </div>
        </div>

        <div class="card p-4 flex items-center space-x-3 border-l-4 border-blue-500">
            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
            </div>
            <div>
                <div class="text-xl font-extrabold text-gray-800">{{ $wpCount }}</div>
                <div class="text-xs text-gray-500 font-medium">Dari Import WordPress (.xml)</div>
            </div>
        </div>

        <div class="card p-4 flex items-center space-x-3 border-l-4 border-green-500">
            <div class="w-10 h-10 rounded-xl bg-green-100 text-green-700 flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            </div>
            <div>
                <div class="text-xl font-extrabold text-gray-800">{{ $manualCount }}</div>
                <div class="text-xs text-gray-500 font-medium">Unggahan Manual</div>
            </div>
        </div>
    </div>

    {{-- Filter & Actions Toolbar --}}
    <div class="card p-5 mb-4">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-2 flex-1">
                {{-- Search --}}
                <div class="relative flex-1 min-w-[200px] max-w-xs">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari judul gambar..." class="w-full text-xs pl-8 pr-3 py-2 border border-purple-200 rounded-lg focus:ring-purple-500 focus:border-purple-500 outline-none">
                    <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                {{-- Filter Kategori --}}
                <select wire:model.live="filterKategori" class="text-xs border border-purple-200 rounded-lg px-3 py-2 bg-white text-gray-700 focus:ring-purple-500 focus:border-purple-500 outline-none">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriList as $kat)
                        <option value="{{ $kat }}">{{ $kat }}</option>
                    @endforeach
                </select>

                {{-- Filter Sumber --}}
                <select wire:model.live="filterSumber" class="text-xs border border-purple-200 rounded-lg px-3 py-2 bg-white text-gray-700 focus:ring-purple-500 focus:border-purple-500 outline-none">
                    <option value="">Semua Sumber</option>
                    <option value="wordpress_import">WordPress Import</option>
                    <option value="manual">Unggah Manual</option>
                </select>

                @if($search || $filterKategori || $filterSumber)
                <button wire:click="$set('search', ''); $set('filterKategori', ''); $set('filterSumber', '')" class="text-xs text-purple-600 hover:text-purple-800 font-semibold px-2 py-1">
                    Reset Filter
                </button>
                @endif
            </div>

            <div class="flex flex-wrap items-center gap-2">
                {{-- Bulk Action Dropdown Control --}}
                <div class="flex items-center space-x-1.5">
                    <select wire:model="bulkAction" class="border border-purple-200 rounded-lg px-3 py-2 text-xs focus:ring-purple-500 focus:border-purple-500 outline-none bg-white">
                        <option value="">-- Aksi Masal --</option>
                        <option value="delete">🗑️ Hapus Masal (Delete)</option>
                    </select>
                    <button wire:click="executeBulkAction" 
                            @if($bulkAction === 'delete') onclick="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus masal ' + @js(count($selectedGalleries)) + ' media terpilih dari Galeri?') || event.stopImmediatePropagation()" @endif
                            class="px-3.5 py-2 bg-purple-900 hover:bg-purple-800 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg text-xs font-semibold shadow-sm transition flex items-center space-x-1"
                            @if(empty($selectedGalleries)) disabled title="Centang minimal satu foto terlebih dahulu" @endif>
                        <span>Terapkan</span>
                        @if(count($selectedGalleries) > 0)
                        <span class="bg-purple-700 text-white rounded-full px-1.5 py-0.2 text-[10px]">{{ count($selectedGalleries) }}</span>
                        @endif
                    </button>
                </div>

                {{-- Upload Button --}}
                <button wire:click="openUploadModal" class="btn-primary text-xs px-4 py-2 flex items-center space-x-1.5 shadow">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Unggah Foto Baru</span>
                </button>
            </div>
        </div>

        {{-- Quick Selection Bar --}}
        @if(!$galleries->isEmpty())
        <div class="mt-3 pt-3 border-t border-gray-100 flex items-center justify-between text-xs text-gray-500">
            <label class="flex items-center space-x-2 cursor-pointer font-medium hover:text-purple-700 select-none">
                <input type="checkbox" wire:model.live="selectAll" class="rounded border-purple-300 text-purple-600 focus:ring-purple-500 cursor-pointer">
                <span>Pilih Semua di Halaman Ini ({{ count($galleries) }} item)</span>
            </label>
            @if(count($selectedGalleries) > 0)
            <span class="text-purple-700 font-semibold">{{ count($selectedGalleries) }} foto dipilih</span>
            @endif
        </div>
        @endif
    </div>

    {{-- Active Bulk Selection Banner --}}
    @if(count($selectedGalleries) > 0)
    <div class="bg-gradient-to-r from-purple-900 via-purple-800 to-indigo-900 text-white rounded-xl p-3.5 sm:p-4 mb-6 shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-3 border border-purple-700/50">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <div class="font-bold text-xs sm:text-sm flex items-center space-x-2">
                    <span class="bg-white/20 px-2 py-0.5 rounded text-[11px] font-mono">{{ count($selectedGalleries) }} terpilih</span>
                    <span>Media galeri siap diproses masal</span>
                </div>
                <div class="text-[11px] text-purple-200 mt-0.5 flex items-center space-x-2">
                    @if(count($selectedGalleries) < $galleries->total())
                    <button wire:click="selectAllMatching" class="underline hover:text-white font-semibold">
                        Pilih semua {{ $galleries->total() }} media (semua halaman)
                    </button>
                    <span>•</span>
                    @endif
                    <button wire:click="deselectAll" class="underline hover:text-white">
                        Batalkan pilihan
                    </button>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            {{-- Bulk Delete (Permintaan Khusus User) --}}
            <button wire:click="bulkDelete" onclick="confirm('PERINGATAN: Apakah Anda yakin ingin MENGHAPUS PERMANEN ' + {{ count($selectedGalleries) }} + ' foto/media terpilih dari Galeri?') || event.stopImmediatePropagation()" wire:loading.attr="disabled" class="px-4 py-2 bg-red-600 hover:bg-red-500 active:bg-red-700 text-white text-xs font-semibold rounded-lg shadow-sm flex items-center space-x-1.5 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>Hapus Masal</span>
            </button>

            {{-- Deselect All --}}
            <button wire:click="deselectAll" class="px-3.5 py-2 bg-white/20 hover:bg-white/30 text-white text-xs font-medium rounded-lg transition">
                Batal
            </button>
        </div>
    </div>
    @endif

    {{-- Gallery Grid --}}
    @if($galleries->isEmpty())
    <div class="card p-12 text-center text-gray-400">
        <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <p class="font-bold text-gray-600">Belum ada media di Galeri</p>
        <p class="text-xs mt-1 text-gray-400">Media akan otomatis tersimpan di sini saat Anda mengimpor file XML dari WordPress atau mengunggah secara manual.</p>
    </div>
    @else
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4 mb-6">
        @foreach($galleries as $item)
        @php $isSelected = in_array((string)$item->id, array_map('strval', $selectedGalleries)); @endphp
        <div class="card overflow-hidden group hover:shadow-lg transition-all duration-200 flex flex-col border {{ $isSelected ? 'border-purple-600 ring-2 ring-purple-600 bg-purple-50/20' : 'border-purple-100' }}">
            {{-- Image Thumbnail --}}
            <div class="relative w-full h-36 bg-gray-100 overflow-hidden">
                <img src="{{ $item->url }}" alt="{{ $item->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 cursor-pointer" wire:click="previewImage({{ $item->id }})" loading="lazy">
                
                {{-- Checkbox --}}
                <div class="absolute top-2 right-2 z-10">
                    <input type="checkbox" wire:model.live="selectedGalleries" value="{{ (string)$item->id }}" title="Pilih foto ini" class="w-4 h-4 rounded border-purple-300 text-purple-600 focus:ring-purple-500 cursor-pointer shadow bg-white/95 transition">
                </div>

                {{-- Source Badge --}}
                <div class="absolute top-2 left-2">
                    @if($item->sumber === 'wordpress_import')
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-blue-600/90 text-white backdrop-blur shadow">WP Import</span>
                    @else
                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-green-600/90 text-white backdrop-blur shadow">Manual</span>
                    @endif
                </div>

                {{-- Hover Overlay --}}
                <div class="absolute inset-0 bg-purple-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center space-x-2 pointer-events-none">
                    <span class="p-1.5 rounded-full bg-white/80 text-purple-700 shadow" title="Pratinjau">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </span>
                </div>
            </div>

            {{-- Info --}}
            <div class="p-3 flex-1 flex flex-col justify-between bg-white text-xs">
                <div>
                    <h4 class="font-bold text-gray-800 truncate" title="{{ $item->judul }}">{{ $item->judul ?: 'Tanpa Judul' }}</h4>
                    <div class="flex items-center justify-between text-[11px] text-gray-400 mt-1">
                        <span>{{ $item->ukuran ?: 'N/A' }}</span>
                        <span class="truncate max-w-[80px]">{{ $item->created_at->format('d M Y') }}</span>
                    </div>
                    @if($item->article)
                    <div class="mt-1 text-[10px] text-purple-600 truncate" title="Terkait artikel: {{ $item->article->judul }}">
                        📰 {{ $item->article->judul }}
                    </div>
                    @endif
                </div>

                {{-- Action Bar --}}
                <div class="mt-2.5 pt-2 border-t border-gray-100 flex items-center justify-between">
                    <button type="button" onclick="navigator.clipboard.writeText('{{ $item->url }}'); alert('URL Foto berhasil disalin ke clipboard!');" class="text-[11px] text-purple-600 hover:text-purple-800 font-semibold flex items-center space-x-1" title="Salin URL gambar">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        <span>Salin</span>
                    </button>

                    <button type="button" wire:click="delete({{ $item->id }})" onclick="confirm('Hapus gambar ini dari Galeri?') || event.stopImmediatePropagation()" class="text-[11px] text-red-500 hover:text-red-700" title="Hapus foto">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
        {{ $galleries->links() }}
    </div>
    @endif

    {{-- Upload Modal --}}
    @if($showUploadModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-purple-100" @click.away="$wire.closeUploadModal()">
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-100">
                <h3 class="font-bold text-gray-800 text-base flex items-center space-x-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Unggah Foto ke Galeri</span>
                </h3>
                <button wire:click="closeUploadModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="saveUpload" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih File Foto *</label>
                    <input type="file" wire:model="gambar_upload" accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 cursor-pointer border border-purple-200 rounded-lg p-1.5">
                    @error('gambar_upload') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                    @if($gambar_upload)
                    <div class="mt-2 text-center">
                        <img src="{{ $gambar_upload->temporaryUrl() }}" class="max-h-36 mx-auto rounded-lg shadow object-cover">
                    </div>
                    @endif
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Judul / Keterangan Foto</label>
                    <input type="text" wire:model="judul" placeholder="Contoh: Suasana Ruang Ujian PPG 2026" class="w-full text-xs px-3 py-2 border border-purple-200 rounded-lg focus:ring-purple-500 focus:border-purple-500 outline-none">
                    @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kategori</label>
                    <input type="text" wire:model="kategori" placeholder="Contoh: Kegiatan, Kampus, Arsip" class="w-full text-xs px-3 py-2 border border-purple-200 rounded-lg focus:ring-purple-500 focus:border-purple-500 outline-none">
                    @error('kategori') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex justify-end space-x-2 pt-3 border-t border-gray-100">
                    <button type="button" wire:click="closeUploadModal" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-lg">Batal</button>
                    <button type="submit" class="btn-primary text-xs px-5 py-2 font-semibold">
                        <span wire:loading.remove wire:target="saveUpload">Simpan Foto</span>
                        <span wire:loading wire:target="saveUpload">Mengunggah...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Lightbox Preview Modal --}}
    @if($selectedImage)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-black/80 backdrop-blur-md flex items-center justify-center p-4" wire:click.self="closePreview">
        <div class="bg-white rounded-2xl max-w-3xl w-full overflow-hidden shadow-2xl border border-white/10 animate-fade-in">
            <div class="relative bg-black flex items-center justify-center min-h-[300px] max-h-[65vh]">
                <img src="{{ $selectedImage->url }}" alt="{{ $selectedImage->judul }}" class="max-w-full max-h-[65vh] object-contain">
                <button wire:click="closePreview" class="absolute top-3 right-3 p-2 rounded-full bg-black/60 text-white hover:bg-black/90">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-5 bg-white">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div>
                        <h3 class="font-bold text-gray-800 text-base">{{ $selectedImage->judul ?: 'Tanpa Judul' }}</h3>
                        <div class="flex items-center space-x-3 text-xs text-gray-500 mt-1">
                            <span>Kategori: <strong class="text-purple-700">{{ $selectedImage->kategori }}</strong></span>
                            <span>•</span>
                            <span>Sumber: <strong>{{ $selectedImage->sumber === 'wordpress_import' ? 'WordPress Import' : 'Upload Manual' }}</strong></span>
                            <span>•</span>
                            <span>Ukuran: <strong>{{ $selectedImage->ukuran }}</strong></span>
                        </div>
                    </div>

                    <div class="flex items-center space-x-2">
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $selectedImage->url }}'); alert('URL Foto berhasil disalin!');" class="btn-primary text-xs px-4 py-2 flex items-center space-x-1.5 shadow">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            <span>Salin URL Gambar</span>
                        </button>
                        <a href="{{ $selectedImage->url }}" target="_blank" download class="p-2 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50" title="Buka gambar di tab baru">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
