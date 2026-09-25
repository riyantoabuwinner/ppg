@extends('layouts.app')

@section('title', 'Manajemen Kontak & Sosial Media - Admin PPG')

@section('header')
    <h2 class="text-xl font-bold text-gray-800 leading-tight">Manajemen Kontak & Sosial Media</h2>
    <p class="text-sm text-gray-500 mt-1">Kelola informasi kontak, alamat, dan seluruh akun sosial media institusi.</p>
@endsection

@section('content')
    <livewire:admin.manajemen-kontak />
@endsection
