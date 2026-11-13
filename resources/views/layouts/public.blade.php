<!DOCTYPE html>

<html lang="id" class="public-page @yield('page-style-class')">



<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') - Peta Sekolah</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    <link rel="shortcut icon" href="{{ asset('storage/Assets/tut-wuri-handayani.png') }}" type="image/x-icon">



    @stack('styles')
    @vite(['resources/css/app.css'])

</head>



<body>

    @php
        $logoSekolah = asset(
            'storage/Assets/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg.webp',
        );
        $menu = [
            'peta.index' => ['Beranda', 'home'],
            'peta.cari' => ['Pencarian', 'search'],
            'peta.klaster' => ['Klaster', 'layers'],
            'peta.panas' => ['Peta Panas', 'chart'],
            'peta.wilayah' => ['Wilayah', 'map'],
            'peta.rute' => ['Rute', 'route'],
            'peta.alamat' => ['Cek Lokasi', 'pin'],
        ];
    @endphp
    <header class="public-header">
        <div class="public-header-inner">
            <a class="public-brand" href="{{ route('peta.index') }}" aria-label="SIG Sekolah - Beranda">
                <img class="public-brand-logo" src="{{ $logoSekolah }}" alt="Logo Tut Wuri Handayani">
                <span class="public-brand-divider" aria-hidden="true"></span>
                <span class="public-brand-copy">
                    <strong>SIG Sekolah</strong>
                    <span>Sistem Informasi Geografis Sekolah</span>
                </span>
            </a>
            <nav class="menu" aria-label="Navigasi utama">
                @foreach ($menu as $nama => [$label, $ikon])
                    @if (Route::has($nama))
                        <a href="{{ route($nama) }}" @class(['aktif' => request()->routeIs($nama)])>
                            @if ($ikon === 'home')
                                <svg viewBox="0 0 24 24">
                                    <path d="m3 10 9-7 9 7v10h-6v-7H9v7H3z" />
                                </svg>
                            @elseif ($ikon === 'search')
                                <svg viewBox="0 0 24 24">
                                    <circle cx="10" cy="10" r="7" />
                                    <path d="m15 15 6 6" />
                                </svg>
                            @elseif ($ikon === 'layers')
                                <svg viewBox="0 0 24 24">
                                    <path d="m12 2 10 6-10 6L2 8zm-10 11 10 6 10-6M2 18l10 5 10-5" />
                                </svg>
                            @elseif ($ikon === 'chart')
                                <svg viewBox="0 0 24 24">
                                    <path d="M4 20V9m5 11V4m5 16v-8m5 8V6" />
                                </svg>
                            @elseif ($ikon === 'map')
                                <svg viewBox="0 0 24 24">
                                    <path d="m3 6 6-3 6 3 6-3v15l-6 3-6-3-6 3zM9 3v15m6-12v15" />
                                </svg>
                            @elseif ($ikon === 'route')
                                <svg viewBox="0 0 24 24">
                                    <circle cx="5" cy="5" r="2" />
                                    <circle cx="19" cy="19" r="2" />
                                    <path d="M7 5h8a4 4 0 0 1 0 8H9a4 4 0 0 0 0 8h8" />
                                </svg>
                            @else
                                <svg viewBox="0 0 24 24">
                                    <path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z" />
                                    <circle cx="12" cy="10" r="2" />
                                </svg>
                            @endif
                            <span>{{ $label }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>
            <div class="public-header-actions">
                @auth
                    @if (Route::has('dashboard'))
                        <a class="masuk" href="{{ route('dashboard') }}"><svg viewBox="0 0 24 24">
                                <rect x="3" y="3" width="7" height="7" rx="1" />
                                <rect x="14" y="3" width="7" height="7" rx="1" />
                                <rect x="3" y="14" width="7" height="7" rx="1" />
                                <rect x="14" y="14" width="7" height="7" rx="1" />
                            </svg><span>Dashboard</span></a>
                    @endif
                @else
                    @if (Route::has('login'))
                        <a class="masuk" href="{{ route('login') }}"><svg viewBox="0 0 24 24">
                                <circle cx="12" cy="7" r="4" />
                                <path d="M4 21v-2a8 8 0 0 1 16 0v2" />
                            </svg><span>Login</span></a>
                    @endif
                @endauth
                <button type="button" id="public-menu-button" class="public-menu-button" aria-label="Buka navigasi"
                    aria-controls="public-mobile-menu" aria-expanded="false">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
        <div id="public-mobile-menu" class="public-mobile-menu" hidden>
            <nav aria-label="Navigasi mobile">
                @foreach ($menu as $nama => [$label, $ikon])
                    @if (Route::has($nama))
                        <a href="{{ route($nama) }}" @class(['aktif' => request()->routeIs($nama)])>{{ $label }}</a>
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

            logoFallback: "{{ asset('storage/Assets/tut-wuri-handayani.png') }}",

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

    <script>
        window.aturLoadingPetaPublik = function(aktif) {
            const frame = document.getElementById('public-map-frame');
            const loading = document.getElementById('peta-loading');

            if (!frame || !loading) {
                throw new Error('Indikator loading peta publik tidak ditemukan.');
            }

            frame.classList.toggle('is-loading', aktif);
            frame.setAttribute('aria-busy', String(aktif));
            loading.hidden = !aktif;
        };
    </script>

    @stack('scripts')

</body>



</html>
