<div>
    @if (session()->has('message'))
        <div class="bg-purple-100 border border-purple-400 text-purple-700 px-4 py-3 rounded-xl relative mb-4">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-rose-100 border border-rose-300 text-rose-800 px-4 py-3 rounded-xl relative mb-4 flex items-center space-x-2">
            <svg class="w-5 h-5 flex-shrink-0 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.486 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
            <span class="font-medium text-xs">{{ session('error') }}</span>
        </div>
    @endif

    @if ($currentStep === 7)
        <div class="text-center py-10">
            <svg class="mx-auto h-16 w-16 text-purple-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="text-xl font-bold text-gray-900">Data Terkunci & Sedang Direview</h3>
            <p class="mt-2 text-gray-600">Anda telah berhasil mengirimkan formulir lapor diri. Data Anda saat ini sedang dalam proses verifikasi oleh Administrator.</p>
            <a href="{{ route('mahasiswa.cetak.pdf') }}" target="_blank" class="mt-6 inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition shadow">Cetak Bukti Lapor Diri (PDF)</a>
        </div>
    @else
        <form wire:submit.prevent="submitForm">
            
            <!-- STEP 1: Data Pribadi -->
            <div class="{{ $currentStep != 1 ? 'hidden' : '' }}">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Langkah 1: Data Pribadi & Kependudukan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIM / NIK</label>
                        <input type="text" wire:model="nim" class="w-full border-gray-300 rounded-md shadow-sm bg-gray-50 focus:border-purple-500 focus:ring-purple-500" readonly>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" wire:model="nama" class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK KTP</label>
                        <input type="text" wire:model="nik" class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                        <select wire:model="jenis_kelamin" class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Lahir</label>
                        <input type="text" wire:model="tempat_lahir_id" class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
                        <input type="date" wire:model="tanggal_lahir" class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>
                </div>
            </div>

            <!-- STEP 2: Domisili -->
            <div class="{{ $currentStep != 2 ? 'hidden' : '' }}">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Langkah 2: Alamat & Domisili</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap (Jalan)</label>
                        <textarea wire:model="alamat" class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">RT / RW</label>
                        <div class="flex space-x-2">
                            <input type="text" wire:model="rt" placeholder="RT" class="w-1/2 border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            <input type="text" wire:model="rw" placeholder="RW" class="w-1/2 border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelurahan / Desa</label>
                        <input type="text" wire:model="kelurahan" class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                        <input type="text" wire:model="no_hp" class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>
                </div>
            </div>

            <!-- STEP 3: Pendidikan -->
            <div class="{{ $currentStep != 3 ? 'hidden' : '' }}">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Langkah 3: Riwayat Pendidikan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Asal Perguruan Tinggi (S1)</label>
                        <input type="text" wire:model="asal_perguruan_tinggi" placeholder="Nama Kampus Asal" class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Program Studi (S1)</label>
                        <input type="text" wire:model="asal_program_studi" placeholder="Nama Prodi Asal" class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>
                </div>
            </div>

            <!-- STEP 4: Keluarga -->
            <div class="{{ $currentStep != 4 ? 'hidden' : '' }}">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Langkah 4: Data Keluarga</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h4 class="font-semibold text-gray-700 mb-3">Data Ayah</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Nama Ayah</label>
                                <input type="text" wire:model="nama_ayah" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Pekerjaan Ayah</label>
                                <input type="text" wire:model="pekerjaan_ayah" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-purple-500">
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <h4 class="font-semibold text-gray-700 mb-3">Data Ibu</h4>
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Nama Ibu</label>
                                <input type="text" wire:model="nama_ibu" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-purple-500">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Pekerjaan Ibu</label>
                                <input type="text" wire:model="pekerjaan_ibu" class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:ring-purple-500">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- STEP 5: Ekonomi -->
            <div class="{{ $currentStep != 5 ? 'hidden' : '' }}">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Langkah 5: Ekonomi & Pembiayaan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sumber Dana Pembiayaan</label>
                        <input type="text" wire:model="sumber_dana" class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Penerima KPS/KIP?</label>
                        <select wire:model="terima_kps" class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            <option value="">-- Pilih --</option>
                            <option value="Ya">Ya</option>
                            <option value="Tidak">Tidak</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- STEP 6: Unggah Dokumen -->
            <div class="{{ $currentStep != 6 ? 'hidden' : '' }}">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Langkah 6: Unggah Dokumen Pendukung</h3>
                <p class="text-sm text-gray-500 mb-4">Pastikan file dalam format PDF/JPG dengan ukuran maksimal 2MB per file.</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Scan E-KTP</label>
                        <input type="file" wire:model="file_ktp" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ijazah S1</label>
                        <input type="file" wire:model="file_ijazah" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Transkrip Nilai</label>
                        <input type="file" wire:model="file_transkrip" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pakta Integritas</label>
                        <input type="file" wire:model="file_pakta_integritas" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Surat Sehat</label>
                        <input type="file" wire:model="file_surat_sehat" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Link RPL (URL)</label>
                        <input type="url" wire:model="link_rpl" placeholder="https://..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-purple-500 focus:ring-purple-500">
                    </div>
                </div>
                
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-6">
                    <p class="text-sm text-yellow-700 font-medium">Dengan menekan tombol Simpan Permanen, saya menyatakan bahwa data yang saya isikan adalah benar dan dapat dipertanggungjawabkan.</p>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="mt-8 flex justify-between pt-4 border-t border-gray-200">
                @if ($currentStep > 1)
                    <button type="button" wire:click="previousStep" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-2 rounded-lg font-medium shadow-sm transition">Sebelumnya</button>
                @else
                    <div></div>
                @endif
                
                @if ($currentStep < 6)
                    <button type="button" wire:click="nextStep" class="bg-purple-700 hover:bg-purple-800 text-white px-6 py-2 rounded-lg font-medium shadow transition">Selanjutnya</button>
                @else
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-bold shadow transition">Simpan Permanen & Kunci Data</button>
                @endif
            </div>
            
        </form>
    @endif
</div>
