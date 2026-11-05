
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') - SIG Sekolah</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">
    <header class="sticky top-0 z-[1000] border-b border-slate-200 bg-white/95 shadow-sm backdrop-blur">
        <div class="mx-auto flex min-h-[72px] max-w-7xl flex-wrap items-center gap-4 px-4 py-3 sm:px-6 lg:px-8">
            <a href="{{ route('peta.index') }}" class="flex min-w-0 items-center gap-3">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-600 text-white shadow-sm">
                    <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z"/>
                        <circle cx="12" cy="10" r="2.5"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <h1 class="text-base font-bold tracking-tight text-slate-900">SIG Sekolah</h1>
                    <p class="hidden text-xs text-slate-500 sm:block">Sistem Informasi Geografis Sekolah</p>
                </div>
            </a>
            <nav class="hidden items-center gap-1 lg:ml-6 lg:flex" aria-label="Navigasi utama">
                <a href="{{ route('peta.index') }}" @class(['rounded-lg px-4 py-2.5 text-sm font-medium transition', 'bg-blue-50 text-blue-700' => request()->routeIs('peta.index'), 'text-slate-600 hover:bg-slate-100' => !request()->routeIs('peta.index')])>Peta Publik</a>
                @auth
                    <a href="{{ route('dashboard') }}" @class(['rounded-lg px-4 py-2.5 text-sm font-medium transition', 'bg-blue-50 text-blue-700' => request()->routeIs('dashboard'), 'text-slate-600 hover:bg-slate-100' => !request()->routeIs('dashboard')])>Dashboard</a>
                    @if(auth()->user()->isAdmin() && Route::has('admin.sekolah.index'))
                        <a href="{{ route('admin.sekolah.index') }}" @class(['rounded-lg px-4 py-2.5 text-sm font-medium transition', 'bg-blue-50 text-blue-700' => request()->routeIs('admin.sekolah.*'), 'text-slate-600 hover:bg-slate-100' => !request()->routeIs('admin.sekolah.*')])>Data Sekolah</a>
                    @endif
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}" class="rounded-lg px-4 py-3 text-sm font-medium hover:bg-blue-50">Pengguna</a>
                    @endif
                    @if(auth()->user()->isDinas() && Route::has('dinas.sekolah.index'))
                        <a href="{{ route('dinas.sekolah.index') }}" @class(['rounded-lg px-4 py-2.5 text-sm font-medium transition', 'bg-blue-50 text-blue-700' => request()->routeIs('dinas.sekolah.*'), 'text-slate-600 hover:bg-slate-100' => !request()->routeIs('dinas.sekolah.*')])>Data Sekolah</a>
                    @endif
                    @if(auth()->user()->isSekolah() && auth()->user()->sekolah_id && Route::has('sekolah.edit'))
                        <a href="{{ route('sekolah.edit', auth()->user()->sekolah_id) }}" @class(['rounded-lg px-4 py-2.5 text-sm font-medium transition', 'bg-blue-50 text-blue-700' => request()->routeIs('sekolah.edit'), 'text-slate-600 hover:bg-slate-100' => !request()->routeIs('sekolah.edit')])>Sekolah Saya</a>
                    @endif
                @endauth
            </nav>
            <div class="ml-auto flex items-center gap-3">
                @auth
                    <div class="hidden items-center gap-3 sm:flex">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                            {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="max-w-36">
                            <p class="truncate text-sm font-semibold">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-slate-500">{{ auth()->user()->roleLabel() }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg border border-red-100 bg-red-50 px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:bg-red-100">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">Masuk</a>
                @endauth
                <button type="button" id="mobile-menu-button" aria-label="Buka navigasi" aria-controls="mobile-menu" aria-expanded="false" class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-100 lg:hidden">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
        <nav id="mobile-menu" aria-label="Navigasi mobile" class="hidden border-t border-slate-200 bg-white px-4 py-3 lg:hidden">
            <div class="flex flex-col gap-1">
                <a href="{{ route('peta.index') }}" class="rounded-lg px-4 py-3 text-sm font-medium hover:bg-blue-50">Peta Publik</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-lg px-4 py-3 text-sm font-medium hover:bg-blue-50">Dashboard</a>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.users.index') }}" class="rounded-lg px-4 py-3 text-sm font-medium hover:bg-blue-50">Pengguna</a>
                    @endif
                    @if(auth()->user()->isAdmin() && Route::has('admin.sekolah.index'))
                        <a href="{{ route('admin.sekolah.index') }}" class="rounded-lg px-4 py-3 text-sm font-medium hover:bg-blue-50">Data Sekolah</a>
                    @endif
                    @if(auth()->user()->isDinas() && Route::has('dinas.sekolah.index'))
                        <a href="{{ route('dinas.sekolah.index') }}" class="rounded-lg px-4 py-3 text-sm font-medium hover:bg-blue-50">Data Sekolah</a>
                    @endif
                    @if(auth()->user()->isSekolah() && auth()->user()->sekolah_id && Route::has('sekolah.edit'))
                        <a href="{{ route('sekolah.edit', auth()->user()->sekolah_id) }}" class="rounded-lg px-4 py-3 text-sm font-medium hover:bg-blue-50">Sekolah Saya</a>
                    @endif
                    <div class="mt-2 border-t border-slate-100 px-4 pt-3 text-sm text-slate-500">
                        {{ auth()->user()->name }} — {{ auth()->user()->roleLabel() }}
                    </div>
                @endauth
            </div>
        </nav>
    </header>
    <main class="page-content mx-auto w-full max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        @if(session('success'))
            <div role="status" class="mb-4 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div role="alert" class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">{{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div role="alert" class="mb-4 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800">
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
