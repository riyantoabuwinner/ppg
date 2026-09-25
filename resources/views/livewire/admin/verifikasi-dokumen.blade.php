<div>
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('message') }}
        </div>
    @endif

    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Verifikasi Dokumen: {{ $peserta->name }}</h2>
            <p class="text-gray-600 text-sm">NIM: {{ $peserta->username }} | Status Akun: 
                <span class="font-bold @if($peserta->status_lapor_diri == 'verified') text-green-600 @else text-yellow-600 @endif">
                    {{ strtoupper($peserta->status_lapor_diri) }}
                </span>
            </p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">&larr; Kembali ke Dashboard</a>
    </div>

    <div class="grid grid-cols-1 gap-6">
        @forelse($dokumens as $doc)
            <div class="bg-white rounded-xl shadow overflow-hidden flex flex-col md:flex-row">
                <!-- Document Viewer (Kiri) -->
                <div class="w-full md:w-2/3 bg-gray-100 border-r border-gray-200 min-h-[400px]">
                    @if($doc->jenis_dokumen === 'link_rpl')
                        <div class="flex items-center justify-center h-full">
                            <a href="{{ $doc->file_path }}" target="_blank" class="text-blue-600 underline font-medium">Buka Link RPL di Tab Baru</a>
                        </div>
                    @else
                        @if(Str::endsWith(strtolower($doc->file_path), ['.pdf']))
                            <iframe src="{{ asset('storage/' . $doc->file_path) }}" class="w-full h-[500px]" frameborder="0"></iframe>
                        @else
                            <div class="flex justify-center p-4">
                                <img src="{{ asset('storage/' . $doc->file_path) }}" alt="{{ $doc->jenis_dokumen }}" class="max-w-full max-h-[500px] object-contain shadow-sm">
                            </div>
                        @endif
                    @endif
                </div>
                
                <!-- Action Panel (Kanan) -->
                <div class="w-full md:w-1/3 p-6 flex flex-col justify-between">
                    <div>
                        <h4 class="text-lg font-bold text-gray-800 uppercase">{{ str_replace('_', ' ', $doc->jenis_dokumen) }}</h4>
                        <div class="mt-2">
                            Status: 
                            @if($doc->status == 'approved')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                            @elseif($doc->status == 'rejected')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            @endif
                        </div>
                        
                        @if($doc->status == 'rejected' && $doc->catatan_revisi)
                            <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded text-sm text-red-800">
                                <b>Catatan:</b> {{ $doc->catatan_revisi }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-6 space-y-3">
                        @if($doc->status !== 'approved')
                            <button wire:click="approve({{ $doc->id }})" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded shadow transition">Setujui Dokumen (Valid)</button>
                        @endif
                        
                        @if($doc->status !== 'rejected')
                            <button wire:click="openRejectModal({{ $doc->id }})" class="w-full bg-red-100 hover:bg-red-200 text-red-800 font-medium py-2 px-4 rounded transition border border-red-300">Tolak & Minta Revisi</button>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-yellow-50 p-6 rounded-xl border border-yellow-200 text-center text-yellow-700">
                Mahasiswa ini belum mengunggah dokumen apapun atau data dokumen belum tersimpan di versi terbaru.
            </div>
        @endforelse
    </div>

    <!-- Reject Modal -->
    @if($showRejectModal)
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-xl w-full max-w-md">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Catatan Penolakan / Revisi</h3>
                <textarea wire:model="catatanRevisi" rows="4" class="w-full border border-gray-300 rounded p-2 focus:ring-green-500 focus:border-green-500" placeholder="Jelaskan alasan penolakan (misal: Gambar blur, KTP tidak jelas)..."></textarea>
                <div class="mt-4 flex justify-end space-x-3">
                    <button wire:click="$set('showRejectModal', false)" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Batal</button>
                    <button wire:click="reject" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Kirim Catatan</button>
                </div>
            </div>
        </div>
    @endif
</div>
