<div>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Manajemen Mitra Kerja</h2>
        <button wire:click="openCreate" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700">
            + Tambah Mitra
        </button>
    </div>

    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('message') }}
        </div>
    @endif

    @if ($showForm)
        <div class="bg-white p-6 rounded shadow mb-6">
            <h3 class="text-lg font-bold mb-4">{{ $editId ? 'Edit Mitra' : 'Tambah Mitra' }}</h3>
            <form wire:submit.prevent="save">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Nama Mitra</label>
                        <input type="text" wire:model="name" class="w-full border rounded px-3 py-2" required>
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">URL Web Mitra (Opsional)</label>
                        <input type="url" wire:model="url" class="w-full border rounded px-3 py-2">
                        @error('url') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Logo Mitra</label>
                        <input type="file" wire:model="logo" class="w-full border rounded px-3 py-2" {{ !$editId ? 'required' : '' }}>
                        @if ($oldLogo)
                            <img src="{{ asset('storage/'.$oldLogo) }}" class="mt-2 h-16 w-auto object-contain bg-gray-50 p-2 rounded">
                        @endif
                        @error('logo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Urutan Tampil (Sort Order)</label>
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
            <input type="text" wire:model.live="search" placeholder="Cari mitra..." class="border rounded px-3 py-2 w-full max-w-sm">
        </div>
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="p-4">Logo</th>
                    <th class="p-4">Nama Mitra</th>
                    <th class="p-4">Urutan</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($partners as $p)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4">
                            @if($p->logo)
                                <img src="{{ asset('storage/'.$p->logo) }}" class="h-10 w-auto object-contain bg-gray-50 p-1 rounded">
                            @else
                                <span class="text-gray-400 text-xs">No Logo</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <div class="font-bold">{{ $p->name }}</div>
                            @if($p->url)
                                <a href="{{ $p->url }}" target="_blank" class="text-xs text-blue-500 hover:underline">{{ $p->url }}</a>
                            @endif
                        </td>
                        <td class="p-4">{{ $p->sort_order }}</td>
                        <td class="p-4">
                            <button wire:click="toggleActive({{ $p->id }})" class="px-2 py-1 rounded text-xs {{ $p->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                            </button>
                        </td>
                        <td class="p-4 space-x-2">
                            <button wire:click="edit({{ $p->id }})" class="text-indigo-600 hover:text-indigo-800">Edit</button>
                            <button onclick="confirm('Yakin ingin menghapus?') || event.stopImmediatePropagation()" wire:click="delete({{ $p->id }})" class="text-red-600 hover:text-red-800">Hapus</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center text-gray-500">Tidak ada data mitra.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4">
            {{ $partners->links() }}
        </div>
    </div>
</div>
