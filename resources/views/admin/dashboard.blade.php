@extends('layouts.app')
@section('title', 'Dashboard Admin — Lapor Diri PPG')
@section('page-title', 'Dashboard Administrator')
@section('page-subtitle', 'Monitoring & Manajemen Lapor Diri PPG')

@section('content')
@php
    use App\Models\User;
    use App\Models\Ticket;
    $total = User::where('role','mahasiswa')->count();
    $draft = User::where('role','mahasiswa')->where('status_lapor_diri','draft')->count();
    $submitted = User::where('role','mahasiswa')->where('status_lapor_diri','submitted')->count();
    $verified = User::where('role','mahasiswa')->where('status_lapor_diri','verified')->count();
    $openTickets = Ticket::where('status','open')->count();
    $pct = $total > 0 ? round(($verified / $total) * 100) : 0;
@endphp

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl stat-card flex items-center justify-center text-white shadow">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <span class="text-2xl font-black text-gray-800">{{ $total }}</span>
        </div>
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Peserta</p>
    </div>
    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl stat-card-gold flex items-center justify-center text-white shadow">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-2xl font-black text-gray-800">{{ $submitted }}</span>
        </div>
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Menunggu Verifikasi</p>
    </div>
    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl stat-card-green flex items-center justify-center text-white shadow">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span class="text-2xl font-black text-gray-800">{{ $verified }}</span>
        </div>
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Terverifikasi</p>
    </div>
    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <div class="w-10 h-10 rounded-xl stat-card-teal flex items-center justify-center text-white shadow">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            </div>
            <span class="text-2xl font-black text-gray-800">{{ $openTickets }}</span>
        </div>
        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tiket Open</p>
    </div>
</div>

{{-- Charts Row --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Progress bar & Pie Chart --}}
    <div class="card p-6">
        <h3 class="font-bold text-gray-700 mb-1 text-sm">Progres Lapor Diri</h3>
        <p class="text-xs text-gray-400 mb-4">Persentase capaian keseluruhan</p>
        <div class="flex items-center justify-center mb-4">
            <canvas id="statusChart" width="200" height="200"></canvas>
        </div>
        <div class="space-y-2 text-xs">
            <div class="flex justify-between items-center"><span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-purple-600 inline-block mr-2"></span>Terverifikasi</span><span class="font-bold text-gray-700">{{ $verified }}</span></div>
            <div class="flex justify-between items-center"><span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-amber-400 inline-block mr-2"></span>Menunggu</span><span class="font-bold text-gray-700">{{ $submitted }}</span></div>
            <div class="flex justify-between items-center"><span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-gray-200 inline-block mr-2"></span>Draft</span><span class="font-bold text-gray-700">{{ $draft }}</span></div>
        </div>
    </div>

    {{-- Export Buttons Card --}}
    <div class="card p-6 flex flex-col justify-between">
        <div>
            <h3 class="font-bold text-gray-700 mb-1 text-sm">Ekspor Data</h3>
            <p class="text-xs text-gray-400 mb-4">Unduh format siap-import untuk sistem pusat</p>
            <div class="bg-purple-50 rounded-xl p-4 mb-3 border border-purple-100">
                <div class="font-semibold text-purple-800 text-sm mb-1">Format PDDIKTI</div>
                <p class="text-xs text-purple-600 mb-3">Excel 50 kolom standar PDDIKTI. Cocok untuk import Feeder PDDIKTI.</p>
                <a href="{{ route('admin.export.pddikti') }}" class="btn-primary text-xs px-4 py-2 inline-block">
                    Unduh PDDIKTI (.xlsx)
                </a>
            </div>
            <div class="bg-green-50 rounded-xl p-4 border border-green-100">
                <div class="font-semibold text-green-800 text-sm mb-1">Format SIAKAD</div>
                <p class="text-xs text-green-600 mb-3">Excel 79 kolom + translasi kode. Siap import ke SIAKAD UINSSC.</p>
                <a href="{{ route('admin.export.siakad') }}" class="bg-green-600 hover:bg-green-700 text-white text-xs px-4 py-2 rounded-lg font-semibold inline-block shadow transition">
                    Unduh SIAKAD (.xls)
                </a>
            </div>
        </div>
    </div>

    {{-- Quick Info --}}
    <div class="card p-6">
        <h3 class="font-bold text-gray-700 mb-1 text-sm">Ringkasan Aktivitas</h3>
        <p class="text-xs text-gray-400 mb-4">Pencapaian per hari ini</p>
        <div class="mb-4">
            <div class="flex justify-between text-xs text-gray-600 mb-1.5">
                <span>Capaian Verifikasi</span>
                <span class="font-bold text-purple-700">{{ $pct }}%</span>
            </div>
            <div class="w-full h-2.5 bg-purple-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full bg-gradient-to-r from-purple-500 to-purple-700 transition-all duration-700" style="width: {{ $pct }}%"></div>
            </div>
        </div>
        <div class="space-y-3 text-sm">
            @php $logs = \Spatie\Activitylog\Models\Activity::latest()->limit(5)->get(); @endphp
            @forelse($logs as $log)
            <div class="flex items-start space-x-2 text-xs text-gray-600">
                <div class="w-1.5 h-1.5 rounded-full bg-purple-400 mt-1.5 flex-shrink-0"></div>
                <div><span class="font-medium">{{ $log->causer?->name ?? 'Sistem' }}</span> — {{ $log->description }}</div>
            </div>
            @empty
            <p class="text-xs text-gray-400 text-center py-4">Belum ada aktivitas tercatat.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Master Data Quick Access Banner --}}
<div class="card p-6 border border-purple-100 bg-gradient-to-r from-purple-50/80 via-white to-purple-50/40">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-600 to-purple-800 text-white flex items-center justify-center shadow-lg shadow-purple-500/25 flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <div>
                <div class="flex items-center space-x-2">
                    <h3 class="font-bold text-gray-800 text-base">Master Data Mahasiswa Lapor Diri</h3>
                    <span class="bg-purple-100 text-purple-700 font-bold text-xs px-2.5 py-0.5 rounded-full">{{ $total }} Mahasiswa</span>
                </div>
                <p class="text-xs text-gray-500 mt-1">Kelola direktori mahasiswa, pencarian data, filter status, dan verifikasi berkas lapor diri.</p>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.master-data') }}" class="btn-primary text-xs px-5 py-2.5 flex items-center space-x-2">
                <span>Buka Master Data</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </a>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Terverifikasi', 'Menunggu', 'Draft'],
            datasets: [{
                data: [{{ $verified }}, {{ $submitted }}, {{ $draft > 0 ? $draft : 0 }}],
                backgroundColor: ['#7C3AED', '#F59E0B', '#E5E7EB'],
                borderWidth: 0,
                hoverOffset: 6,
            }]
        },
        options: {
            cutout: '72%',
            plugins: { legend: { display: false }, tooltip: { callbacks: {
                label: (ctx) => ` ${ctx.label}: ${ctx.raw} peserta`
            }}},
        }
    });
</script>
@endsection
