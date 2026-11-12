<!DOCTYPE html>

<html lang="id">



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



        .school-popup {

            width: 280px;

            max-width: 100%;

            color: var(--ink);

            font-family: system-ui, sans-serif;

        }



        .school-popup-heading {

            display: flex;

            align-items: center;

            gap: 12px;

            margin-bottom: 12px;

            padding-bottom: 12px;

            border-bottom: 1px solid var(--glass-edge);

        }



        .school-popup-logo,

        .school-popup-logo-fallback {

            width: 64px;

            height: 64px;

            flex: 0 0 64px;

            border: 1px solid var(--glass-edge);

            border-radius: 14px;

            background: rgba(255, 255, 255, .08);

        }



        .school-popup-logo {

            padding: 5px;

            object-fit: contain;

        }



        .school-popup-logo-fallback {

            display: grid;

            place-items: center;

            font-size: 30px;

        }



        .school-popup-logo-fallback[hidden] {

            display: none;

        }



        .school-popup-title {

            min-width: 0;

            flex: 1;

        }



        .school-popup-title>strong {

            display: block;

            margin-bottom: 7px;

            color: var(--ink);

            font-size: 14px;

            line-height: 1.4;

            overflow-wrap: anywhere;

        }



        .school-popup-badges {

            display: flex;

            flex-wrap: wrap;

            gap: 5px;

        }



        .school-popup-badge {

            display: inline-flex;

            padding: 4px 8px;

            border: 1px solid rgba(47, 169, 129, .35);

            border-radius: 999px;

            background: rgba(47, 169, 129, .13);

            color: #a7f3d0;

            font-size: 10px;

            font-weight: 650;

        }



        .school-popup-badge-status {

            border-color: rgba(245, 196, 81, .35);

            background: rgba(245, 196, 81, .12);

            color: var(--gold-soft);

        }



        .school-popup-details {

            display: grid;

            gap: 6px;

            color: var(--ink-soft);

            font-size: 12px;

            line-height: 1.5;

        }



        .school-popup-details strong {

            color: var(--ink);

            font-weight: 650;

        }



        .school-popup-details small {

            color: var(--ink-soft);

        }



        .school-popup-link {

            display: block;

            margin-top: 12px;

            padding: 10px 12px;

            border: 1px solid rgba(255, 227, 160, .6);

            border-radius: 10px;

            background: linear-gradient(110deg, var(--gold), var(--gold-deep));

            color: #1c1503 !important;

            font-size: 12px;

            font-weight: 700;

            text-align: center;

            text-decoration: none;

            box-shadow: 0 6px 18px rgba(224, 160, 47, .2);

        }



        .school-popup-link:hover {

            filter: brightness(1.08);

        }



        .leaflet-popup-content {

            margin: 16px;

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


        /* Muted Sky: tampilan navbar mengikuti halaman header-sekolah */
        :root {
            color-scheme: light;
            --ink: #25445a;
            --ink-soft: #52677a;
            --glass: rgba(255, 255, 255, .75);
            --glass-strong: rgba(255, 255, 255, .96);
            --glass-faint: rgba(237, 247, 252, .85);
            --glass-edge: #d6e7f0;
            --glass-shadow: 0 6px 24px rgba(55, 114, 146, .12);
            --glass-inset: inset 0 1px 0 rgba(255, 255, 255, .7);
            --gold: #5F91AC;
            --gold-deep: #4b7e99;
            --gold-soft: #416d85;
            --chalk: #6b9bb3;
            --ink-blue: #5F91AC;
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body {
            background: #f4faff;
            color: var(--ink);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
        }

        body::before,
        body::after {
            display: none;
        }

        .public-header {
            position: sticky;
            top: 0;
            z-index: 1100;
            overflow: visible;
            background: linear-gradient(100deg, #f6fbff 0%, #eaf5fc 100%);
            border-bottom: 1px solid #d6e7f0;
            box-shadow: 0 3px 20px rgba(55, 114, 146, .12);
            backdrop-filter: blur(16px);
        }

        .public-header-inner {
            max-width: 1880px;
            min-height: 90px;
            padding: 12px 30px;
            gap: 22px;
        }

        .public-brand {
            gap: 16px;
            flex-shrink: 0;
        }

        .public-brand-logo {
            width: 65px;
            height: 65px;
            object-fit: contain;
        }

        .public-brand-divider {
            display: block;
            height: 54px;
            width: 2px;
            background: #c7dce9;
        }

        .public-brand-copy strong {
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -.7px;
        }

        .public-brand-copy span {
            margin-top: 4px;
            color: #52677a;
            font-size: 11px;
        }

        .menu {
            gap: 3px;
        }

        .menu a {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 12px 11px;
            border-radius: 11px;
            color: #25445a;
            font-size: 13px;
            font-weight: 700;
        }

        .menu a svg,
        .public-header-actions .masuk svg,
        .public-menu-button svg {
            width: 19px;
            height: 19px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
            flex-shrink: 0;
        }

        .menu a:hover {
            background: #e0f0f9;
            border-color: transparent;
            color: #25445a;
        }

        .menu a.aktif {
            background: #5F91AC;
            border-color: transparent;
            color: #fff;
        }

        .public-header-actions .masuk {
            min-height: 43px;
            padding: 10px 18px;
            gap: 9px;
            border: 0;
            border-radius: 11px;
            background: #81b4d0;
            color: #fff;
            box-shadow: none;
            font-size: 14px;
            font-weight: 700;
        }

        .public-header-actions .masuk:hover {
            background: #5F91AC;
            filter: none;
        }

        .public-menu-button {
            border: 1px solid #d6e7f0;
            background: #e9f5fc;
            color: #25445a;
        }

        .public-mobile-menu {
            background: #f3faff;
            border-top: 1px solid #d6e7f0;
        }

        .public-mobile-menu nav a {
            color: #25445a;
        }

        .public-mobile-menu nav a:hover {
            background: #e0f0f9;
            color: #25445a;
        }

        .public-mobile-menu nav a.aktif {
            background: #5F91AC;
            color: #fff;
        }

        .toolbar {
            background: rgba(245, 250, 255, .94);
            color: #25445a;
            box-shadow: 0 3px 12px rgba(55, 114, 146, .08);
        }

        .toolbar input,
        .toolbar select,
        .toolbar button {
            background: #fff;
            color: #25445a;
        }

        .toolbar select option {
            background: #fff;
            color: #25445a;
        }

        .toolbar input::placeholder {
            color: #74889a;
        }

        .toolbar button,
        .school-popup-link {
            background: #5F91AC;
            color: #fff !important;
            border-color: #5F91AC;
            box-shadow: none;
        }

        .toolbar button:hover,
        .school-popup-link:hover {
            background: #4b7e99;
        }

        .panel,
        .leaflet-popup-content-wrapper,
        .leaflet-popup-tip {
            background: rgba(255, 255, 255, .97);
            color: #25445a;
        }

        .school-popup-badge {
            background: #e2f3f0;
            color: #266b62;
            border-color: #b5dcd4;
        }

        .school-popup-badge-status {
            background: #eaf4fa;
            color: #416d85;
            border-color: #b8d5e6;
        }

        .leaflet-bar a {
            background: #fff;
            color: #25445a;
        }

        .leaflet-bar a:hover {
            background: #e0f0f9;
            color: #25445a;
        }

        .leaflet-control-attribution {
            background: rgba(255, 255, 255, .94) !important;
            color: #52677a !important;
        }

        @media(max-width: 1450px) {
            .public-header-inner {
                padding: 10px 22px;
                gap: 12px;
            }

            .public-brand-logo {
                width: 53px;
                height: 53px;
            }

            .public-brand-copy strong {
                font-size: 21px;
            }

            .public-brand-copy span {
                font-size: 10px;
            }

            .menu a {
                font-size: 12px;
                padding: 10px 8px;
                gap: 5px;
            }

            .menu a svg {
                width: 17px;
                height: 17px;
            }
        }

        @media(max-width: 1200px) {
            .menu {
                display: none;
            }

            .public-menu-button {
                display: inline-flex;
            }
        }

        @media(max-width: 480px) {
            .public-header-inner {
                min-height: 68px;
                padding: 8px 14px;
            }

            .public-brand-logo {
                width: 43px;
                height: 43px;
            }

            .public-brand-divider {
                height: 36px;
                width: 1px;
            }

            .public-brand-copy strong {
                font-size: 17px;
            }

            .public-brand-copy span {
                display: none;
            }

            .public-header-actions .masuk {
                padding: 9px 11px;
                min-height: 36px;
            }

            .public-header-actions .masuk span {
                display: none;
            }
        }
    </style>

    @stack('styles')

</head>



<body>

    @php
        $logoSekolah = asset('storage/Assets/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg.webp');
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

    @stack('scripts')

</body>



</html>
