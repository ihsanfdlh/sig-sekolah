<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#142d3b">
    <title>Masuk - SIG Sekolah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="app-page auth-page">
    <div class="auth-education-art" aria-hidden="true">
        <svg viewBox="0 0 1600 1000" preserveAspectRatio="xMidYMid slice" fill="none">
            <defs>
                <linearGradient id="school-front" x1="800" y1="410" x2="800" y2="760" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#D1FAE5" stop-opacity=".35"/>
                    <stop offset="1" stop-color="#A7F3D0" stop-opacity=".08"/>
                </linearGradient>
                <linearGradient id="school-roof" x1="800" y1="270" x2="800" y2="420" gradientUnits="userSpaceOnUse">
                    <stop stop-color="#C7D2FE" stop-opacity=".5"/>
                    <stop offset="1" stop-color="#A7F3D0" stop-opacity=".22"/>
                </linearGradient>
            </defs>
            <circle cx="1280" cy="170" r="96" fill="#FDE68A" fill-opacity=".12"/>
            <path d="M0 700C226 644 378 682 578 714c245 39 468-59 680-43 126 10 227 52 342 37v292H0V700Z" fill="#0F172A" fill-opacity=".2"/>
            <path d="M0 792c222-58 386-20 586 12 249 40 463-52 680-38 122 8 218 46 334 34v200H0V792Z" fill="#0B1728" fill-opacity=".3"/>

            <g stroke="#D1FAE5" stroke-linecap="round" stroke-linejoin="round" stroke-opacity=".48" stroke-width="5">
                <path d="M278 708h1044"/>
                <path d="m520 431 280-173 280 173"/>
                <path d="M568 421v287m464-287v287"/>
                <path d="M608 450h384v258H608z" fill="url(#school-front)"/>
                <path d="M555 431h490v33H555z" fill="#D1FAE5" fill-opacity=".12"/>
                <path d="M735 708V569a65 65 0 0 1 130 0v139" fill="#C7D2FE" fill-opacity=".13"/>
                <path d="M648 500h42v51h-42zm84 0h42v51h-42zm136 0h42v51h-42zm84 0h42v51h-42z"/>
                <path d="M648 590h42v51h-42zm284 0h42v51h-42z"/>
                <path d="M800 294v-52m-30 20h60"/>
                <path d="M786 343h28"/>
                <path d="M296 707v-97h70v97m-94-97 59-72 59 72"/>
                <path d="M292 654h18v22h-18zm38 0h18v22h-18z"/>
                <path d="M1262 707v-97h70v97m-94-97 59-72 59 72"/>
                <path d="M1258 654h18v22h-18zm38 0h18v22h-18z"/>
                <path d="M435 707v-55m-18 17h36m-18-17-19-20m19 20 20-20m440 55v-38m-15 12h30m-15-12-17-18m17 18 18-18"/>
                <path d="M438 520c22-20 54-20 76 0m-65-13c17-15 40-15 57 0m-50-13c12-10 29-10 41 0"/>
            </g>

            <g stroke="#BFDBFE" stroke-linecap="round" stroke-linejoin="round" stroke-opacity=".52" stroke-width="4">
                <path d="m1060 250 78 28-78 28-78-28 78-28Z" fill="url(#school-roof)"/>
                <path d="M1010 294v39c30 24 70 24 100 0v-39m28-16v46"/>
                <path d="M220 344c0-22 18-40 40-40 9-30 50-37 69-10 28-1 48 20 48 48H220Z"/>
                <path d="M1295 390c0-17 14-31 31-31 7-24 40-29 55-8 22-1 38 16 38 39h-124Z"/>
            </g>

            <g fill="#A7F3D0" fill-opacity=".36">
                <circle cx="487" cy="326" r="5"/>
                <circle cx="1160" cy="451" r="6"/>
                <circle cx="369" cy="468" r="4"/>
                <circle cx="1211" cy="324" r="4"/>
            </g>
        </svg>
        <img
            src="{{ asset('storage/Assets/tut-wuri-handayani.png') }}"
            alt=""
            aria-hidden="true"
        >
    </div>

    <main class="auth-layout">
        <section class="auth-welcome" aria-labelledby="welcome-heading">
            <span class="auth-welcome-badge"><span></span> PORTAL INFORMASI PENDIDIKAN</span>
            <h2 id="welcome-heading">Pendidikan kuat, <span>masa depan hebat.</span></h2>
            <p>
                Jelajahi dan kelola informasi sekolah dalam satu peta.
                Bersama, kita dukung pemerataan pendidikan di setiap wilayah.
            </p>
            <div class="auth-welcome-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.5 12 5l9 4.5-9 4.5-9-4.5Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.5 11.5v5c3.1 2.4 7.9 2.4 11 0v-5M21 10v5"/>
                </svg>
                <span>Sistem Informasi Geografis Sekolah</span>
            </div>
        </section>

        <section class="auth-card" aria-labelledby="login-heading">
            <div class="auth-card-heading">
                <div class="auth-brand-mark">
                    <svg viewBox="0 0 24 24" width="27" height="27" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m3 9 9-5 9 5-9 5-9-5Z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 11.3V16c2.8 2.2 7.2 2.2 10 0v-4.7M21 9v6"/>
                    </svg>
                </div>
                <h1 id="login-heading">Selamat datang</h1>
                <p>Masuk untuk melanjutkan ke akun SIG Sekolah Anda.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-4" aria-label="Form masuk">
                @csrf
                <div>
                    <label for="email" class="mb-1.5 block">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="username"
                        required
                        autofocus
                        class="form-control"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-200">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="password" class="mb-1.5 block">Kata Sandi</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        autocomplete="current-password"
                        required
                        class="form-control"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-200">{{ $message }}</p>
                    @enderror
                </div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="remember" value="1"> Ingat saya
                </label>
                <button type="submit" class="button auth-submit w-full">Masuk</button>
            </form>

            <a href="{{ url('/') }}" class="auth-back-link mt-5 block text-sm font-medium">
                Kembali ke peta publik
            </a>
        </section>
    </main>
</body>

</html>
