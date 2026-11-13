<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') - SIG Sekolah</title>
    <link rel="icon" href="{{ asset('storage/Assets/tut-wuri-handayani.png') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root{--sky:#5F91AC;--sky-soft:#A3C4D4;--sky-ink:#294B63;--sky-muted:#607C91;--sky-line:#D6E5ED;--sky-paper:#F2F8FC}
        body.app-page{background:#F7FAFC!important;background-image:none!important;color:#294B63!important}
        body.app-page::before,body.app-page::after{display:none!important}
        .edu-header{position:sticky;top:0;z-index:1100;width:100%;background:linear-gradient(110deg,#F7FBFE 0%,#ECF5FB 55%,#E5F2F9 100%)!important;background-image:linear-gradient(110deg,#F7FBFE 0%,#ECF5FB 55%,#E5F2F9 100%)!important;border-bottom:1px solid #C9E1EC;box-shadow:0 5px 20px rgba(42,83,108,.10);color:var(--sky-ink)!important;isolation:isolate}
        .edu-header::before,.edu-header::after{display:none!important}
        .edu-header-watermark{position:absolute;right:1%;top:0;height:100%;width:220px;overflow:hidden;pointer-events:none;z-index:-1}
        .edu-header-watermark img{position:absolute;right:-20px;top:50%;height:185px;width:185px;transform:translateY(-50%);object-fit:contain;opacity:.075;filter:grayscale(1)}
        .edu-header-inner{display:grid;grid-template-columns:minmax(285px,1fr) auto minmax(140px,1fr);align-items:center;gap:18px;max-width:1700px;margin:auto;min-height:96px;padding:14px 34px}
        .edu-brand{display:flex;align-items:center;gap:17px;min-width:0;text-decoration:none!important;color:var(--sky-ink)!important}
        .edu-brand-logo{height:62px;width:62px;flex-shrink:0;display:grid;place-items:center}
        .edu-brand-logo img{height:100%;width:100%;object-fit:contain;filter:none!important;opacity:1!important}
        .edu-brand-divider{height:49px;width:1px;background:#BED5E2;flex-shrink:0}
        .edu-brand-name{display:block;font-size:26px;font-weight:800;line-height:1.13;letter-spacing:-.7px;color:#284A63!important;white-space:nowrap}
        .edu-brand-description{display:block;margin-top:6px;font-size:12px;font-weight:500;color:#526E83!important;white-space:nowrap}
        .edu-nav{display:flex;align-items:center;justify-content:center;gap:5px;min-width:0}
        .edu-nav-link{display:inline-flex;align-items:center;gap:9px;min-height:45px;padding:0 12px;border:1px solid transparent;border-radius:12px;color:#294B63!important;background:transparent!important;text-decoration:none!important;font-size:13px;font-weight:650;white-space:nowrap;transition:background .2s,border-color .2s,box-shadow .2s}
        .edu-nav-link svg{width:19px;height:19px;flex-shrink:0;stroke:currentColor}
        .edu-nav-link:hover{background:#DDECF5!important;border-color:#C9E1ED}
        .edu-nav-link.is-active{background:#5F91AC!important;color:#FFF!important;border-color:#5F91AC;box-shadow:0 5px 14px rgba(95,145,172,.20)}
        .edu-actions{display:flex;align-items:center;justify-content:flex-end;gap:10px;min-width:0}
        .edu-user{display:flex;align-items:center;gap:11px;padding:6px 14px 6px 6px;border:1px solid rgba(190,213,226,.9);border-radius:14px;background:rgba(255,255,255,.72);box-shadow:0 5px 18px rgba(41,75,99,.06);backdrop-filter:blur(12px);max-width:205px;min-width:0;transition:background .2s,border-color .2s,box-shadow .2s}.edu-user:hover{background:rgba(255,255,255,.95);border-color:#A3C4D4;box-shadow:0 7px 20px rgba(41,75,99,.10)}
        .edu-avatar{height:38px;width:38px;flex-shrink:0;display:grid;place-items:center;border-radius:11px;background:linear-gradient(135deg,#5F91AC,#8DB5CA);color:#fff;font-size:15px;font-weight:800;box-shadow:0 3px 10px rgba(95,145,172,.2)}
        .edu-user-name{display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:12px;font-weight:800;color:#294B63;line-height:1.4}
        .edu-user-role{display:block;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:10px;color:#668398;line-height:1.5}
        .edu-login,.edu-logout{display:inline-flex;align-items:center;justify-content:center;gap:7px;min-height:42px;padding:0 17px;border-radius:11px;font-size:13px;font-weight:750;text-decoration:none!important;white-space:nowrap;cursor:pointer;transition:background .2s}
        .edu-login{background:#5F91AC!important;color:#FFF!important;border:1px solid #5F91AC}
        .edu-login:hover{background:#4B7D98!important}
        .edu-logout{background:rgba(255,255,255,.7)!important;color:#416D86!important;border:1px solid #C9DEE9;box-shadow:0 4px 14px rgba(41,75,99,.05);backdrop-filter:blur(12px);transition:background .2s,color .2s,border-color .2s,transform .2s}
        .edu-logout:hover{background:#E1EFF6!important;color:#294B63!important;border-color:#A3C4D4;transform:translateY(-1px)}.edu-logout:focus-visible{outline:3px solid rgba(95,145,172,.28);outline-offset:2px}.edu-logout:active{transform:translateY(0)}
        .edu-login svg,.edu-logout svg{width:17px;height:17px}
        .edu-toggle{display:none;align-items:center;justify-content:center;height:42px;width:42px;border:1px solid #C9DEE9;border-radius:11px;background:#FFF!important;color:#294B63!important;cursor:pointer}
        .edu-mobile{background:#F0F7FB!important;border-top:1px solid #D6E5ED;padding:12px 20px 18px}
        .edu-mobile[hidden]{display:none!important}
        .edu-mobile a{display:flex;align-items:center;gap:10px;padding:12px 13px;border-radius:10px;color:#294B63!important;font-size:14px;font-weight:650;text-decoration:none!important}
        .edu-mobile a:hover,.edu-mobile a.is-active{background:#DCECF5!important}
        .edu-mobile svg{width:18px;height:18px}
        @media(max-width:1500px){.edu-header-inner{grid-template-columns:minmax(250px,1fr) auto auto;gap:12px;padding:12px 24px}.edu-nav-link{padding:0 9px;gap:6px;font-size:12px}.edu-brand-name{font-size:23px}.edu-brand-logo{height:55px;width:55px}}
        @media(max-width:1230px){.edu-header-inner{grid-template-columns:1fr auto;min-height:82px}.edu-nav{display:none}.edu-toggle{display:inline-flex}.edu-brand-name{font-size:22px}}
        @media(max-width:640px){.edu-header-inner{padding:10px 14px;min-height:72px;gap:8px}.edu-brand{gap:9px}.edu-brand-logo{width:42px;height:42px}.edu-brand-divider{height:36px}.edu-brand-name{font-size:17px}.edu-brand-description{display:none}.edu-user{display:flex;max-width:48px;padding:4px;border-radius:12px}.edu-user .min-w-0{display:none}.edu-avatar{height:34px;width:34px}.edu-actions{gap:6px}.edu-logout{gap:5px;padding:0 9px}.edu-login,.edu-logout{padding:0 10px;min-height:38px;font-size:12px}.edu-toggle{height:38px;width:38px}.edu-header-watermark img{height:130px;width:130px}}
    </style>
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
