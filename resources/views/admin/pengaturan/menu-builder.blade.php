@extends('layouts.app')
@section('title', 'Visual Menu Builder — ' . \App\Models\AppSetting::get('app_name', 'PPG'))
@section('page-title', 'Visual Menu Builder')
@section('page-subtitle', 'Drag and drop item menu untuk mengatur posisi urutan dan hirarki sub-menu')
@section('content')
@livewire('admin.menu-builder', ['menuId' => $menu])
@endsection
