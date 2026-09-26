<div>
    @if(session()->has('message'))
    <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-4 text-sm flex items-center space-x-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>{{ session('message') }}</span>
    </div>
    @endif

    {{-- Header + Tambah --}}
    <div class="flex justify-between items-center mb-5">
        <h3 class="font-bold text-gray-700">Daftar Slider ({{ count($sliders) }} item)</h3>
        <button wire:click="openCreate" class="btn-primary text-sm px-4 py-2">+ Tambah Slide</button>
    </div>

    {{-- Form Modal --}}
    @if($showForm)
    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6">
            <h3 class="font-bold text-gray-800 text-lg mb-4">{{ $editId ? 'Edit Slide' : 'Tambah Slide Baru' }}</h3>
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-semibold text-gray-600">Judul Slide</label>
                    <input type="text" wire:model="judul" class="w-full border mt-1 px-3 py-2 rounded-lg text-sm" placeholder="Contoh: Selamat Datang Mahasiswa PPG 2025">
                    @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600">Sub-Judul</label>
                    <input type="text" wire:model="subjudul" class="w-full border mt-1 px-3 py-2 rounded-lg text-sm" placeholder="Kalimat pendukung / deskripsi singkat">
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600">Gambar Slide {{ $editId ? '(kosongkan jika tidak diganti)' : '*' }}</label>
                    <input type="file" wire:model="gambar_upload" class="w-full text-sm mt-1 text-gray-500 file:mr-3 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    @error('gambar_upload') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-semibold text-gray-600">Teks Tombol</label>
                        <input type="text" wire:model="teks_tombol" class="w-full border mt-1 px-3 py-2 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600">Link Tombol (URL)</label>
                        <input type="text" wire:model="link_tombol" class="w-full border mt-1 px-3 py-2 rounded-lg text-sm" placeholder="https://...">
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-3">
                    <div>
                        <label class="text-xs font-semibold text-gray-600">Urutan Tampil</label>
                        <input type="number" wire:model="urutan" class="w-full border mt-1 px-3 py-2 rounded-lg text-sm">
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model="is_active" class="rounded">
                            <span class="text-sm font-medium text-gray-700">Aktif / Tampilkan</span>
                        </label>
                    </div>
                    <div class="flex items-end pb-2">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" wire:model="tampilkan_tombol" class="rounded">
                            <span class="text-sm font-medium text-gray-700">Tampilkan Tombol</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="mt-5 flex justify-end space-x-3">
                <button wire:click="$set('showForm', false)" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm hover:bg-gray-200 transition">Batal</button>
                <button wire:click="save" class="btn-primary px-5 py-2 text-sm">Simpan</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Slider Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @forelse($sliders as $slider)
        <div class="card overflow-hidden group">
            <div class="relative">
                @if($slider->gambar)
                <img src="{{ asset('storage/' . $slider->gambar) }}" alt="{{ $slider->judul }}" class="w-full h-36 object-cover">
                @else
                <div class="w-full h-36 bg-gradient-to-br from-purple-200 to-purple-400 flex items-center justify-center text-purple-700 font-bold text-sm">Tanpa Gambar</div>
                @endif
                <div class="absolute top-2 right-2">
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $slider->is_active ? 'badge-verified' : 'badge-draft' }}">
                        {{ $slider->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </div>
            </div>
            <div class="p-4">
                <p class="font-semibold text-gray-800 text-sm truncate">{{ $slider->judul }}</p>
                <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $slider->subjudul }}</p>
                <p class="text-xs text-purple-500 mt-1">Urutan: {{ $slider->urutan }}</p>
            </div>
            <div class="px-4 pb-4 flex space-x-2">
                <button wire:click="edit({{ $slider->id }})" class="flex-1 text-xs py-1.5 bg-purple-50 text-purple-700 rounded-lg hover:bg-purple-100 transition font-medium">Edit</button>
                <button wire:click="toggleActive({{ $slider->id }})" class="flex-1 text-xs py-1.5 {{ $slider->is_active ? 'bg-amber-50 text-amber-700' : 'bg-green-50 text-green-700' }} rounded-lg hover:opacity-80 transition font-medium">
                    {{ $slider->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
                <button wire:click="delete({{ $slider->id }})" onclick="confirm('Yakin hapus slide ini?') || event.stopImmediatePropagation()" class="text-xs py-1.5 px-3 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition font-medium">Hapus</button>
            </div>
        </div>
        @empty
        <div class="col-span-3 card p-10 text-center text-gray-400">
            <p class="text-sm font-medium">Belum ada slide. Klik "+ Tambah Slide" untuk memulai.</p>
        </div>
        @endforelse
    </div>
</div>
