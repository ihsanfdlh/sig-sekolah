
@extends('layouts.app')

@section('title', 'Profil ' . $sekolah->nama)

@section('content')
@php
    $profil = $sekolah->profil;

    $logo = $profil?->logo
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($profil->logo)
        : null;

    $informasi = [
        'NPSN' => $sekolah->npsn,
        'Jenjang' => $sekolah->jenjang,
        'Status' => $sekolah->status,
        'Kecamatan' => $sekolah->kecamatan,
        'Alamat' => $sekolah->alamat,
        'Kepala Sekolah' => $profil?->kepala_sekolah,
        'Akreditasi' => $profil?->akreditasi,
        'Tahun Berdiri' => $profil?->tahun_berdiri,
    ];
@endphp

<div class="mx-auto max-w-6xl space-y-6 pb-10">

    {{-- Navigasi --}}
    <div class="flex items-center justify-between gap-4">
        <a href="{{ route('peta.index') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:underline">
            ← Kembali ke Peta
        </a>

        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
            Profil Publik Sekolah
        </span>
    </div>

    {{-- Header sekolah --}}
    <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="h-28 bg-gradient-to-r from-blue-700 via-blue-600 to-cyan-500 sm:h-40"></div>

        <div class="px-5 pb-6 sm:px-8">
            <div class="-mt-12 flex flex-col gap-4 sm:-mt-14 sm:flex-row sm:items-end">

                <div class="flex h-28 w-28 shrink-0 items-center justify-center overflow-hidden rounded-2xl border-4 border-white bg-white shadow-md sm:h-32 sm:w-32">
                    @if ($logo)
                        <img src="{{ $logo }}"
                             alt="Logo {{ $sekolah->nama }}"
                             class="h-full w-full object-contain p-2">
                    @else
                        <span class="text-5xl">🏫</span>
                    @endif
                </div>

                <div class="min-w-0 flex-1 pb-1">
                    <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">
                        {{ $sekolah->nama }}
                    </h1>

                    <p class="mt-2 text-sm text-slate-500">
                        NPSN {{ $sekolah->npsn }}
                        • {{ $sekolah->jenjang }}
                        • {{ $sekolah->status }}
                    </p>
                </div>
            </div>
        </div>
    </section>

    {{-- Statistik --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Jumlah Siswa</p>
            <p class="mt-2 text-3xl font-bold text-blue-700">
                {{ $profil?->jumlah_siswa !== null
                    ? number_format($profil->jumlah_siswa, 0, ',', '.')
                    : '-' }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Jumlah Guru</p>
            <p class="mt-2 text-3xl font-bold text-blue-700">
                {{ $profil?->jumlah_guru !== null
                    ? number_format($profil->jumlah_guru, 0, ',', '.')
                    : '-' }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Akreditasi</p>
            <p class="mt-2 text-3xl font-bold text-blue-700">
                {{ $profil?->akreditasi ?: '-' }}
            </p>
        </div>

    </div>

    {{-- Informasi sekolah --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">

        <h2 class="mb-5 text-xl font-bold text-slate-900">
            Informasi Sekolah
        </h2>

        <dl class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            @foreach ($informasi as $label => $nilai)
                <div class="border-b border-slate-100 pb-3">
                    <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                        {{ $label }}
                    </dt>
                    <dd class="mt-1 break-words text-sm font-medium text-slate-800">
                        {{ $nilai !== null && $nilai !== '' ? $nilai : 'Belum tersedia' }}
                    </dd>
                </div>
            @endforeach
        </dl>

    </section>

    {{-- Deskripsi --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">

        <h2 class="mb-4 text-xl font-bold text-slate-900">
            Tentang Sekolah
        </h2>

        <p class="whitespace-pre-line text-sm leading-7 text-slate-600">
            {{ $profil?->deskripsi ?: 'Deskripsi sekolah belum tersedia.' }}
        </p>

    </section>

    {{-- Kontak --}}
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">

        <h2 class="mb-5 text-xl font-bold text-slate-900">
            Kontak dan Lokasi
        </h2>

        <div class="space-y-4 text-sm">

            <div>
                <p class="text-slate-500">Email</p>
                @if ($profil?->email)
                    <a href="mailto:{{ $profil->email }}"
                       class="font-medium text-blue-600 hover:underline">
                        {{ $profil->email }}
                    </a>
                @else
                    <p class="text-slate-700">Belum tersedia</p>
                @endif
            </div>

            <div>
                <p class="text-slate-500">Website</p>
                @if ($profil?->website && filter_var($profil->website, FILTER_VALIDATE_URL)
                    && in_array(parse_url($profil->website, PHP_URL_SCHEME), ['http', 'https'], true))
                    <a href="{{ $profil->website }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="break-all font-medium text-blue-600 hover:underline">
                        {{ $profil->website }}
                    </a>
                @else
                    <p class="text-slate-700">Belum tersedia</p>
                @endif
            </div>

            <div>
                <p class="text-slate-500">Alamat</p>
                <p class="font-medium text-slate-800">
                    {{ $sekolah->alamat ?: 'Belum tersedia' }}
                </p>
            </div>

            @if (is_numeric($sekolah->latitude) && is_numeric($sekolah->longitude))
                <a
                    href="https://www.google.com/maps/search/?api=1&query={{ urlencode($sekolah->latitude . ',' . $sekolah->longitude) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex rounded-lg bg-blue-600 px-5 py-3 font-semibold text-white hover:bg-blue-700">
                    Lihat Lokasi di Google Maps
                </a>
            @endif

        </div>
    </section>

</div>
@endsection
