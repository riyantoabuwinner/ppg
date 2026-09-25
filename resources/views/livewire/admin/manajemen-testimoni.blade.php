<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Testimoni</h2>
        <button wire:click="openCreate" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700">
            + Tambah Testimoni
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if ($showForm)
        <div class="bg-white p-6 rounded shadow mb-6">
            <h3 class="text-lg font-bold mb-4">{{ $editId ? 'Edit Testimoni' : 'Tambah Testimoni' }}</h3>
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Nama</label>
                        <input type="text" wire:model="name" class="w-full border rounded px-3 py-2" required>
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Subtitle / Jabatan</label>
                        <input type="text" wire:model="subtitle" class="w-full border rounded px-3 py-2">
                        @error('subtitle') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Tipe Testimoni</label>
                        <select wire:model.live="type" class="w-full border rounded px-3 py-2" required>
                            <option value="text">Teks</option>
                            <option value="video">Video</option>
                        </select>
                        @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Rating (1-5)</label>
                        <input type="number" wire:model="rating" min="1" max="5" class="w-full border rounded px-3 py-2" required>
                        @error('rating') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                @if($type === 'text')
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Isi Testimoni</label>
                    <textarea wire:model="content" class="w-full border rounded px-3 py-2" rows="3" required></textarea>
                    @error('content') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                @else
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">URL Media / Video (Contoh: https://youtube.com/...)</label>
                    <input type="text" wire:model="media_url" class="w-full border rounded px-3 py-2" required>
                    @error('media_url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Foto / Avatar (Opsional)</label>
                        <input type="file" wire:model="avatar" class="w-full border rounded px-3 py-2">
                        @if ($oldAvatar)
                            <img src="{{ asset('storage/'.$oldAvatar) }}" class="mt-2 h-16 w-16 object-cover rounded-full">
                        @endif
                        @error('avatar') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Urutan (Sort Order)</label>
                        <input type="number" wire:model="sort_order" class="w-full border rounded px-3 py-2">
                    </div>
                </div>
                
                <div class="mb-4">
                    <label class="flex items-center">
                        <input type="checkbox" wire:model="is_active" class="mr-2">
                        <span class="text-gray-700">Aktif (Tampilkan di Beranda)</span>
                    </label>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" wire:click="$set('showForm', false)" class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600">Batal</button>
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700">Simpan</button>
                </div>
            </form>
        </div>
    @endif

    <div class="bg-white rounded shadow overflow-hidden">
        <div class="p-4 border-b">
            <input type="text" wire:model.live="search" placeholder="Cari nama..." class="border rounded px-3 py-2 w-full max-w-sm">
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="p-4">Avatar</th>
                    <th class="p-4">Nama</th>
                    <th class="p-4">Tipe</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($testimonials as $t)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4">
                            @if($t->avatar)
                                <img src="{{ asset('storage/'.$t->avatar) }}" class="h-10 w-10 object-cover rounded-full">
                            @else
                                <div class="h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-500">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="font-bold">{{ $t->name }}</div>
                            <div class="text-xs text-gray-500">{{ $t->subtitle }}</div>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded text-xs {{ $t->type == 'video' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}">
                                {{ ucfirst($t->type) }}
                            </span>
                        </td>
                        <td class="p-4">
                            <button wire:click="toggleActive({{ $t->id }})" class="px-2 py-1 rounded text-xs {{ $t->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $t->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </td>
                        <td class="p-4 space-x-2">
                            <button wire:click="edit({{ $t->id }})" class="text-indigo-600 hover:text-indigo-800">Edit</button>
                            <button onclick="confirm('Yakin ingin menghapus?') || event.stopImmediatePropagation()" wire:click="delete({{ $t->id }})" class="text-red-600 hover:text-red-800">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">Tidak ada data testimoni.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $testimonials->links() }}
        </div>
    </div>
</div>
