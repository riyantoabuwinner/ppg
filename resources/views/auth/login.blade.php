<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ \App\Models\AppSetting::get('app_name', 'Lapor Diri PPG') }} — {{ \App\Models\AppSetting::get('app_subtitle', 'UIN Siber Syekh Nurjati Cirebon') }}</title>
    @php $favicon = \App\Models\AppSetting::get('app_favicon'); @endphp
    @if($favicon)
    <link rel="icon" type="image/png" href="{{ asset('storage/'.$favicon) }}">
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { background: rgba(255,255,255,0.75); backdrop-filter: blur(20px); border: 1px solid rgba(167,139,250,0.2); }
        .glow { box-shadow: 0 0 60px rgba(124,58,237,0.2), 0 20px 60px rgba(0,0,0,0.08); }
        input:focus { outline: none; border-color: #7C3AED; box-shadow: 0 0 0 3px rgba(124,58,237,0.12); }
    </style>
</head>
<body class="min-h-screen flex" style="background: linear-gradient(135deg, #1E1B4B 0%, #4C1D95 40%, #2D1B69 70%, #0F0C29 100%);">

    {{-- Decorative Blobs --}}
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full opacity-20" style="background: radial-gradient(circle, #A78BFA, transparent 70%);"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 rounded-full opacity-20" style="background: radial-gradient(circle, #7C3AED, transparent 70%);"></div>
        <div class="absolute top-1/2 left-1/3 w-64 h-64 rounded-full opacity-10" style="background: radial-gradient(circle, #F5C518, transparent 70%);"></div>
    </div>

    <div class="flex w-full min-h-screen items-center justify-center p-4 relative">
        <div class="w-full max-w-4xl glass rounded-3xl overflow-hidden glow flex">

            {{-- Left: Branding --}}
            <div class="hidden md:flex w-1/2 flex-col justify-between p-10 relative overflow-hidden" style="background: linear-gradient(145deg, rgba(124,58,237,0.9), rgba(91,33,182,0.95));">
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 20% 80%, white 1px, transparent 1px), radial-gradient(circle at 80% 20%, white 1px, transparent 1px); background-size: 40px 40px;"></div>
                <div class="relative">
                    <div class="flex items-center space-x-3 mb-10">
                        @php $logoDark = \App\Models\AppSetting::get('app_logo_dark'); @endphp
                        @if($logoDark)
                            <img src="{{ asset('storage/'.$logoDark) }}" class="h-12 w-auto object-contain max-w-[160px]" alt="Logo">
                        @else
                            <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center font-black text-white text-sm shadow-lg backdrop-blur">PPG</div>
                        @endif
                        <div>
                            <p class="text-white font-bold text-sm">{{ \App\Models\AppSetting::get('app_name', 'Portal Lapor Diri PPG') }}</p>
                            <p class="text-purple-200 text-xs">{{ \App\Models\AppSetting::get('app_subtitle', 'UIN Siber Syekh Nurjati Cirebon') }}</p>
                        </div>
                    </div>

                    <h1 class="text-white font-extrabold text-3xl leading-tight mb-3">Portal<br>Lapor Diri<br>PPG</h1>
                    <p class="text-purple-200 text-sm leading-relaxed">
                        Selamat datang di sistem lapor diri mahasiswa baru {{ \App\Models\AppSetting::get('app_name', 'Program Pendidikan Profesi Guru (PPG)') }} {{ \App\Models\AppSetting::get('app_subtitle', 'UIN Siber Syekh Nurjati Cirebon') }}.
                    </p>
                </div>

                <div class="relative space-y-3">
                    @foreach(['Pengisian Data Tunggal (Single Entry)', 'Ekspor Otomatis ke PDDIKTI & SIAKAD', 'Verifikasi Dokumen Online', 'Tanda Bukti PDF Resmi'] as $f)
                    <div class="flex items-center space-x-2 text-sm text-purple-100">
                        <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                        </div>
                        <span>{{ $f }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Right: Login Form --}}
            <div class="w-full md:w-1/2 p-10 flex flex-col justify-center bg-white/90">
                <div class="max-w-sm mx-auto w-full">
                    <div class="mb-8 text-center md:text-left">
                        <h2 class="text-2xl font-extrabold text-gray-800 mb-1">Silakan Masuk</h2>
                        <p class="text-gray-500 text-sm">Gunakan NIM / Nomor Tes dan Password (NIK) Anda</p>
                    </div>

                    @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 text-sm flex items-start space-x-2">
                        <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <ul class="list-none">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                        @csrf
                        <div>
                            <label for="username" class="block text-sm font-semibold text-gray-700 mb-1.5">NIM / Nomor Tes</label>
                            <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm transition bg-gray-50 focus:bg-white"
                                placeholder="Contoh: 2309110001">
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password / NIK</label>
                            <input id="password" type="password" name="password" required
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm transition bg-gray-50 focus:bg-white"
                                placeholder="Nomor NIK Anda (16 digit)">
                        </div>

                        <button type="submit" class="w-full py-3 rounded-xl text-white font-bold text-sm shadow-lg transition"
                            style="background: linear-gradient(135deg, #7C3AED, #5B21B6); box-shadow: 0 6px 20px rgba(124,58,237,0.35);"
                            onmouseover="this.style.transform='translateY(-1px)'"
                            onmouseout="this.style.transform='translateY(0)'">
                            Masuk ke Sistem
                        </button>
                    </form>

                    <p class="text-center text-xs text-gray-400 mt-6">
                        Mengalami kendala login? <a href="#" class="text-purple-600 font-medium hover:underline">Hubungi Helpdesk</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
