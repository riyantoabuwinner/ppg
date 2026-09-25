@extends('layouts.app')
@section('title', 'Verifikasi Dokumen - Admin')

@section('content')
    <!-- Panggil Livewire Component dan lempar id dari route -->
    @livewire('admin.verifikasi-dokumen', ['id' => $id])
@endsection
