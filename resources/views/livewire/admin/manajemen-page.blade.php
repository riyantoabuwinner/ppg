<div>
    @if(session()->has('message'))
    <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-4 text-sm">{{ session('message') }}</div>
    @endif

    <div class="flex justify-between items-center mb-5">
        <h3 class="font-bold text-gray-700">Daftar Halaman Statis</h3>
        <button wire:click="openCreate" class="btn-primary text-sm px-4 py-2">+ Buat Halaman Baru</button>
    </div>

    {{-- Modal Form --}}
    @if($showForm)
    <div class="fixed inset-0 bg-black/60 z-50 flex items-start justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl my-8 p-6">
            <h3 class="font-bold text-gray-800 text-lg mb-5">{{ $editId ? 'Edit Halaman' : 'Buat Halaman Statis Baru' }}</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="text-xs font-semibold text-gray-600">Judul Halaman <span class="text-red-400">*</span></label>
                    <input type="text" wire:model="judul" class="w-full border mt-1 px-3 py-2.5 rounded-xl text-sm" placeholder="Contoh: Tentang PPG, Syarat dan Ketentuan, Kontak">
                    @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600">Status</label>
                    <select wire:model="status" class="w-full border mt-1 px-3 py-2.5 rounded-xl text-sm">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="text-xs font-semibold text-gray-600">Meta Description (untuk SEO)</label>
                <input type="text" wire:model="meta_description" class="w-full border mt-1 px-3 py-2 rounded-xl text-sm" placeholder="Deskripsi singkat halaman ini untuk mesin pencari...">
            </div>

            <div class="mb-4">
                <div class="flex items-center justify-between mb-2">
                    <label class="text-xs font-semibold text-gray-600">Konten Halaman <span class="text-red-400">*</span></label>
                    <span class="text-xs bg-purple-50 text-purple-700 px-2 py-0.5 rounded-full font-medium">Mendukung HTML</span>
                </div>
                {{-- Toolbar HTML sederhana --}}
                <div class="border-b border-gray-100 bg-gray-50 rounded-t-xl px-3 py-2 flex flex-wrap gap-2">
                    @foreach([
                        ['tag'=>'<h2>Heading 2</h2>', 'label'=>'H2'],
                        ['tag'=>'<h3>Heading 3</h3>', 'label'=>'H3'],
                        ['tag'=>'<p>Paragraf</p>', 'label'=>'P'],
                        ['tag'=>'<strong>Tebal</strong>', 'label'=>'B'],
                        ['tag'=>'<em>Miring</em>', 'label'=>'I'],
                        ['tag'=>'<ul><li>Item</li></ul>', 'label'=>'UL'],
                        ['tag'=>'<ol><li>Item</li></ol>', 'label'=>'OL'],
                        ['tag'=>'<a href="#">Tautan</a>', 'label'=>'Link'],
                        ['tag'=>'<hr>', 'label'=>'---'],
                    ] as $btn)
                    <button type="button" onclick="insertTag('{{ addslashes($btn['tag']) }}')" class="text-xs px-2 py-1 bg-white border border-gray-200 rounded-lg hover:bg-purple-50 hover:border-purple-300 text-gray-600 font-mono transition">{{ $btn['label'] }}</button>
                    @endforeach
                </div>
                <textarea id="page-konten" wire:model="konten" rows="14" class="w-full border border-t-0 rounded-b-xl px-3 py-3 text-sm font-mono bg-gray-50" placeholder="Tulis konten halaman di sini... Anda bisa menggunakan HTML penuh."></textarea>
                @error('konten') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="text-xs font-semibold text-gray-600">Urutan di Menu</label>
                    <input type="number" wire:model="urutan" class="w-full border mt-1 px-3 py-2 rounded-xl text-sm">
                </div>
                <div class="flex items-end pb-2">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" wire:model="tampil_di_menu" class="rounded w-4 h-4" style="accent-color:#7C3AED">
                        <div>
                            <span class="text-sm font-medium text-gray-700">Tampilkan di Navigasi Publik</span>
                            <p class="text-xs text-gray-400">Halaman akan muncul di menu navbar situs</p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button wire:click="$set('showForm', false)" class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm hover:bg-gray-200 transition">Batal</button>
                <button wire:click="save" class="btn-primary px-6 py-2.5 text-sm">Simpan Halaman</button>
            </div>
        </div>
    </div>
    @endif

    {{-- Search --}}
    <input type="text" wire:model.live="search" placeholder="Cari judul halaman..." class="w-full max-w-sm border rounded-xl px-3 py-2 text-sm mb-4">

    {{-- Table --}}
    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Judul / Slug</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Menu Publik</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Penulis</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 bg-white">
                @forelse($pages as $page)
                <tr class="hover:bg-purple-50/30 transition">
                    <td class="px-5 py-3">
                        <p class="font-semibold text-sm text-gray-800">{{ $page->judul }}</p>
                        <p class="text-xs text-gray-400 font-mono">{{ $page->slug }}</p>
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $page->status === 'published' ? 'badge-verified' : 'badge-draft' }}">
                            {{ $page->status === 'published' ? 'Published' : 'Draft' }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $page->tampil_di_menu ? 'badge-verified' : 'badge-draft' }}">
                            {{ $page->tampil_di_menu ? 'Ya' : 'Tidak' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-xs text-gray-500">{{ $page->author->name ?? '-' }}</td>
                    <td class="px-5 py-3 whitespace-nowrap text-right text-xs font-medium space-x-2">
                        <button wire:click="edit({{ $page->id }})" class="text-purple-600 hover:text-purple-900">Edit</button>
                        <button wire:click="toggleStatus({{ $page->id }})" class="{{ $page->status === 'published' ? 'text-amber-600' : 'text-green-600' }} hover:opacity-70">
                            {{ $page->status === 'published' ? 'Tarik' : 'Publikasikan' }}
                        </button>
                        <a href="{{ route('public.page', $page->slug) }}" target="_blank" class="text-blue-600 hover:text-blue-900">Preview</a>
                        <button wire:click="delete({{ $page->id }})" onclick="confirm('Hapus halaman ini?') || event.stopImmediatePropagation()" class="text-red-500 hover:text-red-700">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada halaman statis.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-5 py-3 border-t bg-gray-50">{{ $pages->links() }}</div>
    </div>

    <script>
    function insertTag(tag) {
        const ta = document.getElementById('page-konten');
        if (!ta) return;
        const start = ta.selectionStart;
        const end = ta.selectionEnd;
        ta.value = ta.value.substring(0, start) + tag + ta.value.substring(end);
        ta.selectionStart = ta.selectionEnd = start + tag.length;
        ta.dispatchEvent(new Event('input'));
    }
    </script>
</div>
