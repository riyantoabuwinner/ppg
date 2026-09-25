<div>
    @if(session()->has('message'))
    <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-4 text-sm flex items-center space-x-2">
        <svg class="w-4 h-4 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>{{ session('message') }}</span>
    </div>
    @endif

    @if(session()->has('error'))
    <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-xl mb-4 text-sm flex items-center space-x-2">
        <svg class="w-4 h-4 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
        <div>
            <h3 class="font-bold text-gray-700 text-base">Manajemen Berita & Artikel</h3>
            <p class="text-xs text-gray-400">Kelola artikel publik dan impor arsip berita WordPress</p>
        </div>
        <div class="flex items-center space-x-2">
            <button wire:click="openImportModal" class="px-4 py-2 bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 rounded-lg text-xs font-semibold flex items-center space-x-1.5 transition">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                <span>Import WordPress (.xml)</span>
            </button>
            <button wire:click="openCreate" class="btn-primary text-xs px-4 py-2 font-semibold shadow flex items-center space-x-1">
                <span>+ Tulis Artikel Baru</span>
            </button>
        </div>
    </div>

    {{-- Import WordPress Modal --}}
    @if($showImportModal)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 overflow-y-auto backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 border border-purple-100" @click.away="$wire.closeImportModal()">
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-100">
                <h3 class="font-bold text-gray-800 text-base flex items-center space-x-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/></svg>
                    <span>Import Kumpulan Berita dari WordPress</span>
                </h3>
                <button wire:click="closeImportModal" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="bg-purple-50 border border-purple-100 rounded-xl p-3.5 mb-4 text-xs text-purple-800 leading-relaxed">
                <div class="font-bold flex items-center space-x-1.5 mb-1 text-purple-900">
                    <svg class="w-4 h-4 text-purple-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                    <span>Pengalihan Gambar Otomatis ke Galeri</span>
                </div>
                Unggah file <code>.xml</code> hasil ekspor WordPress (Tools &rarr; Export). Seluruh postingan berita akan masuk ke modul ini, dan <strong>seluruh foto/gambar akan otomatis dialihkan dan disimpan ke menu Galeri</strong> aplikasi.
            </div>

            @if($importSummary)
            <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4 text-xs">
                <div class="font-bold text-green-800 text-sm mb-2">🎉 Hasil Import WordPress Berhasil:</div>
                <ul class="space-y-1 text-green-700">
                    <li>• <strong>{{ $importSummary['articles_count'] }}</strong> artikel berhasil diimpor</li>
                    <li>• <strong>{{ $importSummary['galleries_count'] }}</strong> gambar berhasil dialihkan ke menu Galeri</li>
                    @if(!empty($importSummary['categories']))
                    <li>• Kategori terdeteksi: {{ implode(', ', $importSummary['categories']) }}</li>
                    @endif
                </ul>
                <div class="mt-3">
                    <a href="{{ route('admin.pengaturan.galeri') }}" class="text-xs text-purple-700 underline font-semibold">Lihat foto di Galeri &rarr;</a>
                </div>
            </div>
            @endif

            <form wire:submit.prevent="processImport" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Pilih File WordPress (.xml) *</label>
                    <input type="file" wire:model="xml_file" accept=".xml,text/xml,application/xml" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 cursor-pointer border border-purple-200 rounded-lg p-1.5">
                    @error('xml_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="bg-gray-50 border border-gray-200 rounded-xl p-3 space-y-2">
                    <label class="text-xs font-semibold text-gray-700 block">Metode Penyimpanan Gambar ke Galeri:</label>
                    <div class="flex items-start space-x-2">
                        <input type="checkbox" id="dl-img" wire:model="download_images" class="mt-0.5 rounded border-purple-300 text-purple-600 focus:ring-purple-500">
                        <label for="dl-img" class="text-xs text-gray-600 leading-snug cursor-pointer">
                            <strong>Simpan file fisik gambar ke Galeri lokal (Direkomendasikan)</strong>
                            <span class="block text-[11px] text-gray-400 mt-0.5">Seluruh foto/gambar akan otomatis diunduh dan disimpan ke penyimpanan Galeri lokal. Artikel dan konten berita langsung menautkan ke file lokal di server aplikasi (bukan tautan WordPress lagi).</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-2 pt-3 border-t border-gray-100">
                    <button type="button" wire:click="closeImportModal" class="px-4 py-2 text-xs font-semibold text-gray-600 hover:bg-gray-100 rounded-lg">Tutup</button>
                    <button type="submit" class="btn-primary text-xs px-5 py-2 font-semibold flex items-center space-x-2">
                        <span wire:loading.remove wire:target="processImport">Proses Import XML</span>
                        <span wire:loading wire:target="processImport" class="flex items-center space-x-1.5">
                            <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Sedang Memproses XML & Media...</span>
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Gallery Picker Modal --}}
    @if($showGalleryPicker)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4 overflow-y-auto backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-6 border border-purple-100" @click.away="$wire.closeGalleryPicker()">
            <div class="flex justify-between items-center mb-4 pb-3 border-b border-gray-100">
                <h3 class="font-bold text-gray-800 text-base flex items-center space-x-2">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span>Pilih Gambar dari Galeri Media</span>
                </h3>
                <button wire:click="closeGalleryPicker" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="mb-4">
                <input type="text" wire:model.live.debounce.300ms="gallerySearch" placeholder="Cari foto di Galeri..." class="w-full text-xs px-3 py-2 border border-purple-200 rounded-lg focus:ring-purple-500 focus:border-purple-500 outline-none">
            </div>

            <div class="grid grid-cols-3 sm:grid-cols-4 gap-3 max-h-80 overflow-y-auto p-1">
                @forelse($galleries as $gal)
                <div wire:click="selectImageFromGallery('{{ $gal->file_path }}')" class="border border-purple-100 rounded-xl overflow-hidden cursor-pointer group hover:border-purple-500 hover:shadow-md transition">
                    <div class="w-full h-24 bg-gray-100 overflow-hidden">
                        <img src="{{ $gal->url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-200">
                    </div>
                    <div class="p-1.5 text-[10px] truncate text-gray-700 font-medium text-center">
                        {{ $gal->judul ?: 'Foto' }}
                    </div>
                </div>
                @empty
                <div class="col-span-4 text-center text-gray-400 py-8 text-xs">
                    Tidak ada gambar ditemukan di Galeri.
                </div>
                @endforelse
            </div>

            <div class="mt-4 pt-3 border-t border-gray-100 flex justify-end">
                <button wire:click="closeGalleryPicker" class="px-4 py-2 text-xs text-gray-600 hover:bg-gray-100 rounded-lg">Tutup</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Form Create/Edit Article Modal --}}
    @if($showForm)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-start justify-center p-4 overflow-y-auto backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl my-8 p-6 border border-purple-100">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-gray-100">
                <h3 class="font-bold text-gray-800 text-lg">{{ $editId ? 'Edit Artikel' : 'Tulis Artikel Baru' }}</h3>
                <button wire:click="$set('showForm', false)" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="text-xs font-semibold text-gray-600">Judul Artikel *</label>
                    <input type="text" wire:model="judul" class="w-full border mt-1 px-3 py-2.5 rounded-lg text-sm" placeholder="Tulis judul artikel yang menarik...">
                    @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-600">Kategori</label>
                        <select wire:model="kategori" class="w-full border mt-1 px-3 py-2.5 rounded-lg text-sm">
                            <option value="Berita">Berita</option>
                            <option value="Kegiatan">Kegiatan</option>
                            <option value="Akademik">Akademik</option>
                            <option value="Pengumuman">Pengumuman</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600">Status</label>
                        <select wire:model="status" class="w-full border mt-1 px-3 py-2.5 rounded-lg text-sm">
                            <option value="draft">Draft (Belum Dipublikasikan)</option>
                            <option value="published">Published (Langsung Tampil)</option>
                        </select>
                    </div>
                </div>

                {{-- Thumbnail Picker (Upload vs Gallery) --}}
                <div>
                    <label class="text-xs font-semibold text-gray-600 block mb-1">Gambar Thumbnail</label>
                    <div class="flex items-center space-x-3 mb-2">
                        <input type="file" wire:model="gambar_upload" class="text-xs text-gray-500 file:mr-3 file:py-1.5 file:px-4 file:rounded-lg file:border-0 file:font-semibold file:bg-purple-50 file:text-purple-700">
                        <span class="text-xs text-gray-400">atau</span>
                        <button type="button" wire:click="openGalleryPicker" class="px-3 py-1.5 bg-purple-100 hover:bg-purple-200 text-purple-800 rounded-lg text-xs font-semibold flex items-center space-x-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span>Pilih dari Galeri</span>
                        </button>
                    </div>
                    @error('gambar_upload') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                    @if($selected_gallery_image)
                    <div class="mt-2 flex items-center space-x-3 bg-purple-50/50 p-2 rounded-lg border border-purple-100">
                        <img src="{{ str_starts_with($selected_gallery_image, 'http') ? $selected_gallery_image : asset('storage/' . $selected_gallery_image) }}" class="w-14 h-14 rounded object-cover">
                        <div class="text-xs text-gray-600 flex-1 truncate">
                            <span class="font-bold text-purple-700">Thumbnail dari Galeri:</span>
                            <div class="truncate text-[11px] text-gray-400">{{ $selected_gallery_image }}</div>
                        </div>
                        <button type="button" wire:click="removeSelectedImage" class="text-xs text-red-500 hover:text-red-700 font-semibold">Hapus</button>
                    </div>
                    @endif
                </div>

                <div>
                    <label class="text-xs font-semibold text-gray-600">Ringkasan / Excerpt</label>
                    <textarea wire:model="excerpt" rows="2" class="w-full border mt-1 px-3 py-2 rounded-lg text-sm" placeholder="Ringkasan singkat artikel (opsional, akan otomatis dibuat jika kosong)"></textarea>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600">Konten Artikel *</label>
                    <textarea id="konten-editor" wire:model="konten" rows="10" class="w-full border mt-1 px-3 py-2 rounded-lg text-sm font-mono" placeholder="Tulis konten artikel di sini..."></textarea>
                    @error('konten') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-400 mt-1">Mendukung format tag HTML standar (paragraf, heading, gambar, dll).</p>
                </div>
            </div>
            <div class="mt-6 flex justify-end space-x-3 pt-3 border-t border-gray-100">
                <button wire:click="$set('showForm', false)" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm">Batal</button>
                <button wire:click="save" class="btn-primary px-6 py-2 text-sm font-semibold">Simpan Artikel</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Filter, Search, and Bulk Action Controls --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-4">
        <div class="flex flex-1 items-center space-x-2">
            <div class="relative flex-1 max-w-md">
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari judul artikel..." class="w-full border border-purple-200 rounded-lg pl-9 pr-3 py-2 text-xs focus:ring-purple-500 focus:border-purple-500 outline-none">
                <svg class="w-4 h-4 text-purple-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <select wire:model.live="filterStatus" class="border border-purple-200 rounded-lg px-3 py-2 text-xs focus:ring-purple-500 focus:border-purple-500 outline-none bg-white">
                <option value="">Semua Status</option>
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
        </div>

        {{-- Bulk Action Dropdown Control --}}
        <div class="flex items-center space-x-2">
            <select wire:model="bulkAction" class="border border-purple-200 rounded-lg px-3 py-2 text-xs focus:ring-purple-500 focus:border-purple-500 outline-none bg-white">
                <option value="">-- Aksi Masal --</option>
                <option value="delete">🗑️ Hapus Masal (Delete)</option>
                <option value="publish">✅ Publikasikan Masal</option>
                <option value="draft">📝 Jadikan Draft Masal</option>
            </select>
            <button wire:click="executeBulkAction" 
                    @if($bulkAction === 'delete') onclick="return confirm('PERINGATAN: Apakah Anda yakin ingin menghapus masal ' + @js(count($selectedArticles)) + ' artikel terpilih?') || event.stopImmediatePropagation()" @endif
                    class="px-4 py-2 bg-purple-900 hover:bg-purple-800 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg text-xs font-semibold shadow-sm transition flex items-center space-x-1.5"
                    @if(empty($selectedArticles)) disabled title="Centang minimal satu artikel terlebih dahulu" @endif>
                <span>Terapkan</span>
                @if(count($selectedArticles) > 0)
                <span class="bg-purple-700 text-white rounded-full px-1.5 py-0.2 text-[10px]">{{ count($selectedArticles) }}</span>
                @endif
            </button>
        </div>
    </div>

    {{-- Active Bulk Selection Banner --}}
    @if(count($selectedArticles) > 0)
    <div class="bg-gradient-to-r from-purple-900 via-purple-800 to-indigo-900 text-white rounded-xl p-3.5 sm:p-4 mb-4 shadow-md flex flex-col sm:flex-row sm:items-center justify-between gap-3 border border-purple-700/50">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            </div>
            <div>
                <div class="font-bold text-xs sm:text-sm flex items-center space-x-2">
                    <span class="bg-white/20 px-2 py-0.5 rounded text-[11px] font-mono">{{ count($selectedArticles) }} terpilih</span>
                    <span>Artikel siap diproses masal</span>
                </div>
                <div class="text-[11px] text-purple-200 mt-0.5 flex items-center space-x-2">
                    @if(count($selectedArticles) < $articles->total())
                    <button wire:click="selectAllMatching" class="underline hover:text-white font-semibold">
                        Pilih semua {{ $articles->total() }} artikel (semua halaman)
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
            {{-- Bulk Publish --}}
            <button wire:click="bulkPublish" wire:loading.attr="disabled" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm flex items-center space-x-1.5 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Publikasikan</span>
            </button>

            {{-- Bulk Draft --}}
            <button wire:click="bulkDraft" wire:loading.attr="disabled" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-400 active:bg-amber-600 text-white text-xs font-semibold rounded-lg shadow-sm flex items-center space-x-1.5 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span>Draft</span>
            </button>

            {{-- Bulk Delete (Permintaan Khusus User) --}}
            <button wire:click="bulkDelete" onclick="confirm('PERINGATAN: Apakah Anda yakin ingin MENGHAPUS PERMANEN ' + {{ count($selectedArticles) }} + ' artikel terpilih?') || event.stopImmediatePropagation()" wire:loading.attr="disabled" class="px-3 py-1.5 bg-red-600 hover:bg-red-500 active:bg-red-700 text-white text-xs font-semibold rounded-lg shadow-sm flex items-center space-x-1.5 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>Hapus Masal</span>
            </button>

            {{-- Deselect All --}}
            <button wire:click="deselectAll" class="px-3 py-1.5 bg-white/20 hover:bg-white/30 text-white text-xs font-medium rounded-lg transition">
                Batal
            </button>
        </div>
    </div>
    @endif

    {{-- Articles Table --}}
    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-purple-50/50">
                <tr>
                    <th class="w-10 px-4 py-3 text-center">
                        <input type="checkbox" wire:model.live="selectAll" title="Pilih Semua di Halaman Ini" class="rounded border-purple-300 text-purple-600 focus:ring-purple-500 cursor-pointer">
                    </th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Judul / Penulis</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Kategori</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tanggal</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 bg-white">
                @forelse($articles as $article)
                @php $isSelected = in_array((string)$article->id, array_map('strval', $selectedArticles)); @endphp
                <tr class="hover:bg-purple-50/30 transition {{ $isSelected ? 'bg-purple-50/60 border-l-4 border-l-purple-600' : '' }}">
                    <td class="w-10 px-4 py-3 text-center">
                        <input type="checkbox" wire:model.live="selectedArticles" value="{{ (string)$article->id }}" class="rounded border-purple-300 text-purple-600 focus:ring-purple-500 cursor-pointer">
                    </td>
                    <td class="px-5 py-3">
                        <div class="flex items-center space-x-3">
                            @if($article->gambar_url)
                            <img src="{{ $article->gambar_url }}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                            @elseif($article->gambar)
                            <img src="{{ asset('storage/' . $article->gambar) }}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0">
                            @else
                            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center text-purple-400 flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            @endif
                            <div>
                                <p class="font-semibold text-sm text-gray-800 line-clamp-1">{{ $article->judul }}</p>
                                <p class="text-xs text-gray-400">oleh {{ $article->author->name ?? '-' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 whitespace-nowrap text-xs text-gray-600">{{ $article->kategori }}</td>
                    <td class="px-5 py-3 whitespace-nowrap">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $article->status === 'published' ? 'badge-verified' : 'badge-draft' }}">
                            {{ $article->status === 'published' ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 whitespace-nowrap text-xs text-gray-500">
                        {{ $article->published_at?->format('d/m/Y') ?? $article->created_at->format('d/m/Y') }}
                    </td>
                    <td class="px-5 py-3 whitespace-nowrap text-right text-xs font-medium space-x-2">
                        <button wire:click="edit({{ $article->id }})" class="text-purple-600 hover:text-purple-900">Edit</button>
                        @if($article->status === 'draft')
                            <button wire:click="publish({{ $article->id }})" class="text-green-600 hover:text-green-900">Publikasikan</button>
                        @else
                            <button wire:click="unpublish({{ $article->id }})" class="text-amber-600 hover:text-amber-900">Tarik</button>
                        @endif
                        <a href="{{ route('public.berita.show', $article->slug) }}" target="_blank" class="text-blue-600 hover:text-blue-900">Preview</a>
                        <button wire:click="delete({{ $article->id }})" onclick="confirm('Hapus artikel ini?') || event.stopImmediatePropagation()" class="text-red-500 hover:text-red-700">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada artikel. Klik "Import WordPress (.xml)" untuk mengimpor kumpulan berita.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-3 border-t bg-gray-50">{{ $articles->links() }}</div>
    </div>
</div>
