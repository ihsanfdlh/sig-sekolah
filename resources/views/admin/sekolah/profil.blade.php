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
            'block w-full rounded-xl border border-[#C9DDE8] bg-white px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-[#5F91AC] focus:ring-2 focus:ring-[#DCECF5]';
    @endphp
    <div id="profil-edit-page" class="relative mx-auto w-full max-w-7xl space-y-6">
        <div id="profil-edit-skeleton" aria-hidden="true"
            class="absolute inset-0 z-30 rounded-2xl bg-[#F7FAFC] transition-opacity duration-[400ms]">
            <div class="mb-6 flex items-center justify-between gap-4">
                <div class="space-y-3">
                    <div class="sk-line h-4 w-32 rounded-lg"></div>
                    <div class="sk-line h-8 w-64 max-w-full rounded-lg"></div>
                    <div class="sk-line h-4 w-52 rounded-lg"></div>
                </div>
                <div class="sk-line h-10 w-24 rounded-xl"></div>
            </div>
            <div class="mb-6 flex gap-3">
                <div class="sk-line h-11 w-36 rounded-xl"></div>
                <div class="sk-line h-11 w-36 rounded-xl"></div>
            </div>
            <div class="overflow-hidden rounded-2xl border border-[#D6E5ED] bg-white p-6">
                <div class="sk-line mb-3 h-6 w-56 rounded-lg"></div>
                <div class="sk-line mb-8 h-4 w-80 max-w-full rounded-lg"></div>
                <div class="grid gap-5 md:grid-cols-2">
                    @for ($i = 0; $i < 8; $i++)
                        <div class="space-y-2">
                            <div class="sk-line h-4 w-28 rounded-lg"></div>
                            <div class="sk-line h-12 w-full rounded-xl"></div>
                        </div>
                    @endfor
                </div>
                <div class="mt-8 sk-line h-28 w-full rounded-xl"></div>
                <div class="mt-8 sk-line h-32 w-full rounded-xl"></div>
            </div>
        </div>
        <!-- Header Halaman -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="mb-2 flex items-center gap-2 text-sm text-[#607C91]">
                    <a href="{{ route('dashboard') }}" class="transition hover:text-[#4B7D98]">Dashboard</a>
                    <span>/</span>
                    <span class="font-medium text-[#496B82]">Profil Sekolah</span>
                </div>
                <h1 class="text-2xl font-bold tracking-tight text-[#294B63] sm:text-3xl">{{ $sekolah->nama }}</h1>
                <p class="mt-1 text-sm text-[#607C91]">
                    NPSN: {{ $sekolah->npsn }} • Lengkapi informasi profil sekolah.
                </p>
            </div>
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-[#C9DDE8] bg-white px-4 py-2.5 text-sm font-medium text-[#496B82] transition hover:bg-slate-50">
                <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6" />
                </svg>
                Kembali
            </a>
        </div>
        <!-- Navigasi Halaman -->
        <div class="w-full min-w-0">
            @include('admin.sekolah._tab', ['sekolah' => $sekolah])
        </div>
        <!-- Pesan Berhasil -->
        @if (session('success'))
            <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif
        <!-- Pesan Validasi -->
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
        <!-- Form Profil Sekolah -->
        <form method="POST" action="{{ route('sekolah.profil.update', $sekolah) }}" enctype="multipart/form-data"
            class="w-full min-w-0 overflow-hidden rounded-2xl border border-[#D6E5ED] bg-white/90 shadow-[0_10px_30px_rgba(42,83,108,.07)] backdrop-blur-xl">
            @csrf
            @method('PUT')
            <!-- Header Form -->
            <div class="border-b border-[#D6E5ED] px-5 py-5 sm:px-7">
                <h2 class="text-lg font-bold text-[#294B63]">Informasi Tambahan Sekolah</h2>
                <p class="mt-1 text-sm text-[#607C91]">
                    Masukkan data profil sekolah secara lengkap dan sesuai kondisi terbaru.
                </p>
            </div>
            <!-- Isi Form -->
            <div class="space-y-8 p-5 sm:p-7">
                <!-- Identitas Profil -->
                <section>
                    <div class="mb-5">
                        <h3 class="text-base font-bold text-[#294B63]">Identitas dan Statistik Sekolah</h3>
                        <p class="mt-1 text-sm text-[#607C91]">
                            Informasi kontak, pimpinan, dan jumlah warga sekolah.
                        </p>
                    </div>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-5 md:grid-cols-2">
                        @foreach ($teks as $name => [$label, $type])
                            <div class="min-w-0">
                                <label for="{{ $name }}" class="mb-2 block text-sm font-semibold text-[#496B82]">
                                    {{ $label }}
                                </label>
                                <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}"
                                    value="{{ old($name, $profil->{$name} ?? '') }}"
                                    @if ($name === 'tahun_berdiri') min="1800" max="{{ date('Y') }}"
                                @elseif ($type === 'number')
                                    min="0" @endif
                                    class="{{ $cls }} @error($name) border-red-500 @enderror">
                                @error($name)
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                        <!-- Akreditasi -->
                        <div class="min-w-0">
                            <label for="akreditasi" class="mb-2 block text-sm font-semibold text-[#496B82]">
                                Akreditasi
                            </label>
                            <select id="akreditasi" name="akreditasi"
                                class="{{ $cls }} @error('akreditasi') border-red-500 @enderror">
                                <option value="">-- Pilih Akreditasi --</option>
                                @foreach (['A', 'B', 'C', 'Belum Terakreditasi'] as $o)
                                    <option value="{{ $o }}" @selected(old('akreditasi', $profil->akreditasi ?? '') === $o)>
                                        {{ $o }}
                                    </option>
                                @endforeach
                            </select>
                            @error('akreditasi')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>
                <!-- Logo Sekolah -->
                <section class="border-t border-[#D6E5ED] pt-7">
                    <div class="mb-5">
                        <h3 class="text-base font-bold text-[#294B63]">Logo Sekolah</h3>
                        <p class="mt-1 text-sm text-[#607C91]">
                            Unggah logo resmi untuk ditampilkan pada profil sekolah.
                        </p>
                    </div>
                    <div
                        class="flex flex-col gap-5 rounded-xl border border-[#D6E5ED] bg-[#F0F7FB]/70 p-5 sm:flex-row sm:items-center">
                        <div
                            class="flex h-28 w-28 shrink-0 items-center justify-center overflow-hidden rounded-xl border border-[#D6E5ED] bg-white p-2">
                            @if ($profil?->logo)
                                <img src="{{ Storage::disk('public')->url($profil->logo) }}"
                                    alt="Logo {{ $sekolah->nama }}" class="h-full w-full object-contain">
                            @else
                                <span class="text-center text-xs text-slate-400">Belum ada logo</span>
                            @endif
                        </div>
                        <div class="w-full min-w-0 flex-1">
                            <label for="logo" class="mb-2 block text-sm font-semibold text-[#496B82]">
                                Unggah Logo
                            </label>
                            <input type="file" id="logo" name="logo" accept=".jpg,.jpeg,.png,.webp"
                                class="block w-full rounded-lg border border-[#C9DDE8] bg-white p-3 text-sm text-[#496B82]">
                            <p class="mt-2 text-xs text-[#607C91]">
                                Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                            </p>
                            @error('logo')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>
                <!-- Deskripsi Sekolah -->
                <section class="border-t border-[#D6E5ED] pt-7">
                    <div class="mb-5">
                        <h3 class="text-base font-bold text-[#294B63]">Deskripsi Sekolah</h3>
                        <p class="mt-1 text-sm text-[#607C91]">
                            Tuliskan gambaran umum mengenai sekolah.
                        </p>
                    </div>
                    <label for="deskripsi" class="mb-2 block text-sm font-semibold text-[#496B82]">
                        Deskripsi Sekolah
                    </label>
                    <textarea id="deskripsi" name="deskripsi" rows="5"
                        class="{{ $cls }} resize-y @error('deskripsi') border-red-500 @enderror"
                        placeholder="Tuliskan deskripsi singkat mengenai sekolah...">{{ old('deskripsi', $profil->deskripsi ?? '') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </section>
            </div>
            <!-- Footer Form -->
            <div
                class="flex flex-wrap items-center justify-end gap-3 border-t border-[#D6E5ED] bg-[#F0F7FB]/70 px-5 py-4 sm:px-7">
                <a href="{{ route('dashboard') }}"
                    class="inline-flex h-10 items-center justify-center rounded-lg border border-[#C9DDE8] bg-white px-5 text-sm font-semibold text-[#496B82] transition hover:bg-slate-100">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-[#5F91AC] px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-[#4B7D98]">
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
    @push('styles')
        <style>
            @keyframes profilShimmer {
                0% {
                    background-position: 200% 0
                }

                100% {
                    background-position: -200% 0
                }
            }

            #profil-edit-skeleton .sk-line {
                background: linear-gradient(90deg, #E6F0F6 20%, #F7FBFE 45%, #E6F0F6 70%);
                background-size: 200% 100%;
                animation: profilShimmer 1.35s linear infinite
            }

            @media(prefers-reduced-motion:reduce) {
                #profil-edit-skeleton .sk-line {
                    animation: none
                }
            }
        </style>
    @endpush
    @push('scripts')
        <script>
            (function() {
                const started = Date.now();
                const minimumDuration = 1800;
                const skeleton = document.getElementById('profil-edit-skeleton');
                if (!skeleton) return;
                let done = false;

                function hide() {
                    if (done) return;
                    done = true;
                    setTimeout(function() {
                        skeleton.classList.add('opacity-0', 'pointer-events-none');
                        setTimeout(function() {
                            skeleton.remove()
                        }, 400)
                    }, Math.max(0, minimumDuration - (Date.now() - started)))
                }
                if (document.readyState === 'complete') {
                    hide()
                } else {
                    window.addEventListener('load', hide, {
                        once: true
                    })
                }
                setTimeout(hide, 8000)
            })();
        </script>
    @endpush
@endsection
