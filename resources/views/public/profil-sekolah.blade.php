@extends('layouts.app')
@section('title', 'Profil ' . $sekolah->nama)
@section('content')
    <style>
        #profil-skeleton {
            position: absolute;
            inset: 0;
            z-index: 50;
            pointer-events: none;
            opacity: 1;
            transition: opacity .4s ease
        }

        #profil-skeleton.is-hidden {
            opacity: 0
        }

        #profil-skeleton .sk-block {
            position: absolute;
            overflow: hidden;
            border-radius: 10px;
            background: #DFEAF1
        }

        #profil-skeleton .sk-block::after {
            content: "";
            position: absolute;
            inset: 0;
            transform: translateX(-100%);
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .75), transparent);
            animation: profil-shimmer 1.25s infinite
        }

        @keyframes profil-shimmer {
            100% {
                transform: translateX(100%)
            }
        }

        @media(prefers-reduced-motion:reduce) {
            #profil-skeleton .sk-block::after {
                animation: none
            }
        }
    </style>
    @php
        $profil = $sekolah->profil;
        $logo = $profil?->logo ? \Illuminate\Support\Facades\Storage::disk('public')->url($profil->logo) : null;
        $tutWuri = asset('storage/Assets/tut-wuri-handayani.png');
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
        $websiteValid =
            $profil?->website &&
            filter_var($profil->website, FILTER_VALIDATE_URL) &&
            in_array(parse_url($profil->website, PHP_URL_SCHEME), ['http', 'https'], true);
        $punyaKoordinat =
            is_numeric($sekolah->latitude) &&
            is_numeric($sekolah->longitude) &&
            $sekolah->latitude >= -90 &&
            $sekolah->latitude <= 90 &&
            $sekolah->longitude >= -180 &&
            $sekolah->longitude <= 180;
    @endphp
    <div id="profil-sekolah-content" class="relative mx-auto w-full max-w-6xl space-y-6 pb-12">
        {{-- Navigasi --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <a href="{{ route('peta.index') }}"
                class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 transition hover:text-[#416D86]">
                <span aria-hidden="true">&larr;</span>
                Kembali ke Peta
            </a>
            <span class="rounded-full border border-[#CDE2EE] bg-[#E1EFF6] px-3 py-1.5 text-xs font-semibold text-[#416D86]">
                Profil Publik Sekolah
            </span>
        </div>
        {{-- Header Sekolah --}}
        <section
            class="overflow-hidden rounded-2xl border border-[#D6E5ED] bg-white/90 shadow-[0_12px_35px_rgba(41,75,99,0.08)] backdrop-blur-md">
            {{-- Banner --}}
            <div class="relative h-36 overflow-hidden bg-gradient-to-r from-[#294B63] via-[#5F91AC] to-[#A3C4D4] sm:h-44">
                {{-- Ornamen --}}
                <div class="pointer-events-none absolute -right-20 -top-28 h-72 w-72 rounded-full border border-white/10">
                </div>
                <div class="pointer-events-none absolute -bottom-24 right-28 h-48 w-48 rounded-full bg-white/5"></div>
                {{-- Watermark Tut Wuri Handayani --}}
                <div class="pointer-events-none absolute right-5 top-1/2 -translate-y-1/2 sm:right-12">
                    <img src="{{ $tutWuri }}" alt="" aria-hidden="true"
                        class="h-32 w-32 object-contain opacity-20 grayscale brightness-0 invert sm:h-44 sm:w-44">
                </div>
                {{-- Tulisan Banner --}}
                <div class="absolute left-6 top-6 right-40 sm:left-8 sm:top-8 sm:right-56">
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#E1EFF6]">
                        Sistem Informasi Geografis
                    </p>
                    <p class="mt-2 text-sm text-white/80">
                        Profil Satuan Pendidikan
                    </p>
                </div>
            </div>
            {{-- Identitas Sekolah --}}
            <div class="relative px-5 pb-7 sm:px-8">
                {{-- Logo Sekolah --}}
                <div
                    class="relative z-10 -mt-12 mb-5 flex h-24 w-24 items-center justify-center overflow-hidden rounded-2xl border-4 border-white bg-white shadow-md sm:h-28 sm:w-28">
                    @if ($logo)
                        <img src="{{ $logo }}" alt="Logo {{ $sekolah->nama }}"
                            class="h-full w-full object-contain p-2">
                    @else
                        <div
                            class="flex h-full w-full items-center justify-center bg-[#E1EFF6] text-3xl font-bold text-[#416D86]">
                            {{ strtoupper(substr($sekolah->jenjang ?: 'S', 0, 1)) }}
                        </div>
                    @endif
                </div>
                {{-- Nama Sekolah --}}
                <div class="relative z-10">
                    <h1 class="break-words text-2xl font-bold leading-tight tracking-tight text-slate-900 sm:text-3xl">
                        {{ $sekolah->nama }}
                    </h1>
                    <p class="mt-2 text-sm text-slate-500">
                        NPSN {{ $sekolah->npsn }}
                        <span class="mx-2 text-slate-300">|</span>
                        {{ $sekolah->kecamatan ?: 'Kecamatan belum tersedia' }}
                    </p>
                    {{-- Jenjang, Status, Akreditasi --}}
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="rounded-md bg-[#E1EFF6] px-3 py-1 text-xs font-semibold text-[#416D86]">
                            {{ $sekolah->jenjang }}
                        </span>
                        <span class="rounded-md bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                            {{ $sekolah->status }}
                        </span>
                        @if ($profil?->akreditasi)
                            <span class="rounded-md bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                Akreditasi {{ $profil->akreditasi }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        {{-- Statistik Sekolah --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
            {{-- Jumlah Siswa --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Jumlah Siswa
                </p>
                <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                    {{ $profil?->jumlah_siswa !== null ? number_format($profil->jumlah_siswa, 0, ',', '.') : '-' }}
                </p>
                <p class="mt-1 text-xs text-slate-400">
                    Peserta didik
                </p>
            </div>
            {{-- Jumlah Guru --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Jumlah Guru
                </p>
                <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                    {{ $profil?->jumlah_guru !== null ? number_format($profil->jumlah_guru, 0, ',', '.') : '-' }}
                </p>
                <p class="mt-1 text-xs text-slate-400">
                    Tenaga pengajar
                </p>
            </div>
            {{-- Akreditasi --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">
                    Akreditasi
                </p>
                <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                    {{ $profil?->akreditasi ?: '-' }}
                </p>
                <p class="mt-1 text-xs text-slate-400">
                    Status akreditasi sekolah
                </p>
            </div>
        </div>
        {{-- Informasi Sekolah --}}
        <section
            class="overflow-hidden rounded-2xl border border-[#D6E5ED] bg-white/90 shadow-[0_12px_35px_rgba(41,75,99,0.08)] backdrop-blur-md">
            <div class="border-b border-slate-100 px-5 py-5 sm:px-7">
                <h2 class="text-lg font-bold text-slate-900">
                    Informasi Sekolah
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Identitas dan informasi umum satuan pendidikan.
                </p>
            </div>
            <dl class="grid grid-cols-1 gap-x-10 px-5 py-2 sm:grid-cols-2 sm:px-7">
                @foreach ($informasi as $label => $nilai)
                    <div class="min-w-0 border-b border-slate-100 py-4">
                        <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            {{ $label }}
                        </dt>
                        <dd class="mt-1.5 break-words text-sm font-medium leading-6 text-slate-800">
                            {{ $nilai !== null && $nilai !== '' ? $nilai : 'Belum tersedia' }}
                        </dd>
                    </div>
                @endforeach
            </dl>
        </section>
        {{-- Tentang Sekolah --}}
        <section
            class="overflow-hidden rounded-2xl border border-[#D6E5ED] bg-white/90 shadow-[0_12px_35px_rgba(41,75,99,0.08)] backdrop-blur-md">
            <div class="border-b border-slate-100 px-5 py-5 sm:px-7">
                <h2 class="text-lg font-bold text-slate-900">
                    Tentang Sekolah
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Gambaran umum dan informasi tambahan mengenai sekolah.
                </p>
            </div>
            <div class="px-5 py-6 sm:px-7">
                <p class="whitespace-pre-line break-words text-sm leading-8 text-slate-600">
                    {{ $profil?->deskripsi ?: 'Deskripsi sekolah belum tersedia.' }}
                </p>
            </div>
        </section>
        {{-- Kontak dan Lokasi --}}
        <section
            class="overflow-hidden rounded-2xl border border-[#D6E5ED] bg-white/90 shadow-[0_12px_35px_rgba(41,75,99,0.08)] backdrop-blur-md">
            <div class="border-b border-slate-100 px-5 py-5 sm:px-7">
                <h2 class="text-lg font-bold text-slate-900">
                    Kontak dan Lokasi
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Informasi kontak resmi dan lokasi sekolah.
                </p>
            </div>
            <div class="grid grid-cols-1 gap-8 p-5 sm:p-7 lg:grid-cols-2">
                {{-- Informasi Kontak --}}
                <div>
                    <h3 class="mb-5 text-sm font-bold text-slate-900">
                        Informasi Kontak
                    </h3>
                    <dl class="space-y-5">
                        {{-- Email --}}
                        <div class="border-b border-slate-100 pb-4">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Email
                            </dt>
                            <dd class="mt-2">
                                @if ($profil?->email)
                                    <a href="mailto:{{ $profil->email }}"
                                        class="break-all text-sm font-medium text-[#416D86] hover:underline">
                                        {{ $profil->email }}
                                    </a>
                                @else
                                    <span class="text-sm text-slate-700">
                                        Belum tersedia
                                    </span>
                                @endif
                            </dd>
                        </div>
                        {{-- Website --}}
                        <div class="border-b border-slate-100 pb-4">
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Website
                            </dt>
                            <dd class="mt-2">
                                @if ($websiteValid)
                                    <a href="{{ $profil->website }}" target="_blank" rel="noopener noreferrer"
                                        class="break-all text-sm font-medium text-[#416D86] hover:underline">
                                        {{ $profil->website }}
                                    </a>
                                @else
                                    <span class="text-sm text-slate-700">
                                        Belum tersedia
                                    </span>
                                @endif
                            </dd>
                        </div>
                        {{-- Telepon --}}
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Telepon
                            </dt>
                            <dd class="mt-2 text-sm font-medium text-slate-800">
                                {{ $sekolah->telepon ?: 'Belum tersedia' }}
                            </dd>
                        </div>
                    </dl>
                </div>
                {{-- Lokasi Sekolah --}}
                <div>
                    <h3 class="mb-5 text-sm font-bold text-slate-900">
                        Lokasi Sekolah
                    </h3>
                    <div class="rounded-xl border border-slate-200 bg-[#F2F8FC] p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                            Alamat Lengkap
                        </p>
                        <p class="mt-2 text-sm font-medium leading-7 text-slate-800">
                            {{ $sekolah->alamat ?: 'Alamat belum tersedia' }}
                        </p>
                        @if ($punyaKoordinat)
                            {{-- Koordinat --}}
                            <div class="mt-5 grid grid-cols-2 gap-3">
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs text-slate-500">
                                        Latitude
                                    </p>
                                    <p class="mt-1 break-all text-xs font-semibold text-slate-800 sm:text-sm">
                                        {{ $sekolah->latitude }}
                                    </p>
                                </div>
                                <div class="rounded-lg border border-slate-200 bg-white p-3">
                                    <p class="text-xs text-slate-500">
                                        Longitude
                                    </p>
                                    <p class="mt-1 break-all text-xs font-semibold text-slate-800 sm:text-sm">
                                        {{ $sekolah->longitude }}
                                    </p>
                                </div>
                            </div>
                            {{-- Tombol Google Maps --}}
                            <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($sekolah->latitude . ',' . $sekolah->longitude) }}"
                                target="_blank" rel="noopener noreferrer"
                                class="mt-5 inline-flex w-full items-center justify-center rounded-lg bg-[#5F91AC] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#4B7D98]">
                                Lihat Lokasi di Google Maps
                            </a>
                        @else
                            <p class="mt-4 text-xs text-slate-500">
                                Koordinat lokasi belum tersedia.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </section>
        {{-- Footer --}}
        <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-200 pt-5">
            <p class="text-xs text-slate-500">
                Informasi profil dikelola oleh pihak sekolah.
            </p>
            <a href="{{ route('peta.index') }}" class="text-sm font-semibold text-[#416D86] transition hover:underline">
                Kembali ke Peta
            </a>
        </div>
    </div>
    <script>
        (function() {
            const started = Date.now();
            const minimumDuration = 1800;
            const maximumDuration = 8000;
            const root = document.getElementById('profil-sekolah-content');
            if (!root) return;
            const overlay = document.createElement('div');
            overlay.id = 'profil-skeleton';
            overlay.setAttribute('aria-hidden', 'true');
            root.prepend(overlay);
            const selectors = ['section', 'section h1', 'section h2', 'section h3', 'section p', 'section img',
                'section dt', 'section dd', 'section a', 'section span', 'section .grid > div', '.grid > div', 'a'
            ];

            function render() {
                if (!overlay.isConnected) return;
                overlay.replaceChildren();
                const base = root.getBoundingClientRect();
                const targets = new Set();
                selectors.forEach(selector => root.querySelectorAll(selector).forEach(el => {
                    if (!overlay.contains(el) && el.getBoundingClientRect().width > 0 && el
                        .getBoundingClientRect().height > 0) targets.add(el)
                }));
                Array.from(targets).filter(el => !Array.from(targets).some(parent => parent !== el && parent.contains(
                    el) && ['SECTION'].includes(parent.tagName))).forEach(el => {
                    const rect = el.getBoundingClientRect();
                    if (rect.width < 10 || rect.height < 8) return;
                    const block = document.createElement('div');
                    block.className = 'sk-block';
                    block.style.left = (rect.left - base.left) + 'px';
                    block.style.top = (rect.top - base.top) + 'px';
                    block.style.width = rect.width + 'px';
                    block.style.height = rect.height + 'px';
                    block.style.borderRadius = el.tagName === 'IMG' ? '16px' : '10px';
                    overlay.appendChild(block);
                });
            }
            render();
            let finished = false;

            function finish() {
                if (finished) return;
                finished = true;
                const wait = Math.max(0, minimumDuration - (Date.now() - started));
                setTimeout(() => {
                    overlay.classList.add('is-hidden');
                    setTimeout(() => overlay.remove(), 450)
                }, wait);
            }
            if (document.readyState === 'complete') finish();
            else window.addEventListener('load', finish, {
                once: true
            });
            setTimeout(finish, maximumDuration);
        })();
    </script>
@endsection
