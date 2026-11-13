<!DOCTYPE html>
<html lang="id" class="admin-app">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') - SIG Sekolah</title>
    <link rel="icon" href="{{ asset('storage/Assets/tut-wuri-handayani.png') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="app-page min-h-screen flex flex-col antialiased">
    @php
        $logo = asset('storage/Assets/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg.webp');
        $navItems = [
            ['label' => 'Peta Publik', 'route' => 'peta.index', 'active' => 'peta.index', 'icon' => 'map'],
        ];
        if (auth()->check()) {
            $user = auth()->user();
            $navItems[] = ['label' => 'Dashboard', 'route' => 'dashboard', 'active' => 'dashboard', 'icon' => 'home'];
            if ($user->isAdmin()) {
                $navItems[] = ['label' => 'Data Sekolah', 'route' => 'admin.sekolah.index', 'active' => 'admin.sekolah.*', 'icon' => 'school'];
                $navItems[] = ['label' => 'Pengguna', 'route' => 'admin.users.index', 'active' => 'admin.users.*', 'icon' => 'users'];
                $navItems[] = ['label' => 'Log Aktivitas', 'route' => 'admin.log-aktivitas.index', 'active' => 'admin.log-aktivitas.*', 'icon' => 'list'];
            }
            if ($user->isDinas()) {
                $navItems[] = ['label' => 'Data Sekolah', 'route' => 'dinas.sekolah.index', 'active' => 'dinas.sekolah.*', 'icon' => 'school'];
            }
            if ($user->isSekolah() && $user->sekolah_id) {
                $navItems[] = ['label' => 'Sekolah Saya', 'route' => 'sekolah.edit', 'active' => 'sekolah.*', 'icon' => 'school', 'parameter' => $user->sekolah_id];
            }
            $navItems[] = ['label' => 'Ubah Kata Sandi', 'route' => 'password.edit', 'active' => 'password.edit', 'icon' => 'lock'];
        }
    @endphp
    <header class="edu-header">
        <div class="edu-header-watermark" aria-hidden="true"><img src="{{ $logo }}" alt=""></div>
        <div class="edu-header-inner">
            <a href="{{ route('peta.index') }}" class="edu-brand">
                <span class="edu-brand-logo"><img src="{{ $logo }}" alt="Logo Tut Wuri Handayani"></span>
                <span class="edu-brand-divider" aria-hidden="true"></span>
                <span><strong class="edu-brand-name">SIG Sekolah</strong><small class="edu-brand-description">Sistem Informasi Geografis Sekolah</small></span>
            </a>
            <nav class="edu-nav" aria-label="Navigasi utama">
                @foreach($navItems as $item)
                    @if(Route::has($item['route']))
                        <a class="edu-nav-link {{ request()->routeIs($item['active']) ? 'is-active' : '' }}" href="{{ route($item['route'], $item['parameter'] ?? []) }}">
                            @switch($item['icon'])
                                @case('home')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m3 10 9-7 9 7v10a1 1 0 0 1-1 1h-5v-7H9v7H4a1 1 0 0 1-1-1z"/></svg>@break
                                @case('map')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m3 6 6-3 6 3 6-3v15l-6 3-6-3-6 3zM9 3v15M15 6v15"/></svg>@break
                                @case('school')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-7h6v7M9 10h.01M15 10h.01"/></svg>@break
                                @case('users')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8ZM22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>@break
                                @case('list')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/></svg>@break
                                @case('lock')<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg>@break
                            @endswitch
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach
            </nav>
            <div class="edu-actions">
                @auth
                    <div class="edu-user"><span class="edu-avatar">{{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}</span><span class="min-w-0"><strong class="edu-user-name">{{ auth()->user()->name }}</strong><small class="edu-user-role">{{ auth()->user()->roleLabel() }}</small></span></div>
                    <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="edu-logout" aria-label="Keluar dari akun"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M10 17l5-5-5-5M15 12H3"/><path d="M12 3h6a3 3 0 0 1 3 3v12a3 3 0 0 1-3 3h-6"/></svg><span>Keluar</span></button></form>
                @else
                    <a href="{{ route('login') }}" class="edu-login"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a6 6 0 0 0-12 0v2M14 13a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z"/></svg>Masuk</a>
                @endauth
                <button type="button" id="mobile-menu-button" class="edu-toggle" aria-label="Buka navigasi" aria-controls="mobile-menu" aria-expanded="false"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
            </div>
        </div>
        <nav id="mobile-menu" class="edu-mobile" aria-label="Navigasi mobile" hidden>
            @foreach($navItems as $item)
                @if(Route::has($item['route']))
                    <a href="{{ route($item['route'], $item['parameter'] ?? []) }}" class="{{ request()->routeIs($item['active']) ? 'is-active' : '' }}">{{ $item['label'] }}</a>
                @endif
            @endforeach
            @auth
                <div class="mt-2 border-t border-[#D6E5ED] px-3 pt-3 text-xs text-[#607C91]">{{ auth()->user()->name }} — {{ auth()->user()->roleLabel() }}</div>
            @endauth
        </nav>
    </header>
    <main class="page-content mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 lg:px-8">
        @if(session('success'))
            <div role="status" class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div role="alert" class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div role="alert" class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800"><ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        @yield('content')
    </main>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const button = document.getElementById('mobile-menu-button');
            const menu = document.getElementById('mobile-menu');
            if (!button || !menu) return;
            button.addEventListener('click', function () {
                const nextOpen = menu.hidden;
                menu.hidden = !nextOpen;
                button.setAttribute('aria-expanded', String(nextOpen));
                button.setAttribute('aria-label', nextOpen ? 'Tutup navigasi' : 'Buka navigasi');
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
