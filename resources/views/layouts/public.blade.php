<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Peta Sekolah</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        /* ==========================================================
           TEMA GLASSMORPHISM GELAP - PENDIDIKAN
           Papan tulis hijau tua + biru tinta + emas (lambang kelulusan).
           Semua warna diatur lewat variabel di bawah.
           ========================================================== */
        :root {
            color-scheme: dark;

            --ink: #eaf3ee;
            --ink-soft: #a9c0b5;

            --glass: rgba(255, 255, 255, .07);
            --glass-strong: rgba(14, 30, 27, .72);
            --glass-faint: rgba(255, 255, 255, .06);
            --glass-edge: rgba(255, 255, 255, .16);
            --glass-shadow: 0 14px 40px rgba(0, 0, 0, .45);
            --glass-inset: inset 0 1px 0 rgba(255, 255, 255, .14);
            --blur: 20px;

            --gold: #f5c451;
            --gold-deep: #e0a02f;
            --gold-soft: #ffe3a0;
            --chalk: #2fa981;
            --ink-blue: #3b46c4;
        }

        html,
        body {
            height: 100%;
            margin: 0;
            font-family: system-ui, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            background: linear-gradient(150deg, #07120f, #0a1626 55%, #0b0f1e);
            color: var(--ink);
        }

        /* Gumpalan cahaya: hijau papan tulis, biru tinta, dan sedikit emas */
        body::before {
            content: "";
            position: fixed;
            inset: -10%;
            z-index: -2;
            pointer-events: none;
            background:
                radial-gradient(circle at 12% 16%, rgba(47, 169, 129, .42), transparent 34%),
                radial-gradient(circle at 90% 10%, rgba(59, 70, 196, .5), transparent 36%),
                radial-gradient(circle at 82% 86%, rgba(224, 160, 47, .28), transparent 32%),
                radial-gradient(circle at 18% 90%, rgba(30, 110, 160, .4), transparent 34%);
            animation: latar-bergeser 30s ease-in-out infinite alternate;
        }

        /* Motif kertas berpetak (buku tulis), sangat tipis */
        body::after {
            content: "";
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(255, 255, 255, .035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .035) 1px, transparent 1px);
            background-size: 32px 32px;
            -webkit-mask-image: radial-gradient(ellipse at center, #000 30%, transparent 85%);
            mask-image: radial-gradient(ellipse at center, #000 30%, transparent 85%);
        }

        @keyframes latar-bergeser {
            to {
                transform: translate(3%, 2%) scale(1.08);
            }
        }

        @media (prefers-reduced-motion: reduce) {
            body::before {
                animation: none;
            }
        }

        :focus-visible {
            outline: 3px solid var(--gold);
            outline-offset: 2px;
        }

        /* ---------- Header ---------- */
        .public-header {
            position: sticky;
            top: 0;
            z-index: 1100;
            flex: 0 0 auto;
            overflow: hidden;
            border-bottom: 1px solid var(--glass-edge);
            background: rgba(10, 24, 22, .55);
            -webkit-backdrop-filter: blur(var(--blur)) saturate(150%);
            backdrop-filter: blur(var(--blur)) saturate(150%);
            color: var(--ink);
            box-shadow: var(--glass-shadow), var(--glass-inset);
        }

        .public-watermark {
            position: absolute;
            inset: 0 0 0 auto;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            overflow: hidden;
            pointer-events: none;
        }

        .public-watermark img {
            width: clamp(220px, 30vw, 390px);
            height: clamp(150px, 20vw, 230px);
            transform: translateX(30px) rotate(6deg);
            object-fit: contain;
            opacity: .09;
            filter: grayscale(1) brightness(0) invert(1);
        }

        .public-header-inner {
            position: relative;
            z-index: 1;
            display: flex;
            min-height: 56px;
            max-width: 1280px;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: 0 auto;
            padding: 6px 24px;
        }

        .public-brand {
            display: flex;
            min-width: 0;
            align-items: center;
            gap: 10px;
            color: var(--ink);
            text-decoration: none;
        }

        .public-brand-mark {
            display: grid;
            width: 36px;
            height: 36px;
            flex: 0 0 auto;
            place-items: center;
            border: 1px solid rgba(245, 196, 81, .45);
            border-radius: 12px;
            background: rgba(245, 196, 81, .12);
            color: var(--gold);
            box-shadow: 0 4px 14px rgba(0, 0, 0, .35), var(--glass-inset);
            transition: background .2s ease;
        }

        .public-brand:hover .public-brand-mark {
            background: rgba(245, 196, 81, .22);
        }

        .public-brand-copy {
            min-width: 0;
        }

        .public-brand-copy strong {
            display: block;
            font-size: 16px;
            line-height: 1.2;
            letter-spacing: -.02em;
        }

        .public-brand-copy span {
            display: block;
            margin-top: 1px;
            color: var(--ink-soft);
            font-size: 10px;
            white-space: nowrap;
        }

        .menu {
            display: flex;
            flex: 0 1 auto;
            align-items: center;
            gap: 4px;
        }

        .menu a {
            color: var(--ink-soft);
            text-decoration: none;
            padding: 7px 10px;
            border: 1px solid transparent;
            border-radius: 10px;
            white-space: nowrap;
            font-size: 13px;
            font-weight: 500;
            transition: background .2s ease, color .2s ease;
        }

        .menu a:hover {
            background: var(--glass);
            border-color: var(--glass-edge);
            color: var(--ink);
        }

        .menu a.aktif {
            background: rgba(245, 196, 81, .15);
            border-color: rgba(245, 196, 81, .45);
            color: var(--gold-soft);
            font-weight: 600;
        }

        .public-header-actions {
            display: flex;
            flex: 0 0 auto;
            align-items: center;
            gap: 10px;
        }

        .public-header-actions .masuk {
            display: inline-flex;
            min-height: 34px;
            align-items: center;
            justify-content: center;
            padding: 0 14px;
            border: 1px solid rgba(255, 227, 160, .6);
            border-radius: 10px;
            background: linear-gradient(110deg, var(--gold), var(--gold-deep));
            color: #1c1503;
            white-space: nowrap;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            box-shadow: 0 6px 18px rgba(224, 160, 47, .28), inset 0 1px 0 rgba(255, 255, 255, .4);
            transition: filter .2s ease;
        }

        .public-header-actions .masuk:hover {
            filter: brightness(1.08);
        }

        .public-menu-button {
            display: none;
            width: 36px;
            height: 36px;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--glass-edge);
            border-radius: 10px;
            background: var(--glass);
            color: var(--ink);
            cursor: pointer;
            box-shadow: var(--glass-inset);
        }

        .public-mobile-menu {
            position: relative;
            z-index: 1;
            padding: 4px 16px 14px;
            border-top: 1px solid var(--glass-edge);
            background: rgba(8, 20, 18, .7);
            -webkit-backdrop-filter: blur(var(--blur));
            backdrop-filter: blur(var(--blur));
        }

        .public-mobile-menu[hidden] {
            display: none;
        }

        .public-mobile-menu nav {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .public-mobile-menu nav a {
            padding: 10px 12px;
            border-radius: 9px;
            color: var(--ink-soft);
            font-size: 14px;
            text-decoration: none;
        }

        .public-mobile-menu nav a:hover {
            background: var(--glass);
            color: var(--ink);
        }

        .public-mobile-menu nav a.aktif {
            background: rgba(245, 196, 81, .15);
            color: var(--gold-soft);
        }

        @media (max-width: 1100px) {
            .public-header-inner {
                padding-right: 20px;
                padding-left: 20px;
            }

            .menu {
                display: none;
            }

            .public-menu-button {
                display: inline-flex;
            }
        }

        @media (max-width: 480px) {
            .public-header-inner {
                min-height: 52px;
                gap: 8px;
                padding: 6px 14px;
            }

            .public-brand {
                gap: 10px;
            }

            .public-brand-mark {
                width: 34px;
                height: 34px;
                border-radius: 11px;
            }

            .public-brand-copy strong {
                font-size: 16px;
            }

            .public-brand-copy span {
                display: none;
            }

            .public-header-actions {
                gap: 7px;
            }

            .public-header-actions .masuk {
                min-height: 34px;
                padding: 0 10px;
                font-size: 12px;
            }

            .public-menu-button {
                width: 34px;
                height: 34px;
            }
        }

        /* ---------- Konten ---------- */
        main {
            flex: 1;
            min-height: 0;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            padding: 10px 16px;
            font-size: 14px;
            color: var(--ink);
            border-bottom: 1px solid var(--glass-edge);
            background: rgba(10, 24, 22, .5);
            box-shadow: 0 8px 24px rgba(0, 0, 0, .3), var(--glass-inset);
            -webkit-backdrop-filter: blur(var(--blur)) saturate(150%);
            backdrop-filter: blur(var(--blur)) saturate(150%);
        }

        .toolbar input,
        .toolbar select,
        .toolbar button {
            padding: 8px 10px;
            border: 1px solid var(--glass-edge);
            border-radius: 10px;
            font-size: 14px;
            background: rgba(255, 255, 255, .08);
            color: var(--ink);
            box-shadow: var(--glass-inset);
        }

        .toolbar select option {
            background: #10201d;
            color: var(--ink);
        }

        .toolbar input::placeholder {
            color: var(--ink-soft);
        }

        .toolbar button {
            background: linear-gradient(110deg, var(--gold), var(--gold-deep));
            color: #1c1503;
            font-weight: 700;
            border-color: rgba(255, 227, 160, .6);
            cursor: pointer;
            box-shadow: 0 7px 18px rgba(224, 160, 47, .25), inset 0 1px 0 rgba(255, 255, 255, .4);
        }

        .toolbar button:hover {
            filter: brightness(1.08);
        }

        /* Panel melayang & popup Leaflet */
        .panel,
        .leaflet-popup-content-wrapper,
        .leaflet-popup-tip {
            border: 1px solid var(--glass-edge);
            background: var(--glass-strong);
            color: var(--ink);
            box-shadow: var(--glass-shadow), var(--glass-inset);
            -webkit-backdrop-filter: blur(var(--blur)) saturate(150%);
            backdrop-filter: blur(var(--blur)) saturate(150%);
        }

        .peta {
            flex: 1;
            min-height: 300px;
        }

        .panel {
            position: absolute;
            z-index: 1000;
            border-radius: 16px;
            padding: 12px 14px;
            font-size: 14px;
        }

        .leaflet-popup-content-wrapper {
            border-radius: 16px;
        }

        .leaflet-popup-content a,
        .panel a {
            color: var(--gold);
        }

        .leaflet-container a.leaflet-popup-close-button {
            color: var(--ink-soft);
        }

        .leaflet-container a.leaflet-popup-close-button:hover {
            color: var(--gold);
        }

        /* Kontrol bawaan Leaflet */
        .leaflet-bar {
            border: 1px solid var(--glass-edge) !important;
            border-radius: 14px !important;
            overflow: hidden;
            box-shadow: var(--glass-shadow) !important;
        }

        .leaflet-bar a {
            background: var(--glass-strong);
            color: var(--ink);
            border-bottom-color: var(--glass-edge);
            -webkit-backdrop-filter: blur(var(--blur));
            backdrop-filter: blur(var(--blur));
        }

        .leaflet-bar a:hover {
            background: rgba(245, 196, 81, .2);
            color: var(--gold-soft);
        }

        .leaflet-control-attribution {
            border-radius: 10px 0 0 0;
            background: var(--glass-strong) !important;
            color: var(--ink-soft) !important;
            -webkit-backdrop-filter: blur(12px);
            backdrop-filter: blur(12px);
        }

        .leaflet-control-attribution a {
            color: var(--gold) !important;
        }

        /* Cadangan untuk peramban tanpa backdrop-filter: buat lebih pekat agar teks tetap terbaca */
        @supports not ((backdrop-filter: blur(1px)) or (-webkit-backdrop-filter: blur(1px))) {
            :root {
                --glass-strong: rgba(12, 26, 24, .96);
            }

            .public-header,
            .toolbar,
            .public-mobile-menu {
                background: rgba(12, 26, 24, .96);
            }
        }
    </style>
    @stack('styles')
</head>

<body>
    <header class="public-header">
        <div class="public-watermark" aria-hidden="true">
            <img src="{{ asset('storage/Assets/tut-wuri-handayani.png') }}" alt="">
        </div>
        @php
            $menu = [
                'peta.index' => 'Peta Sekolah',
                'peta.cari' => 'Pencarian',
                'peta.klaster' => 'Klaster',
                'peta.panas' => 'Peta Panas',
                'peta.wilayah' => 'Wilayah',
                'peta.rute' => 'Rute',
                'peta.alamat' => 'Cek Lokasi',
            ];
        @endphp
        <div class="public-header-inner">
            <a class="public-brand" href="{{ route('peta.index') }}">
                <span class="public-brand-mark" aria-hidden="true">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m3 9 9-5 9 5-9 5-9-5Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 11.3V16c2.8 2.2 7.2 2.2 10 0v-4.7M21 9v6"/>
                    </svg>
                </span>
                <span class="public-brand-copy">
                    <strong>SIG Sekolah</strong>
                    <span>Sistem Informasi Geografis Sekolah</span>
                </span>
            </a>

            <nav class="menu" aria-label="Navigasi utama">
                @foreach ($menu as $nama => $label)
                    @if (Route::has($nama))
                        <a href="{{ route($nama) }}" @class(['aktif' => request()->routeIs($nama)])>
                            {{ $label }}
                        </a>
                    @endif
                @endforeach
            </nav>

            <div class="public-header-actions">
                @auth
                    <a class="masuk" href="{{ route('dashboard') }}">Dashboard</a>
                @else
                    <a class="masuk" href="{{ route('login') }}">Masuk</a>
                @endauth
                <button
                    type="button"
                    id="public-menu-button"
                    class="public-menu-button"
                    aria-label="Buka navigasi"
                    aria-controls="public-mobile-menu"
                    aria-expanded="false"
                >
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                        <path d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <div id="public-mobile-menu" class="public-mobile-menu" hidden>
            <nav aria-label="Navigasi mobile">
                @foreach ($menu as $nama => $label)
                    @if (Route::has($nama))
                        <a href="{{ route($nama) }}" @class(['aktif' => request()->routeIs($nama)])>
                            {{ $label }}
                        </a>
                    @endif
                @endforeach
            </nav>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        window.PETA_URL = {
            data: "{{ route('peta.data') }}",
            wilayah: "{{ route('peta.data.wilayah') }}",
        };
    </script>
    <script src="{{ asset('js/peta.js') }}"></script>
    <script>
        const publicMenuButton = document.getElementById('public-menu-button');
        const publicMobileMenu = document.getElementById('public-mobile-menu');

        publicMenuButton.addEventListener('click', () => {
            const isExpanded = publicMenuButton.getAttribute('aria-expanded') === 'true';

            publicMenuButton.setAttribute('aria-expanded', String(!isExpanded));
            publicMenuButton.setAttribute(
                'aria-label',
                isExpanded ? 'Buka navigasi' : 'Tutup navigasi'
            );
            publicMobileMenu.hidden = isExpanded;
        });
    </script>
    @stack('scripts')
</body>

</html>