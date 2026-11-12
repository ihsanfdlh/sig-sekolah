<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') - SIG Sekolah</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="shortcut icon" href="storage/Assets/tut-wuri-handayani.png" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="app-page min-h-screen bg-slate-50 text-slate-800 antialiased flex flex-col">

    <!-- Header / Navbar Utama dengan Siluet Tut Wuri Handayani -->
    <header class="relative bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 text-white shadow-lg overflow-hidden sticky top-0 z-[1000]">

        <!-- Siluet Tut Wuri Handayani pada Background Navbar -->
        <div class="pointer-events-none absolute inset-0 flex items-center justify-end overflow-hidden">

            <img
                src="{{ asset('storage/Assets/tut-wuri-handayani.png') }}"
                alt=""
                aria-hidden="true"
                class="h-64 w-64 translate-x-10 rotate-6 object-contain opacity-10 grayscale brightness-0 invert sm:h-80 sm:w-80 lg:h-96 lg:w-96"
            >

        </div>
        <div class="mx-auto flex min-h-[76px] max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8 relative z-10">
            
            <!-- Brand & Logo -->
            <a href="{{ route('peta.index') }}" class="flex min-w-0 items-center gap-3.5 group">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 text-blue-200 shadow-md transition group-hover:bg-white/20">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m3 9 9-5 9 5-9 5-9-5Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 11.3V16c2.8 2.2 7.2 2.2 10 0v-4.7M21 9v6"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h1 class="text-lg font-bold tracking-tight text-white leading-tight">SIG Sekolah</h1>
                    <p class="hidden text-xs text-blue-200/80 sm:block">Sistem Informasi Geografis Sekolah</p>
                </div>
            </a>

            <!-- Navigasi Desktop -->
            <nav class="hidden items-center gap-1 lg:ml-6 lg:flex" aria-label="Navigasi utama">
                <a href="{{ route('peta.index') }}" @class(['rounded-xl px-4 py-2.5 text-sm font-medium transition', 'bg-white/20 text-white backdrop-blur-sm shadow-sm' => request()->routeIs('peta.index'), 'text-blue-100 hover:bg-white/10 hover:text-white' => !request()->routeIs('peta.index')])>Peta Publik</a>
                
                @auth
                    <a href="{{ route('dashboard') }}" @class(['rounded-xl px-4 py-2.5 text-sm font-medium transition', 'bg-white/20 text-white backdrop-blur-sm shadow-sm' => request()->routeIs('dashboard'), 'text-blue-100 hover:bg-white/10 hover:text-white' => !request()->routeIs('dashboard')])>Dashboard</a>
                    
                    @if(auth()->user()->isAdmin() && Route::has('admin.sekolah.index'))
                        <a href="{{ route('admin.sekolah.index') }}" @class(['rounded-xl px-4 py-2.5 text-sm font-medium transition', 'bg-white/20 text-white backdrop-blur-sm shadow-sm' => request()->routeIs('admin.sekolah.*'), 'text-blue-100 hover:bg-white/10 hover:text-white' => !request()->routeIs('admin.sekolah.*')])>Data Sekolah</a>
                    @endif
                    
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}" class="rounded-xl px-4 py-2.5 text-sm font-medium text-blue-100 transition hover:bg-white/10 hover:text-white">Pengguna</a>
                        <a href="{{ route('admin.log-aktivitas.index') }}" class="rounded-xl px-4 py-2.5 text-sm font-medium text-blue-100 transition hover:bg-white/10 hover:text-white">Log Aktivitas</a>
                    @endif
                    
                    @if(auth()->user()->isDinas() && Route::has('dinas.sekolah.index'))
                        <a href="{{ route('dinas.sekolah.index') }}" @class(['rounded-xl px-4 py-2.5 text-sm font-medium transition', 'bg-white/20 text-white backdrop-blur-sm shadow-sm' => request()->routeIs('dinas.sekolah.*'), 'text-blue-100 hover:bg-white/10 hover:text-white' => !request()->routeIs('dinas.sekolah.*')])>Data Sekolah</a>
                    @endif
                    
                    @if(auth()->user()->isSekolah() && auth()->user()->sekolah_id && Route::has('sekolah.edit'))
                        <a href="{{ route('sekolah.edit', auth()->user()->sekolah_id) }}" @class(['rounded-xl px-4 py-2.5 text-sm font-medium transition', 'bg-white/20 text-white backdrop-blur-sm shadow-sm' => request()->routeIs('sekolah.edit'), 'text-blue-100 hover:bg-white/10 hover:text-white' => !request()->routeIs('sekolah.edit')])>Sekolah Saya</a>
                    @endif
                    
                    <a href="{{ route('password.edit') }}" class="rounded-xl px-4 py-2.5 text-sm font-medium text-blue-100 transition hover:bg-white/10 hover:text-white">Ubah Kata Sandi</a>
                @endauth
            </nav>

            <!-- User Status / Login Button -->
            <div class="ml-auto flex items-center gap-3">
                @auth
                    <div class="hidden items-center gap-3 sm:flex bg-white/10 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/10">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-500 font-bold text-white shadow-sm">
                            {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="max-w-36">
                            <p class="truncate text-xs font-bold text-white">{{ auth()->user()->name }}</p>
                            <p class="truncate text-[11px] text-blue-200">{{ auth()->user()->roleLabel() }}</p>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-xl border border-red-400/30 bg-red-500/20 px-4 py-2 text-xs font-bold text-red-200 transition hover:bg-red-600 hover:text-white shadow-sm">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-md transition hover:bg-blue-500 active:scale-95">Masuk</a>
                @endauth

                <!-- Hamburger Mobile Button -->
                <button type="button" id="mobile-menu-button" aria-label="Buka navigasi" aria-controls="mobile-menu" aria-expanded="false" class="flex h-10 w-10 items-center justify-center rounded-xl border border-white/20 bg-white/10 text-white hover:bg-white/20 lg:hidden">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Navigasi Mobile -->
        <nav id="mobile-menu" aria-label="Navigasi mobile" class="hidden border-t border-white/10 bg-slate-900/95 backdrop-blur-lg px-4 py-3 lg:hidden text-white">
            <div class="flex flex-col gap-1">
                <a href="{{ route('peta.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-white/10">Peta Publik</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-white/10">Dashboard</a>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-white/10">Pengguna</a>
                    @endif
                    @if(auth()->user()->isAdmin() && Route::has('admin.sekolah.index'))
                        <a href="{{ route('admin.sekolah.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-white/10">Data Sekolah</a>
                    @endif
                    @if(auth()->user()->isDinas() && Route::has('dinas.sekolah.index'))
                        <a href="{{ route('dinas.sekolah.index') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-white/10">Data Sekolah</a>
                    @endif
                    @if(auth()->user()->isSekolah() && auth()->user()->sekolah_id && Route::has('sekolah.edit'))
                        <a href="{{ route('sekolah.edit', auth()->user()->sekolah_id) }}" class="rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-white/10">Sekolah Saya</a>
                    @endif
                    <div class="mt-2 border-t border-white/10 px-4 pt-3 text-xs text-blue-200">
                        {{ auth()->user()->name }} — {{ auth()->user()->roleLabel() }}
                    </div>
                    <a href="{{ route('password.edit') }}" class="rounded-lg px-4 py-2.5 text-sm font-medium hover:bg-white/10">Ubah Kata Sandi</a>
                @endauth
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="page-content mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8 flex-1">
        @if(session('success'))
            <div role="status" class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800 shadow-sm">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div role="alert" class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 shadow-sm">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div role="alert" class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 shadow-sm">
                <ul class="list-inside list-disc">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @yield('content')
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const button = document.getElementById('mobile-menu-button');
            const menu = document.getElementById('mobile-menu');
            if (button && menu) {
                button.addEventListener('click', function () {
                    const isOpen = !menu.classList.contains('hidden');
                    menu.classList.toggle('hidden', isOpen);
                    button.setAttribute('aria-expanded', String(!isOpen));
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>