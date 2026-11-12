<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - Peta Sekolah</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            font-family: system-ui, sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        .public-header {
            position: sticky;
            top: 0;
            z-index: 1000;
            flex: 0 0 auto;
            overflow: hidden;
            border-bottom: 1px solid rgba(255, 255, 255, .12);
            background: linear-gradient(110deg, #123e32, #312e81 58%, #0f172a);
            color: #fff;
            box-shadow: 0 10px 24px rgba(15, 23, 42, .18);
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
            opacity: .1;
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
            color: #fff;
            text-decoration: none;
        }

        .public-brand-mark {
            display: grid;
            width: 36px;
            height: 36px;
            flex: 0 0 auto;
            place-items: center;
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 12px;
            background: rgba(255, 255, 255, .1);
            color: #bfdbfe;
            box-shadow: 0 4px 12px rgba(15, 23, 42, .2);
            backdrop-filter: blur(8px);
            transition: background .2s ease;
        }

        .public-brand:hover .public-brand-mark {
            background: rgba(255, 255, 255, .2);
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
            color: rgba(191, 219, 254, .82);
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
            color: #dbeafe;
            text-decoration: none;
            padding: 7px 10px;
            border-radius: 10px;
            white-space: nowrap;
            font-size: 13px;
            font-weight: 500;
            transition: background .2s ease, color .2s ease;
        }

        .menu a:hover {
            background: rgba(255, 255, 255, .1);
            color: #fff;
        }

        .menu a.aktif {
            background: rgba(255, 255, 255, .2);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(15, 23, 42, .1);
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
            padding: 0 13px;
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 10px;
            background: rgba(255, 255, 255, .1);
            color: #fff;
            white-space: nowrap;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            transition: background .2s ease;
        }

        .public-header-actions .masuk:hover {
            background: rgba(255, 255, 255, .2);
        }

        .public-menu-button {
            display: none;
            width: 36px;
            height: 36px;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 10px;
            background: rgba(255, 255, 255, .1);
            color: #fff;
            cursor: pointer;
        }

        .public-mobile-menu {
            position: relative;
            z-index: 1;
            padding: 4px 16px 14px;
            border-top: 1px solid rgba(255, 255, 255, .1);
            background: rgba(15, 23, 42, .7);
            backdrop-filter: blur(12px);
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
            color: #dbeafe;
            font-size: 14px;
            text-decoration: none;
        }

        .public-mobile-menu nav a:hover,
        .public-mobile-menu nav a.aktif {
            background: rgba(255, 255, 255, .12);
            color: #fff;
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
            background: #f3f4f6;
            border-bottom: 1px solid #d1d5db;
        }

        .toolbar input,
        .toolbar select,
        .toolbar button {
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
        }

        .toolbar button {
            background: #1e3a8a;
            color: #fff;
            border-color: #1e3a8a;
            cursor: pointer;
        }

        .peta {
            flex: 1;
            min-height: 300px;
        }

        .panel {
            position: absolute;
            z-index: 1000;
            background: #fff;
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 14px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .2);
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
