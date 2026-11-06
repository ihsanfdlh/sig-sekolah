@extends('layouts.app')
@section('title', 'Profil Sekolah')
@section('content')
    @php
        $teks = [
            'kepala_sekolah' => ['Kepala Sekolah', 'text'],
            'email' => ['Email Sekolah', 'email'],
            'website' => ['Website Sekolah (https://)', 'url'],
            'tahun_berdiri' => ['Tahun Berdiri', 'number'],
            'jumlah_siswa' => ['Jumlah Siswa', 'number'],
            'jumlah_guru' => ['Jumlah Guru', 'number'],
        ];
        $cls =
            'w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100';
    @endphp
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="mb-2 flex items-center gap-2 text-sm text-slate-500">
                    <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
                    <span>/</span>
                    <span class="font-medium text-slate-700">Profil Sekolah</span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">{{ $sekolah->nama }}</h1>
                <p class="mt-1 text-sm text-slate-500">NPSN: {{ $sekolah->npsn }} • Lengkapi informasi profil sekolah.</p>
            </div>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                </svg>
                Kembali
            </a>
        </div>
        @include('admin.sekolah._tab', ['sekolah' => $sekolah])
        @if (session('success'))
    <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
        <p class="font-semibold">Gagal menyimpan profil sekolah:</p>

        <ul class="mt-2 list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <form method="POST" action="{{ route('sekolah.profil.update', $sekolah) }}"
            class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-6 border-b border-slate-100 pb-4">
                <h2 class="text-lg font-bold text-slate-900">Informasi Tambahan Sekolah</h2>
                <p class="mt-1 text-sm text-slate-500">Masukkan data profil sekolah secara lengkap dan sesuai kondisi
                    terbaru.</p>
            </div>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                @foreach ($teks as $name => [$label, $type])
                    <div>
                        <label for="{{ $name }}"
                            class="mb-1.5 block text-sm font-semibold text-slate-700">{{ $label }}</label>
                        <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}"
                            value="{{ old($name, $profil->{$name} ?? '') }}"
                            @if ($name === 'tahun_berdiri')
    min="1800" max="{{ date('Y') }}"
@elseif ($type === 'number')
    min="0"
@endif
                            class="{{ $cls }} @error($name) border-red-500 @enderror">
                        @error($name)
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach
                <div class="rounded-xl border border-slate-200 bg-white p-5">
                    <h3 class="mb-4 text-lg font-semibold text-slate-800">
                        Logo Sekolah
                    </h3>
                    @if ($profil?->logo)
                        <div class="mb-4">
                            <img src="{{ Storage::disk('public')->url($profil->logo) }}" alt="Logo {{ $sekolah->nama }}"
                                class="h-28 w-28 rounded-xl border border-slate-200 bg-white object-contain p-2">
                        </div>
                    @endif
                    <label for="logo" class="mb-2 block text-sm font-medium text-slate-700">
                        Unggah Logo
                    </label>
                    <input type="file" id="logo" name="logo" accept=".jpg,.jpeg,.png,.webp"
                        class="block w-full rounded-lg border border-slate-300 p-3 text-sm">
                    <p class="mt-2 text-xs text-slate-500">
                        Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                    </p>
                    @error('logo')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="akreditasi" class="mb-1.5 block text-sm font-semibold text-slate-700">Akreditasi</label>
                    <select id="akreditasi" name="akreditasi"
                        class="{{ $cls }} @error('akreditasi') border-red-500 @enderror">
                        <option value="">-- Pilih Akreditasi --</option>
                        @foreach (['A', 'B', 'C', 'Belum Terakreditasi'] as $o)
                            <option value="{{ $o }}" @selected(old('akreditasi', $profil->akreditasi ?? '') === $o)>{{ $o }}</option>
                        @endforeach
                    </select>
                    @error('akreditasi')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="md:col-span-2">
                    <label for="deskripsi" class="mb-1.5 block text-sm font-semibold text-slate-700">Deskripsi
                        Sekolah</label>
                    <textarea id="deskripsi" name="deskripsi" rows="5"
                        class="{{ $cls }} @error('deskripsi') border-red-500 @enderror"
                        placeholder="Tuliskan deskripsi singkat mengenai sekolah...">{{ old('deskripsi', $profil->deskripsi ?? '') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            <div class="mt-7 flex flex-wrap items-center justify-end gap-3 border-t border-slate-200 pt-5">
                <a href="{{ route('dashboard') }}"
                    class="rounded-lg border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Batal</a>
                <button type="submit"
                    class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                    <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l4 4v12a2 2 0 0 1-2 2z" />
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="17 21 17 13 7 13 7 21" />
                        <polyline stroke-linecap="round" stroke-linejoin="round" points="7 3 7 8 15 8" />
                    </svg>
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>
@endsection
