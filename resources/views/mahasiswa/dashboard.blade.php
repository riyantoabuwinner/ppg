@extends('layouts.app')
@section('title', 'Dashboard Mahasiswa — Lapor Diri PPG')
@section('page-title', 'Lapor Diri Mahasiswa Baru PPG')
@section('page-subtitle', 'Pendidikan Profesi Guru — UIN Siber Syekh Nurjati Cirebon')

@section('content')
@php
    $user = Auth::user();
    $statusLabel = [
        'draft'     => ['teks' => 'Belum Selesai', 'color' => 'bg-amber-100 text-amber-800 border border-amber-200'],
        'submitted' => ['teks' => 'Menunggu Verifikasi Admin', 'color' => 'bg-blue-100 text-blue-800 border border-blue-200'],
        'verified'  => ['teks' => 'Data Terverifikasi ✓', 'color' => 'bg-green-100 text-green-800 border border-green-200'],
    ][$user->status_lapor_diri] ?? ['teks' => '-', 'color' => 'bg-gray-100 text-gray-700'];

    $activePeriod   = \App\Models\Period::currentOpen();
    $isOpen         = !is_null($activePeriod);
    $upcomingPeriod = \App\Models\Period::nextUpcoming();
    $latestClosed   = \App\Models\Period::latestClosed();
@endphp

{{-- Info Banner User --}}
<div class="card p-5 mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
    <div class="flex items-center space-x-4">
        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-purple-700 flex items-center justify-center text-white font-black text-xl flex-shrink-0 shadow">
            {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
            <p class="font-bold text-gray-800">{{ $user->name }}</p>
            <p class="text-sm text-gray-500">NIM / No Tes: <span class="font-mono font-semibold">{{ $user->username }}</span></p>
        </div>
    </div>
    <span class="text-xs font-semibold px-3 py-1.5 rounded-full {{ $statusLabel['color'] }}">
        Status: {{ $statusLabel['teks'] }}
    </span>
</div>

{{-- BANNER PERIODE AKTIF --}}
@if($isOpen)
<div class="card p-4 mb-6 border-l-4 border-emerald-500 bg-emerald-50/60 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 shadow-sm">
    <div class="flex items-center space-x-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </div>
        <div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-100 text-emerald-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1 animate-ping"></span>
                    Periode Sedang Dibuka
                </span>
                <h4 class="font-bold text-gray-800 text-sm">{{ $activePeriod->nama }}</h4>
            </div>
            <p class="text-xs text-gray-600 mt-0.5">
                Batas pengisian: <strong class="text-emerald-700 font-semibold">{{ $activePeriod->tanggal_selesai->translatedFormat('d F Y, H:i') }} WIB</strong>
                @if($activePeriod->tahun_akademik) &bull; TA {{ $activePeriod->tahun_akademik }} @endif
                @if($activePeriod->deskripsi) &bull; <span class="text-gray-500">{{ $activePeriod->deskripsi }}</span> @endif
            </p>
        </div>
    </div>
</div>
@endif

{{-- KONDISI JIKA STATUS DRAFT & PERIODE DITUTUP --}}
@if($user->status_lapor_diri === 'draft' && !$isOpen)
<div class="card p-8 text-center max-w-2xl mx-auto my-6 border-t-4 border-rose-500 shadow-md">
    <div class="w-16 h-16 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4 shadow-sm">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
    </div>
    <h3 class="text-xl font-extrabold text-gray-800 mb-2">Periode Lapor Diri Sedang Ditutup</h3>
    <p class="text-xs text-gray-500 mb-6">
        Saat ini portal lapor diri tidak menerima pengisian atau pengiriman formulir baru karena periode pendaftaran belum dibuka atau sudah berakhir.
    </p>

    {{-- Info Jadwal Mendatang / Berakhir --}}
    @if($upcomingPeriod)
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6 text-left shadow-sm">
        <div class="flex items-center space-x-2 text-amber-800 font-bold text-xs mb-1">
            <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Jadwal Periode Berikutnya</span>
        </div>
        <p class="text-sm font-bold text-gray-800">{{ $upcomingPeriod->nama }}</p>
        <p class="text-xs text-amber-700 mt-1">
            Dibuka mulai: <strong>{{ $upcomingPeriod->tanggal_mulai->translatedFormat('d F Y, H:i') }} WIB</strong> s/d <strong>{{ $upcomingPeriod->tanggal_selesai->translatedFormat('d F Y, H:i') }} WIB</strong>
        </p>
        @if($upcomingPeriod->deskripsi)
        <p class="text-xs text-gray-500 mt-1 italic">{{ $upcomingPeriod->deskripsi }}</p>
        @endif
    </div>
    @elseif($latestClosed)
    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-6 text-left text-xs text-gray-600">
        <p class="font-semibold text-gray-700">Periode Terakhir:</p>
        <p class="text-gray-800 font-bold">{{ $latestClosed->nama }}</p>
        <p class="text-gray-500 mt-0.5">Telah berakhir pada {{ $latestClosed->tanggal_selesai->translatedFormat('d F Y, H:i') }} WIB.</p>
    </div>
    @endif

    <div class="pt-4 border-t border-gray-100 flex flex-wrap items-center justify-center gap-3">
        <a href="{{ route('mahasiswa.helpdesk') }}" class="btn-primary px-5 py-2.5 text-xs flex items-center space-x-2 shadow">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            <span>Hubungi Helpdesk</span>
        </a>
        <a href="{{ route('landing') }}" class="px-5 py-2.5 text-xs font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-xl transition">
            Lihat Informasi Publik
        </a>
    </div>
</div>

@else
    {{-- Step Tracker (Hanya tampil jika draft dan periode dibuka) --}}
    @if($user->status_lapor_diri === 'draft' && $isOpen)
    <div class="card p-5 mb-6">
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-widest mb-4">Progres Pengisian Formulir</p>
        <div class="flex items-center step-indicator">
            @foreach(['Data Pribadi', 'Domisili', 'Pendidikan', 'Keluarga', 'Ekonomi', 'Dokumen'] as $i => $step)
            <div class="step {{ $i === 0 ? 'active' : 'inactive' }}">{{ $i + 1 }}</div>
            @if($i < 5)<div class="step-line mx-1"></div>@endif
            @endforeach
        </div>
        <div class="flex justify-between text-xs text-gray-400 mt-2">
            @foreach(['Data Pribadi', 'Domisili', 'Pendidikan', 'Keluarga', 'Ekonomi', 'Dokumen'] as $step)
            <span class="text-center" style="width: 16.6%">{{ $step }}</span>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Main Form Card --}}
    <div class="card p-6 border-t-4 border-purple-600">
        @livewire('mahasiswa.form-lapor-diri')
    </div>
@endif

@endsection
