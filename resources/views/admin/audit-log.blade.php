@extends('layouts.app')
@section('title', 'Log Aktivitas — Admin')
@section('page-title', 'Log Aktivitas (Audit Trail)')
@section('page-subtitle', 'Rekam jejak seluruh aktivitas yang terjadi dalam sistem')

@section('content')
@livewire('admin.audit-log')
@endsection
