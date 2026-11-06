
@extends('layouts.app')

@section('title', 'Ubah Sekolah')

@section('content')
<div class="mx-auto w-full max-w-7xl space-y-6">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <div class="mb-2 flex items-center gap-2 text-sm text-slate-500">
                <a href="{{ route('dashboard') }}" class="transition hover:text-blue-600">Dashboard</a>
                <span>/</span>
                <span class="font-medium text-slate-700">Ubah Sekolah</span>
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">Ubah Sekolah</h1>
            <p class="mt-1 text-sm text-slate-500">
                Perbarui informasi, identitas, dan titik lokasi {{ $sekolah->nama }}.
            </p>
        </div>
        <a href="{{ route('dashboard') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
            <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/>
            </svg>
            Kembali
        </a>
    </div>

    <div class="w-full min-w-0">
        @include('admin.sekolah._tab', ['sekolah' => $sekolah])
    </div>

    <form method="POST" action="{{ route('sekolah.update', $sekolah) }}" class="w-full min-w-0">
        @csrf
        @method('PUT')
        @include('admin.sekolah._form', ['sekolah' => $sekolah])
    </form>
</div>
@endsection
