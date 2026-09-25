<div>
    {{-- Flash Message --}}
    @if(session()->has('message'))
    <div class="bg-emerald-50 border border-emerald-300 text-emerald-800 px-4 py-3 rounded-xl mb-6 text-sm flex items-center space-x-2 shadow-sm animate-fade-in">
        <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span class="font-medium">{{ session('message') }}</span>
    </div>
    @endif

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total --}}
        <div class="card p-4 flex items-center justify-between border-l-4 border-purple-600">
            <div>
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Periode</p>
                <p class="text-2xl font-extrabold text-gray-800 mt-1">{{ $totalCount }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
        </div>

        {{-- Sedang Dibuka --}}
        <div class="card p-4 flex items-center justify-between border-l-4 border-emerald-500">
            <div>
                <div class="flex items-center space-x-1.5">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
                    <p class="text-xs font-semibold text-emerald-700 uppercase tracking-wider">Sedang Dibuka</p>
                </div>
                <p class="text-2xl font-extrabold text-emerald-800 mt-1">{{ $openCount }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        {{-- Akan Datang --}}
        <div class="card p-4 flex items-center justify-between border-l-4 border-amber-500">
            <div>
                <p class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Akan Datang</p>
                <p class="text-2xl font-extrabold text-amber-800 mt-1">{{ $upcomingCount }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>

        {{-- Ditutup --}}
        <div class="card p-4 flex items-center justify-between border-l-4 border-rose-500">
            <div>
                <p class="text-xs font-semibold text-rose-700 uppercase tracking-wider">Ditutup / Berakhir</p>
                <p class="text-2xl font-extrabold text-rose-800 mt-1">{{ $closedCount }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center text-rose-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
    </div>

    {{-- Toolbar --}}
    <div class="card p-4 mb-6">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto">
                {{-- Search --}}
                <div class="relative w-full sm:w-64">
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari periode..." class="w-full pl-9 pr-3 py-2 text-xs border rounded-xl focus:border-purple-500">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>

                {{-- Status Filter --}}
                <select wire:model.live="statusFilter" class="w-full sm:w-auto px-3 py-2 text-xs border rounded-xl focus:border-purple-500">
                    <option value="">Semua Status</option>
                    <option value="open">Sedang Dibuka</option>
                    <option value="upcoming">Akan Datang</option>
                    <option value="closed">Ditutup / Berakhir</option>
                    <option value="inactive">Nonaktif Manual</option>
                </select>
            </div>

            {{-- Tombol Tambah --}}
            <button wire:click="openCreateModal" class="btn-primary w-full sm:w-auto px-4 py-2 text-xs flex items-center justify-center space-x-1.5 shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                <span>Tambah Periode Baru</span>
            </button>
        </div>
    </div>

    {{-- Tabel Periode --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-gray-600">
                <thead class="bg-purple-50/70 border-b border-purple-100 text-purple-900 font-bold uppercase tracking-wider text-[11px]">
                    <tr>
                        <th class="px-5 py-3.5">Nama Periode / Gelombang</th>
                        <th class="px-4 py-3.5">Tahun / Semester</th>
                        <th class="px-4 py-3.5">Jadwal Buka & Tutup</th>
                        <th class="px-4 py-3.5 text-center">Status Waktu</th>
                        <th class="px-4 py-3.5 text-center">Aktif</th>
                        <th class="px-4 py-3.5 text-center">Peserta</th>
                        <th class="px-5 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-50">
                    @forelse($periods as $period)
                    <tr class="hover:bg-purple-50/30 transition">
                        {{-- Nama & Deskripsi --}}
                        <td class="px-5 py-4">
                            <p class="font-bold text-gray-800 text-sm leading-tight">{{ $period->nama }}</p>
                            @if($period->deskripsi)
                            <p class="text-gray-400 text-[11px] mt-0.5 line-clamp-1">{{ $period->deskripsi }}</p>
                            @endif
                        </td>

                        {{-- Tahun & Semester --}}
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="font-semibold text-gray-700">{{ $period->tahun_akademik ?? '-' }}</span>
                            @if($period->semester)
                            <span class="text-gray-400 text-[11px]">({{ $period->semester }})</span>
                            @endif
                        </td>

                        {{-- Tanggal Buka & Tutup --}}
                        <td class="px-4 py-4 whitespace-nowrap">
                            <div class="space-y-1">
                                <div class="flex items-center space-x-1.5 text-emerald-700">
                                    <svg class="w-3.5 h-3.5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="font-medium">Mulai: {{ $period->tanggal_mulai ? $period->tanggal_mulai->translatedFormat('d M Y H:i') : '-' }} WIB</span>
                                </div>
                                <div class="flex items-center space-x-1.5 text-rose-700">
                                    <svg class="w-3.5 h-3.5 text-rose-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <span class="font-medium">Selesai: {{ $period->tanggal_selesai ? $period->tanggal_selesai->translatedFormat('d M Y H:i') : '-' }} WIB</span>
                                </div>
                            </div>
                        </td>

                        {{-- Status Otomatis --}}
                        <td class="px-4 py-4 text-center whitespace-nowrap">
                            <span class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-full text-[11px] font-semibold {{ $period->status_badge_class }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $period->status_dot_class }}"></span>
                                <span>{{ $period->status_label }}</span>
                            </span>
                        </td>

                        {{-- Toggle Aktif --}}
                        <td class="px-4 py-4 text-center whitespace-nowrap">
                            <button wire:click="toggleActive({{ $period->id }})" 
                                    class="relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $period->is_active ? 'bg-purple-600' : 'bg-gray-300' }}"
                                    title="Klik untuk {{ $period->is_active ? 'menonaktifkan' : 'mengaktifkan' }}">
                                <span class="sr-only">Toggle Aktif</span>
                                <span class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $period->is_active ? 'translate-x-4' : 'translate-x-0' }}"></span>
                            </button>
                        </td>

                        {{-- Jumlah Mahasiswa --}}
                        <td class="px-4 py-4 text-center whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md font-semibold text-xs bg-purple-100 text-purple-800">
                                {{ $period->student_profiles_count ?? 0 }} mhs
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td class="px-5 py-4 text-right whitespace-nowrap">
                            <div class="inline-flex items-center space-x-1">
                                <button wire:click="openEditModal({{ $period->id }})" class="p-1.5 rounded-lg text-purple-600 hover:bg-purple-100 transition" title="Edit Periode">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </button>
                                <button wire:click="confirmDelete({{ $period->id }})" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-100 transition" title="Hapus Periode">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <p class="font-semibold text-sm text-gray-600">Belum ada periode lapor diri</p>
                            <p class="text-xs text-gray-400 mt-1">Klik tombol "Tambah Periode Baru" di atas untuk membuat jadwal buka/tutup lapor diri.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($periods->hasPages())
        <div class="px-5 py-4 border-t border-purple-50">
            {{ $periods->links() }}
        </div>
        @endif
    </div>

    {{-- MODAL TAMBAH / EDIT --}}
    @if($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-purple-100 animate-scale-up">
            {{-- Modal Header --}}
            <div class="px-6 py-4 border-b border-purple-100 flex items-center justify-between" style="background: linear-gradient(135deg, #4C1D95, #7C3AED);">
                <div class="flex items-center space-x-2 text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <h3 class="font-bold text-sm">{{ $isEdit ? 'Edit Periode Lapor Diri' : 'Tambah Periode Lapor Diri Baru' }}</h3>
                </div>
                <button wire:click="$set('showModal', false)" class="text-white/70 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Modal Body / Form --}}
            <form wire:submit.prevent="save" class="p-6 space-y-4">
                {{-- Nama Periode --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Nama Periode / Gelombang <span class="text-rose-500">*</span></label>
                    <input type="text" wire:model="nama" placeholder="Contoh: Gelombang 1 PPG Daljab 2025" class="w-full text-xs border rounded-xl px-3 py-2.5 focus:border-purple-500">
                    @error('nama') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Tahun Akademik & Semester --}}
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Tahun Akademik</label>
                        <input type="text" wire:model="tahun_akademik" placeholder="Contoh: 2025/2026" class="w-full text-xs border rounded-xl px-3 py-2.5 focus:border-purple-500">
                        @error('tahun_akademik') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Semester</label>
                        <select wire:model="semester" class="w-full text-xs border rounded-xl px-3 py-2.5 focus:border-purple-500">
                            <option value="Ganjil">Ganjil</option>
                            <option value="Genap">Genap</option>
                        </select>
                        @error('semester') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Tanggal Mulai & Tanggal Selesai (Buka & Tutup) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 p-3 bg-purple-50/50 rounded-xl border border-purple-100">
                    <div>
                        <label class="block text-xs font-semibold text-emerald-800 mb-1 flex items-center space-x-1">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Tanggal & Jam Buka <span class="text-rose-500">*</span></span>
                        </label>
                        <input type="datetime-local" wire:model="tanggal_mulai" class="w-full text-xs border rounded-xl px-3 py-2 bg-white focus:border-purple-500">
                        @error('tanggal_mulai') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-rose-800 mb-1 flex items-center space-x-1">
                            <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                            <span>Tanggal & Jam Tutup <span class="text-rose-500">*</span></span>
                        </label>
                        <input type="datetime-local" wire:model="tanggal_selesai" class="w-full text-xs border rounded-xl px-3 py-2 bg-white focus:border-purple-500">
                        @error('tanggal_selesai') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Keterangan / Deskripsi --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Keterangan / Catatan untuk Peserta</label>
                    <textarea wire:model="deskripsi" rows="2" placeholder="Catatan opsional untuk ditampilkan kepada mahasiswa..." class="w-full text-xs border rounded-xl px-3 py-2 focus:border-purple-500"></textarea>
                    @error('deskripsi') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Status Aktif (Toggle) --}}
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl border border-gray-200">
                    <div>
                        <p class="text-xs font-semibold text-gray-800">Status Aktif Master</p>
                        <p class="text-[11px] text-gray-400">Jika dimatikan, periode akan langsung berstatus nonaktif meskipun tanggal masih berlaku.</p>
                    </div>
                    <label class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $is_active ? 'bg-purple-600' : 'bg-gray-300' }}">
                        <input type="checkbox" wire:model="is_active" class="sr-only">
                        <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                    </label>
                </div>

                {{-- Modal Footer --}}
                <div class="flex items-center justify-end space-x-2 pt-3 border-t border-gray-100">
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                        Batal
                    </button>
                    <button type="submit" class="btn-primary px-5 py-2 text-xs shadow flex items-center space-x-1.5">
                        <span wire:loading.remove>{{ $isEdit ? 'Simpan Perubahan' : 'Buat Periode' }}</span>
                        <span wire:loading>Menyimpan...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- MODAL KONFIRMASI HAPUS --}}
    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center border border-rose-100 animate-scale-up">
            <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h3 class="font-bold text-gray-800 text-base mb-1">Hapus Periode?</h3>
            <p class="text-xs text-gray-500 mb-4">Apakah Anda yakin ingin menghapus periode <strong class="text-gray-700">"{{ $deleteNama }}"</strong>? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex items-center justify-center space-x-2">
                <button type="button" wire:click="$set('showDeleteModal', false)" class="px-4 py-2 text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
                    Batal
                </button>
                <button type="button" wire:click="delete" class="px-4 py-2 text-xs font-semibold text-white bg-rose-600 hover:bg-rose-700 rounded-xl transition shadow">
                    Ya, Hapus
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
