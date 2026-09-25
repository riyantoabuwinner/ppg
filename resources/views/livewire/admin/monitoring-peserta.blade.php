<div>
    {{-- Flash Message --}}
    @if (session()->has('message'))
        <div class="mb-4 bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="font-medium">{{ session('message') }}</span>
            </div>
            <button type="button" wire:click="$refresh" class="text-green-600 hover:text-green-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-xl text-sm flex items-center justify-between shadow-sm">
            <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                <span class="font-medium">{{ session('error') }}</span>
            </div>
            <button type="button" wire:click="$refresh" class="text-red-600 hover:text-red-800">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    @endif

    {{-- Reactive Mini Stat Bar --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="card p-4 flex items-center space-x-3 border-l-4 border-purple-500 bg-white shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center font-bold text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <div class="text-xl font-extrabold text-gray-800">{{ $totalCount }}</div>
                <div class="text-xs text-gray-500 font-medium">Total Mahasiswa</div>
            </div>
        </div>
        <div class="card p-4 flex items-center space-x-3 border-l-4 border-amber-500 bg-white shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="text-xl font-extrabold text-gray-800">{{ $submittedCount }}</div>
                <div class="text-xs text-gray-500 font-medium">Menunggu Verifikasi</div>
            </div>
        </div>
        <div class="card p-4 flex items-center space-x-3 border-l-4 border-green-500 bg-white shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-green-100 text-green-700 flex items-center justify-center font-bold text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <div class="text-xl font-extrabold text-gray-800">{{ $verifiedCount }}</div>
                <div class="text-xs text-gray-500 font-medium">Terverifikasi</div>
            </div>
        </div>
        <div class="card p-4 flex items-center space-x-3 border-l-4 border-gray-400 bg-white shadow-sm">
            <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-700 flex items-center justify-center font-bold text-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </div>
            <div>
                <div class="text-xl font-extrabold text-gray-800">{{ $draftCount }}</div>
                <div class="text-xs text-gray-500 font-medium">Draft (Belum Selesai)</div>
            </div>
        </div>
    </div>

    {{-- Bulk Action Floating / Interactive Banner --}}
    @if(count($selectedPesertas) > 0)
    <div class="mb-4 bg-gradient-to-r from-purple-900 to-indigo-900 text-white px-5 py-3.5 rounded-2xl shadow-lg flex flex-col sm:flex-row items-center justify-between gap-3 animate-fadeIn border border-purple-700/50">
        <div class="flex items-center space-x-3">
            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center font-bold text-sm text-purple-200">
                {{ count($selectedPesertas) }}
            </div>
            <div>
                <div class="font-bold text-xs">
                    <span>{{ count($selectedPesertas) }} mahasiswa terpilih</span>
                </div>
                <div class="text-[11px] text-purple-200 mt-0.5 flex items-center space-x-2">
                    @if(count($selectedPesertas) < $pesertas->total())
                    <button wire:click="selectAllMatching" class="underline hover:text-white font-semibold">
                        Pilih semua {{ $pesertas->total() }} mahasiswa (semua halaman)
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
            {{-- Bulk Verify --}}
            <button wire:click="bulkVerify" wire:loading.attr="disabled" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 active:bg-emerald-700 text-white text-xs font-semibold rounded-lg shadow-sm flex items-center space-x-1.5 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Verifikasi Masal</span>
            </button>

            {{-- Bulk Draft --}}
            <button wire:click="bulkDraft" wire:loading.attr="disabled" class="px-3 py-1.5 bg-amber-500 hover:bg-amber-400 active:bg-amber-600 text-white text-xs font-semibold rounded-lg shadow-sm flex items-center space-x-1.5 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span>Jadikan Draft</span>
            </button>

            {{-- Bulk Delete (Permintaan User: Hapus Masal) --}}
            <button wire:click="bulkDelete" onclick="confirm('PERINGATAN: Apakah Anda yakin ingin MENGHAPUS PERMANEN ' + {{ count($selectedPesertas) }} + ' data mahasiswa lapor diri terpilih?') || event.stopImmediatePropagation()" wire:loading.attr="disabled" class="px-3 py-1.5 bg-red-600 hover:bg-red-500 active:bg-red-700 text-white text-xs font-semibold rounded-lg shadow-sm flex items-center space-x-1.5 transition">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>Hapus Masal</span>
            </button>

            {{-- Batal --}}
            <button wire:click="deselectAll" class="px-3 py-1.5 bg-white/20 hover:bg-white/30 text-white text-xs font-medium rounded-lg transition">
                Batal
            </button>
        </div>
    </div>
    @endif

    {{-- Main Table Card --}}
    <div class="card overflow-hidden bg-white shadow-sm border border-purple-100 rounded-2xl">
        <div class="p-5 border-b border-purple-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white">
            <div>
                <h3 class="font-bold text-gray-800 text-base">Tabel Data Mahasiswa Lapor Diri</h3>
                <p class="text-xs text-gray-500 mt-0.5">Daftar seluruh mahasiswa peserta PPG yang terdaftar dalam sistem</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                {{-- Bulk Action Dropdown --}}
                <div class="flex items-center space-x-1.5">
                    <select wire:model="bulkAction" class="border border-purple-200 rounded-xl px-3 py-2 text-xs focus:ring-purple-500 focus:border-purple-500 outline-none bg-purple-50/40 text-gray-700 font-medium">
                        <option value="">Aksi Masal {{ count($selectedPesertas) > 0 ? '('.count($selectedPesertas).' dipilih)' : '' }}</option>
                        <option value="delete">Hapus Masal</option>
                        <option value="verify">Verifikasi Masal</option>
                        <option value="draft">Jadikan Draft Masal</option>
                    </select>
                    <button 
                        wire:click="executeBulkAction" 
                        @if($bulkAction === 'delete') onclick="confirm('PERINGATAN: Apakah Anda yakin ingin MENGHAPUS PERMANEN ' + {{ count($selectedPesertas) }} + ' data mahasiswa terpilih?') || event.stopImmediatePropagation()" @endif
                        type="button" 
                        class="px-3 py-2 bg-purple-100 hover:bg-purple-200 text-purple-800 rounded-xl text-xs font-semibold transition"
                    >
                        Terapkan
                    </button>
                </div>

                {{-- Import Button --}}
                <button 
                    wire:click="openImportModal" 
                    type="button" 
                    class="inline-flex items-center px-3.5 py-2 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white text-xs font-semibold shadow-sm hover:shadow transition transform active:scale-95 space-x-1.5"
                >
                    <svg class="w-4 h-4 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    <span>Import Data Lapor Diri</span>
                </button>

                {{-- Periode Filter --}}
                <select wire:model.live="periodeFilter" class="border border-purple-200 rounded-xl px-3 py-2 text-xs focus:ring-purple-500 focus:border-purple-500 outline-none bg-purple-50/40 text-gray-700 font-medium">
                    <option value="">Semua Periode</option>
                    @foreach($periodes as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                    @endforeach
                </select>

                {{-- Status Filter --}}
                <select wire:model.live="statusFilter" class="border border-purple-200 rounded-xl px-3 py-2 text-xs focus:ring-purple-500 focus:border-purple-500 outline-none bg-purple-50/40 text-gray-700 font-medium">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft (Belum Selesai)</option>
                    <option value="submitted">Menunggu Verifikasi</option>
                    <option value="verified">Terverifikasi</option>
                </select>

                {{-- Search Box --}}
                <div class="relative">
                    <input type="text" wire:model.live="search" placeholder="Cari Nama / NIM..." class="border border-purple-200 rounded-xl pl-8 pr-3 py-2 text-xs focus:ring-purple-500 focus:border-purple-500 outline-none w-52 sm:w-60 bg-white">
                    <svg class="w-3.5 h-3.5 text-gray-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-purple-100 text-sm">
                <thead class="bg-purple-50/50">
                    <tr>
                        <th class="w-10 px-4 py-3.5 text-center">
                            <input type="checkbox" wire:model.live="selectAll" title="Pilih Semua di Halaman Ini" class="rounded border-purple-300 text-purple-600 focus:ring-purple-500 cursor-pointer">
                        </th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-purple-900 uppercase tracking-wider">NIM & Mahasiswa</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-purple-900 uppercase tracking-wider">NIK & Kontak</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-purple-900 uppercase tracking-wider">Asal Prodi / Kampus</th>
                        <th scope="col" class="px-6 py-3.5 text-left text-xs font-semibold text-purple-900 uppercase tracking-wider">Status Lapor Diri</th>
                        <th scope="col" class="px-6 py-3.5 text-right text-xs font-semibold text-purple-900 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-50 bg-white">
                    @forelse ($pesertas as $peserta)
                    @php $isSelected = in_array((string)$peserta->user_id, array_map('strval', $selectedPesertas)); @endphp
                    <tr class="hover:bg-purple-50/30 transition {{ $isSelected ? 'bg-purple-50/60 border-l-4 border-l-purple-600' : '' }}">
                        <td class="w-10 px-4 py-4 text-center whitespace-nowrap">
                            <input type="checkbox" wire:model.live="selectedPesertas" value="{{ $peserta->user_id }}" class="rounded border-purple-300 text-purple-600 focus:ring-purple-500 cursor-pointer">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-bold text-gray-900">{{ $peserta->nama }}</div>
                            <div class="text-xs text-purple-600 font-mono mt-0.5">NIM: {{ $peserta->nim ?? '-' }}</div>
                            @if($peserta->no_tes)
                                <div class="text-[11px] text-gray-400">No. Tes: {{ $peserta->no_tes }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-xs text-gray-700 font-mono">NIK: {{ $peserta->nik ?? '-' }}</div>
                            <div class="text-xs text-gray-500 mt-0.5">{{ $peserta->no_hp ?? '-' }}</div>
                            @if($peserta->email)
                                <div class="text-[11px] text-purple-500">{{ $peserta->email }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-xs text-gray-900 font-medium">{{ optional($peserta->education)->asal_program_studi ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ optional($peserta->education)->asal_perguruan_tinggi ?? '-' }}</div>
                            @if($peserta->jalur_pendaftaran)
                                <span class="inline-block mt-1 text-[10px] px-2 py-0.5 bg-gray-100 text-gray-600 rounded">
                                    {{ $peserta->jalur_pendaftaran }} {{ $peserta->gelombang ? '('.$peserta->gelombang.')' : '' }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(optional($peserta->user)->status_lapor_diri === 'verified')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">Terverifikasi</span>
                            @elseif(optional($peserta->user)->status_lapor_diri === 'submitted')
                                <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-amber-100 text-amber-800 border border-amber-200">Menunggu Verifikasi</span>
                            @else
                                <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-gray-100 text-gray-700 border border-gray-200">Draft</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-xs font-medium space-x-2">
                            <a href="{{ route('admin.verifikasi', $peserta->user_id) }}" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-purple-50 hover:bg-purple-100 text-purple-700 font-semibold border border-purple-200 transition">
                                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Detail & Verifikasi
                            </a>

                            
                            @if(optional($peserta->user)->status_lapor_diri === 'submitted')
                                <button wire:click="verify({{ $peserta->user_id }})" onclick="confirm('Verifikasi data mahasiswa ini secara langsung?') || event.stopImmediatePropagation()" class="inline-flex items-center px-3 py-1.5 rounded-lg bg-green-600 hover:bg-green-700 text-white font-semibold shadow transition">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Setujui Cepat
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 whitespace-nowrap text-center text-sm text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-full bg-purple-50 text-purple-400 flex items-center justify-center mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <span class="font-medium text-gray-600">Tidak ada data mahasiswa ditemukan.</span>
                                <span class="text-xs text-gray-400 mt-1">Anda dapat mengimpor data dari sistem sebelumnya menggunakan tombol Import di atas.</span>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-purple-100 bg-purple-50/20">
            {{ $pesertas->links() }}
        </div>
    </div>

    {{-- MODAL IMPORT DATA LAPOR DIRI --}}
    @if ($showImportModal)
        <div class="fixed inset-0 z-50 overflow-y-auto bg-gray-900/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full border border-purple-100 overflow-hidden transform transition-all animate-fadeIn">
                {{-- Modal Header --}}
                <div class="p-6 bg-gradient-to-r from-purple-700 via-purple-800 to-indigo-800 text-white flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-sm flex items-center justify-center text-white border border-white/20">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        </div>
                        <div>
                            <h3 class="text-base font-bold">Import Data Lapor Diri Mahasiswa</h3>
                            <p class="text-xs text-purple-200 mt-0.5">Tampung data mahasiswa dari sistem sebelumnya via Excel atau CSV</p>
                        </div>
                    </div>
                    <button type="button" wire:click="closeImportModal" class="text-purple-200 hover:text-white p-1 rounded-lg hover:bg-white/10 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="p-6 space-y-5 max-h-[75vh] overflow-y-auto">
                    {{-- Template Download Notice --}}
                    <div class="bg-purple-50/80 border border-purple-200 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-start space-x-2.5">
                            <svg class="w-5 h-5 text-purple-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <p class="text-xs font-bold text-purple-900">Format Fleksibel (PDDIKTI, SIAKAD, atau Excel Umum)</p>
                                <p class="text-[11px] text-purple-700 mt-0.5">Sistem secara cerdas memetakan kolom NIM, NIK, Nama, Prodi, Alamat, dsb. Anda juga dapat menggunakan template resmi kami.</p>
                            </div>
                        </div>
                        <a 
                            href="{{ route('admin.master-data.template-import') }}" 
                            class="inline-flex items-center px-3 py-1.5 rounded-lg bg-purple-600 hover:bg-purple-700 text-white text-xs font-semibold whitespace-nowrap shadow-sm transition"
                        >
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Unduh Template (.xlsx)
                        </a>
                    </div>

                    {{-- Upload Dropzone --}}
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Pilih Berkas Excel / CSV <span class="text-red-500">*</span></label>
                        <div class="border-2 border-dashed border-purple-200 hover:border-purple-400 rounded-2xl p-6 text-center bg-purple-50/30 transition relative cursor-pointer group">
                            <input 
                                type="file" 
                                wire:model="fileImport" 
                                accept=".xlsx, .xls, .csv" 
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                            >
                            <div class="flex flex-col items-center justify-center">
                                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 group-hover:scale-110 transition flex items-center justify-center mb-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                </div>
                                <p class="text-xs font-semibold text-gray-700">
                                    Klik untuk memilih berkas atau seret berkas ke sini
                                </p>
                                <p class="text-[11px] text-gray-400 mt-1">
                                    Mendukung format Microsoft Excel (.xlsx, .xls) dan Comma-Separated Values (.csv) hingga 20MB
                                </p>
                                
                                {{-- Loading indicator during file upload --}}
                                <div wire:loading wire:target="fileImport" class="mt-3 text-purple-700 text-xs font-semibold flex items-center space-x-1.5">
                                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    <span>Sedang mengunggah berkas...</span>
                                </div>

                                {{-- File chosen indicator --}}
                                @if ($fileImport)
                                    <div wire:loading.remove wire:target="fileImport" class="mt-3 px-3 py-1.5 bg-green-100 text-green-800 rounded-lg text-xs font-semibold flex items-center space-x-1.5">
                                        <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                        <span>Berkas Terpilih: {{ $fileImport->getClientOriginalName() }} ({{ round($fileImport->getSize() / 1024, 1) }} KB)</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @error('fileImport')
                            <p class="text-xs text-red-600 mt-1.5 flex items-center space-x-1">
                                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                                <span>{{ $message }}</span>
                            </p>
                        @enderror
                    </div>

                    {{-- Options Section --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5 pt-2">
                        {{-- Status Default --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Status Lapor Diri</label>
                            <select wire:model="importStatusDefault" class="w-full border border-purple-200 rounded-xl px-3 py-2 text-xs focus:ring-purple-500 focus:border-purple-500 bg-purple-50/20 text-gray-800">
                                <option value="submitted">Menunggu Verifikasi (Submitted)</option>
                                <option value="verified">Terverifikasi (Verified)</option>
                                <option value="draft">Draft (Belum Selesai)</option>
                            </select>
                            <p class="text-[10px] text-gray-400 mt-1">Status awal jika kolom status di berkas kosong</p>
                        </div>

                        {{-- Duplicate Handling --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Data Duplikat (NIM/NIK)</label>
                            <select wire:model="duplicateAction" class="w-full border border-purple-200 rounded-xl px-3 py-2 text-xs focus:ring-purple-500 focus:border-purple-500 bg-purple-50/20 text-gray-800">
                                <option value="update">Perbarui Data yang Ada (Update)</option>
                                <option value="skip">Lewati Duplikat (Skip)</option>
                            </select>
                            <p class="text-[10px] text-gray-400 mt-1">Aksi saat NIM/NIK mahasiswa sudah terdaftar</p>
                        </div>

                        {{-- Password Scheme --}}
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Password Akun Mahasiswa</label>
                            <select wire:model="passwordScheme" class="w-full border border-purple-200 rounded-xl px-3 py-2 text-xs focus:ring-purple-500 focus:border-purple-500 bg-purple-50/20 text-gray-800">
                                <option value="dob">Tanggal Lahir (YYYYMMDD)</option>
                                <option value="nik">Gunakan NIK Mahasiswa</option>
                                <option value="default">Password Default (12345678)</option>
                            </select>
                            <p class="text-[10px] text-gray-400 mt-1">Kredensial login akun mahasiswa baru</p>
                        </div>
                    </div>

                    {{-- Import Result Feedback --}}
                    @if ($importResult)
                        <div class="mt-4 p-4 rounded-xl border {{ $importResult['imported'] > 0 || $importResult['updated'] > 0 ? 'bg-green-50 border-green-200' : 'bg-amber-50 border-amber-200' }}">
                            <div class="flex items-center justify-between mb-3">
                                <span class="font-bold text-xs text-gray-800">Ringkasan Hasil Import:</span>
                                <span class="text-xs text-gray-500">Total Baris: <b>{{ $importResult['total_rows'] }}</b></span>
                            </div>

                            <div class="grid grid-cols-3 gap-2 text-center text-xs">
                                <div class="p-2 rounded-lg bg-green-100 text-green-800 font-semibold border border-green-200">
                                    <div class="text-base font-extrabold">{{ $importResult['imported'] }}</div>
                                    <div class="text-[10px]">Data Baru</div>
                                </div>
                                <div class="p-2 rounded-lg bg-blue-100 text-blue-800 font-semibold border border-blue-200">
                                    <div class="text-base font-extrabold">{{ $importResult['updated'] }}</div>
                                    <div class="text-[10px]">Data Diperbarui</div>
                                </div>
                                <div class="p-2 rounded-lg bg-gray-100 text-gray-800 font-semibold border border-gray-200">
                                    <div class="text-base font-extrabold">{{ $importResult['skipped'] }}</div>
                                    <div class="text-[10px]">Dilewati</div>
                                </div>
                            </div>

                            @if (!empty($importResult['errors']))
                                <div class="mt-3 pt-3 border-t border-gray-200">
                                    <p class="text-[11px] font-bold text-amber-800 mb-1">Catatan / Peringatan:</p>
                                    <ul class="text-[10px] text-amber-700 space-y-0.5 max-h-24 overflow-y-auto pl-4 list-disc">
                                        @foreach ($importResult['errors'] as $err)
                                            <li>{{ $err }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Modal Footer --}}
                <div class="p-5 border-t border-purple-100 bg-purple-50/30 flex items-center justify-between">
                    <button 
                        type="button" 
                        wire:click="closeImportModal" 
                        class="px-4 py-2 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-100 text-xs font-semibold transition"
                    >
                        {{ $importResult ? 'Selesai & Tutup' : 'Batal' }}
                    </button>

                    <button 
                        type="button" 
                        wire:click="importLaporDiri" 
                        wire:loading.attr="disabled"
                        class="px-5 py-2 rounded-xl bg-purple-700 hover:bg-purple-800 text-white text-xs font-semibold shadow-md hover:shadow-lg transition flex items-center space-x-1.5 disabled:opacity-50"
                    >
                        <span wire:loading.remove wire:target="importLaporDiri">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            Mulai Proses Impor
                        </span>
                        <span wire:loading wire:target="importLaporDiri" class="flex items-center space-x-1.5">
                            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>Memproses data impor...</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
