<div>
    @if(session()->has('message'))
    <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-4 text-sm">{{ session('message') }}</div>
    @endif

    <div class="flex justify-between items-center mb-5">
        <h3 class="font-bold text-gray-700">Daftar Pengumuman</h3>
        <button wire:click="openCreate" class="btn-primary text-sm px-4 py-2">+ Tambah Pengumuman</button>
    </div>

    @if($showForm)
    <div class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6">
            <h3 class="font-bold text-gray-800 text-lg mb-4">{{ $editId ? 'Edit Pengumuman' : 'Tambah Pengumuman' }}</h3>
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-semibold text-gray-600">Judul *</label>
                    <input type="text" wire:model="judul" class="w-full border mt-1 px-3 py-2 rounded-lg text-sm" placeholder="Judul pengumuman">
                    @error('judul') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600">Isi Pengumuman *</label>
                    <textarea wire:model="isi" rows="4" class="w-full border mt-1 px-3 py-2 rounded-lg text-sm" placeholder="Tulis isi pengumuman..."></textarea>
                    @error('isi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600">Tipe Pengumuman</label>
                    <select wire:model="tipe" class="w-full border mt-1 px-3 py-2 rounded-lg text-sm">
                        <option value="info">ℹ️ Informasi</option>
                        <option value="penting">⭐ Penting</option>
                        <option value="peringatan">⚠️ Peringatan</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="text-xs font-semibold text-gray-600">Tampil Mulai</label>
                        <input type="date" wire:model="tanggal_mulai" class="w-full border mt-1 px-3 py-2 rounded-lg text-sm">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600">Berakhir Pada</label>
                        <input type="date" wire:model="tanggal_selesai" class="w-full border mt-1 px-3 py-2 rounded-lg text-sm">
                    </div>
                </div>
                <label class="flex items-center space-x-2 cursor-pointer">
                    <input type="checkbox" wire:model="is_active" class="rounded">
                    <span class="text-sm font-medium text-gray-700">Aktifkan pengumuman ini</span>
                </label>
            </div>
            <div class="mt-5 flex justify-end space-x-3">
                <button wire:click="$set('showForm', false)" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm">Batal</button>
                <button wire:click="save" class="btn-primary px-5 py-2 text-sm">Simpan</button>
            </div>
        </div>
    </div>
    @endif

    <div class="card overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Judul / Isi</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Tipe</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Periode</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Status</th>
                    <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 bg-white">
                @forelse($pengumumans as $p)
                <tr class="hover:bg-purple-50/30 transition">
                    <td class="px-5 py-3">
                        <p class="font-semibold text-sm text-gray-800">{{ $p->judul }}</p>
                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ Str::limit($p->isi, 80) }}</p>
                    </td>
                    <td class="px-5 py-3 whitespace-nowrap">
                        <span class="text-xs px-2 py-0.5 rounded-full
                            @if($p->tipe === 'penting') bg-amber-100 text-amber-800
                            @elseif($p->tipe === 'peringatan') bg-red-100 text-red-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ ucfirst($p->tipe) }}
                        </span>
                    </td>
                    <td class="px-5 py-3 whitespace-nowrap text-xs text-gray-500">
                        {{ $p->tanggal_mulai?->format('d/m/Y') ?? '—' }} s/d {{ $p->tanggal_selesai?->format('d/m/Y') ?? '∞' }}
                    </td>
                    <td class="px-5 py-3 whitespace-nowrap">
                        <span class="text-xs px-2 py-0.5 rounded-full {{ $p->is_active ? 'badge-verified' : 'badge-draft' }}">
                            {{ $p->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 whitespace-nowrap text-right text-xs font-medium space-x-2">
                        <button wire:click="edit({{ $p->id }})" class="text-purple-600 hover:text-purple-900">Edit</button>
                        <button wire:click="toggleActive({{ $p->id }})" class="{{ $p->is_active ? 'text-amber-600' : 'text-green-600' }} hover:opacity-70">{{ $p->is_active ? 'Nonaktif' : 'Aktifkan' }}</button>
                        <button wire:click="delete({{ $p->id }})" onclick="confirm('Hapus pengumuman ini?') || event.stopImmediatePropagation()" class="text-red-500 hover:text-red-700">Hapus</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada pengumuman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
