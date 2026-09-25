<div>
    @if(session()->has('message'))
    <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-xl mb-6 text-sm flex items-center space-x-2">
        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>{{ session('message') }}</span>
    </div>
    @endif

    <div class="card p-6">
        <h3 class="font-bold text-gray-700 text-sm mb-5 flex items-center space-x-2">
            <i class="fa-solid fa-address-book text-purple-500"></i>
            <span>Informasi Kontak & Alamat</span>
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Alamat Lengkap</label>
                <textarea wire:model="contact_address" class="w-full border px-3 py-2.5 rounded-xl text-sm" rows="3" placeholder="Contoh: Jl. Perjuangan By Pass Sunyaragi Kesambi, Kota Cirebon, Jawa Barat 45132"></textarea>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Nomor Telepon Kantor</label>
                    <div class="flex items-center">
                        <span class="bg-gray-50 border border-r-0 px-3 py-2.5 rounded-l-xl text-gray-500"><i class="fa-solid fa-phone"></i></span>
                        <input type="text" wire:model="contact_phone" class="w-full border px-3 py-2.5 rounded-r-xl text-sm" placeholder="Contoh: (0231) 481264">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Email Resmi</label>
                    <div class="flex items-center">
                        <span class="bg-gray-50 border border-r-0 px-3 py-2.5 rounded-l-xl text-gray-500"><i class="fa-solid fa-envelope"></i></span>
                        <input type="email" wire:model="contact_email" class="w-full border px-3 py-2.5 rounded-r-xl text-sm" placeholder="Contoh: ppg@uinssc.ac.id">
                    </div>
                </div>
            </div>
        </div>

        <h3 class="font-bold text-gray-700 text-sm mb-5 mt-8 flex items-center space-x-2 border-t pt-6">
            <i class="fa-solid fa-hashtag text-purple-500"></i>
            <span>Media Sosial Resmi</span>
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="text-xs font-semibold text-gray-600 mb-1.5 block">WhatsApp Center</label>
                <div class="flex items-center">
                    <span class="bg-green-50 border border-r-0 border-green-200 px-3 py-2.5 rounded-l-xl text-green-600"><i class="fa-brands fa-whatsapp text-lg"></i></span>
                    <input type="text" wire:model="contact_whatsapp" class="w-full border border-gray-200 px-3 py-2.5 rounded-r-xl text-sm" placeholder="Contoh: 6281234567890 (Gunakan awalan 62)">
                </div>
            </div>
            
            <div>
                <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Link Facebook</label>
                <div class="flex items-center">
                    <span class="bg-blue-50 border border-r-0 border-blue-200 px-3 py-2.5 rounded-l-xl text-blue-600"><i class="fa-brands fa-facebook text-lg"></i></span>
                    <input type="url" wire:model="contact_facebook" class="w-full border px-3 py-2.5 rounded-r-xl text-sm" placeholder="Contoh: https://facebook.com/ppguinssc">
                </div>
            </div>
            
            <div>
                <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Link Instagram</label>
                <div class="flex items-center">
                    <span class="bg-pink-50 border border-r-0 border-pink-200 px-3 py-2.5 rounded-l-xl text-pink-600"><i class="fa-brands fa-instagram text-lg"></i></span>
                    <input type="url" wire:model="contact_instagram" class="w-full border px-3 py-2.5 rounded-r-xl text-sm" placeholder="Contoh: https://instagram.com/ppguinssc">
                </div>
            </div>
            
            <div>
                <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Link YouTube</label>
                <div class="flex items-center">
                    <span class="bg-red-50 border border-r-0 border-red-200 px-3 py-2.5 rounded-l-xl text-red-600"><i class="fa-brands fa-youtube text-lg"></i></span>
                    <input type="url" wire:model="contact_youtube" class="w-full border px-3 py-2.5 rounded-r-xl text-sm" placeholder="Contoh: https://youtube.com/@ppguinssc">
                </div>
            </div>
            
            <div>
                <label class="text-xs font-semibold text-gray-600 mb-1.5 block">Link TikTok</label>
                <div class="flex items-center">
                    <span class="bg-gray-100 border border-r-0 border-gray-300 px-3 py-2.5 rounded-l-xl text-gray-800"><i class="fa-brands fa-tiktok text-lg"></i></span>
                    <input type="url" wire:model="contact_tiktok" class="w-full border px-3 py-2.5 rounded-r-xl text-sm" placeholder="Contoh: https://tiktok.com/@ppguinssc">
                </div>
            </div>
        </div>

        <div class="mt-8">
            <button wire:click="save" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2.5 px-6 rounded-xl flex items-center space-x-2 transition shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Simpan Perubahan Kontak</span>
            </button>
        </div>
    </div>
</div>
