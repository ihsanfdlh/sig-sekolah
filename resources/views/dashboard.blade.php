@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="dashboard-heading">
    <h1>Selamat datang, {{ $user->name }}</h1>
    <p>Ruang kerja {{ $user->roleLabel() }} untuk pengelolaan informasi pendidikan.</p>
</div>
@if ($user->isAdmin())
    <div class="grid gap-4 md:grid-cols-3">
        @foreach ($jumlahPerRole as $label => $jumlah)
            <div class="dashboard-card">
                <p class="text-3xl font-bold text-blue-800">{{ $jumlah }}</p>
                <p class="text-gray-600">Akun {{ $label }}</p>
            </div>
        @endforeach
    </div>
@elseif ($user->isDinas())
    <div class="dashboard-card w-full sm:max-w-xs">
        <p class="text-3xl font-bold text-blue-800">{{ $jumlahSekolah }}</p>
        <p class="text-gray-600">Sekolah terdata</p>
    </div>
@elseif ($sekolah)
    <div class="dashboard-profile-card">
        <p class="text-xl font-bold">{{ $sekolah->nama }}</p>
        <p class="text-gray-600">NPSN {{ $sekolah->npsn }} - {{ $sekolah->jenjang }} {{ $sekolah->status }}</p>
        <p class="mt-4">Kelengkapan profil:
        <strong>{{ $kelengkapan }}%</strong></p>
        <div class="w-full bg-gray-200 rounded h-3 mt-1">
            <div class="bg-blue-700 h-3 rounded"
                style="width: {{ $kelengkapan }}%"></div>
        </div>
    </div>
@else
    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-amber-900">
        Akun Anda belum ditautkan ke sekolah. Hubungi admin.
    </div>
@endif
@endsection