<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - SIG Sekolah</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    
    <!-- Ganti @vite dengan ini -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="{{ asset('build/assets/app.css') }}">
        <script type="module" src="{{ asset('build/assets/app.js') }}"></script>
    @endif
</head>
<body class="app-page">
    <header class="app-navbar">
        <a class="app-brand" href="{{ route('peta.index') }}">
            <span class="app-brand-icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z" />
                    <circle cx="12" cy="10" r="2.5" />
                </svg>
            </span>
            <span class="app-brand-text">
                <strong>SIG Sekolah</strong>
                <small>Sistem Informasi Geografis Sekolah</small>
            </span>
        </a>

        <nav class="app-nav" aria-label="Navigasi utama">
            <a href="{{ route('peta.index') }}" @class(['is-active' => request()->routeIs('peta.index')])>
                Peta Publik
            </a>
            <a href="{{ route('admin.sekolah.index') }}" @class(['is-active' => request()->routeIs('admin.sekolah.*')])>
                Data Sekolah
            </a>
        </nav>
    </header>

    <main class="page-content">
        @if (session('success'))
            <div class="alert-success" role="status">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @stack('scripts')
</body>
</html>
