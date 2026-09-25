<div>
    @if(session()->has('message'))
    <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-6 text-sm flex items-center space-x-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>{{ session('message') }}</span>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Form Utama --}}
        <div class="lg:col-span-2">
            <div class="card p-6">
                <h3 class="font-bold text-gray-700 text-sm mb-5 flex items-center space-x-2">
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Informasi Aplikasi</span>
                </h3>
                <div class="space-y-5">
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Judul Aplikasi <span class="text-red-400">*</span></label>
                        <input type="text" wire:model="app_name" class="w-full border px-3 py-2.5 rounded-xl text-sm" placeholder="Contoh: Portal Lapor Diri PPG">
                        @error('app_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Sub Judul / Nama Institusi</label>
                        <input type="text" wire:model="app_subtitle" class="w-full border px-3 py-2.5 rounded-xl text-sm" placeholder="Contoh: UIN Siber Syekh Nurjati Cirebon">
                    </div>
                </div>
            </div>
        </div>

        {{-- Preview Saat Ini --}}
        <div class="space-y-4">
            <div class="card p-5" style="background: linear-gradient(135deg,#4C1D95,#7C3AED);">
                <p class="text-xs font-semibold text-purple-200 mb-3 uppercase tracking-wider">Preview Identitas</p>
                <div class="flex items-center space-x-3 mb-3">
                    @if($logo_dark)
                    <img src="{{ asset('storage/'.$logo_dark) }}" class="h-10 w-auto" alt="Logo">
                    @else
                    <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center font-black text-white text-xs">PPG</div>
                    @endif
                    <div>
                        <p class="text-white font-bold text-sm">{{ \App\Models\AppSetting::get('app_name', 'Portal Lapor Diri PPG') }}</p>
                        <p class="text-purple-200 text-xs">{{ \App\Models\AppSetting::get('app_subtitle', 'UIN Siber Syekh Nurjati Cirebon') }}</p>
                    </div>
                </div>
                @if($favicon)
                <div class="flex items-center space-x-2 mt-2">
                    <img src="{{ asset('storage/'.$favicon) }}" class="h-5 w-5 rounded" alt="Favicon">
                    <span class="text-xs text-purple-200">Favicon aktif</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Upload Logos --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        {{-- Logo Terang (untuk background gelap) --}}
        <div class="card p-5">
            <p class="text-xs font-semibold text-gray-600 mb-1">Logo Versi Gelap (Dark Mode)</p>
            <p class="text-xs text-gray-400 mb-3">Digunakan pada sidebar & background gelap. Format PNG transparan dianjurkan.</p>
            @if($logo_dark)
            <div class="mb-3 p-3 rounded-lg bg-purple-900 flex justify-center">
                <img src="{{ asset('storage/'.$logo_dark) }}" class="max-h-14 w-auto" alt="Logo Gelap">
            </div>
            @endif
            <input type="file" wire:model="logo_dark_upload" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
            @error('logo_dark_upload') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Logo Terang (untuk background putih/terang) --}}
        <div class="card p-5">
            <p class="text-xs font-semibold text-gray-600 mb-1">Logo Versi Terang (Light Mode)</p>
            <p class="text-xs text-gray-400 mb-3">Digunakan pada header & background putih. Format PNG transparan dianjurkan.</p>
            @if($logo_light)
            <div class="mb-3 p-3 rounded-lg bg-gray-100 flex justify-center border">
                <img src="{{ asset('storage/'.$logo_light) }}" class="max-h-14 w-auto" alt="Logo Terang">
            </div>
            @endif
            <input type="file" wire:model="logo_light_upload" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
            @error('logo_light_upload') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Favicon --}}
        <div class="card p-5">
            <p class="text-xs font-semibold text-gray-600 mb-1">Favicon</p>
            <p class="text-xs text-gray-400 mb-3">Ikon kecil yang tampil di tab browser. Ukuran ideal 32x32 px (PNG/ICO).</p>
            @if($favicon)
            <div class="mb-3 p-3 rounded-lg bg-gray-100 flex justify-center border">
                <img src="{{ asset('storage/'.$favicon) }}" class="h-8 w-8" alt="Favicon">
            </div>
            @endif
            <input type="file" wire:model="favicon_upload" accept="image/*" class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
            @error('favicon_upload') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>
    </div>

    {{-- Save Button --}}
    <div class="mt-6 flex justify-end">
        <button wire:click="save" wire:loading.attr="disabled" class="btn-primary px-8 py-3 text-sm flex items-center space-x-2">
            <span wire:loading.remove>Simpan Identitas Aplikasi</span>
            <span wire:loading>Menyimpan...</span>
        </button>
    </div>
</div>
