<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#F0F7FB">
    <title>Masuk - SIG Sekolah</title>
    <link rel="icon" href="{{ asset('storage/Assets/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg.webp') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --sky: #5F91AC;
            --sky-soft: #A3C4D4;
            --sky-dark: #294B63;
            --sky-pale: #F0F7FB;
            --sky-border: #DCE8EF;
            --ink: #253443;
        }
        html,
        body {
            min-height: 100%;
            margin: 0;
        }
        body.auth-muted-sky {
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", sans-serif;
            color: var(--ink);
            background: linear-gradient(125deg, #F7FAFC 0%, #EAF4FA 52%, #F3F8FB 100%) !important;
            background-image: linear-gradient(125deg, #F7FAFC 0%, #EAF4FA 52%, #F3F8FB 100%) !important;
        }
        body.auth-muted-sky::before,
        body.auth-muted-sky::after {
            display: none !important;
        }
        .login-scene {
            position: fixed;
            inset: 0;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }
        .login-scene::before {
            content: "";
            position: absolute;
            width: 680px;
            height: 680px;
            top: -340px;
            left: -240px;
            border-radius: 50%;
            background: rgba(163, 196, 212, .22);
            filter: blur(5px);
        }
        .login-scene::after {
            content: "";
            position: absolute;
            width: 600px;
            height: 600px;
            bottom: -380px;
            right: -140px;
            border-radius: 50%;
            background: rgba(95, 145, 172, .11);
        }
        .login-scene .scene-logo {
            position: absolute;
            left: 5%;
            bottom: 4%;
            width: clamp(250px, 35vw, 530px);
            opacity: .055;
            filter: grayscale(1);
            transform: rotate(-9deg);
        }
        .login-scene .scene-waves {
            position: absolute;
            inset: auto 0 0;
            width: 100%;
            height: auto;
            min-height: 170px;
        }
        .login-topbar {
            position: relative;
            z-index: 2;
            max-width: 1260px;
            margin: 0 auto;
            padding: 28px 30px 0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }
        .login-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            color: var(--sky-dark);
        }
        .login-brand img {
            width: 52px;
            height: 52px;
            object-fit: contain;
            background: #fff;
            border: 1px solid var(--sky-border);
            border-radius: 13px;
            padding: 5px;
            box-sizing: border-box;
            box-shadow: 0 5px 20px rgba(41, 75, 99, .08);
        }
        .login-brand strong {
            display: block;
            font-size: 23px;
            letter-spacing: -.6px;
            line-height: 1.15;
        }
        .login-brand small {
            display: block;
            margin-top: 4px;
            font-size: 12px;
            color: #69879A;
        }
        .topbar-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: var(--sky-dark);
            background: rgba(255, 255, 255, .72);
            border: 1px solid var(--sky-border);
            padding: 11px 16px;
            border-radius: 11px;
            font-size: 13px;
            font-weight: 650;
            transition: background .2s;
        }
        .topbar-back:hover {
            background: #fff;
        }
        .login-layout {
            position: relative;
            z-index: 1;
            max-width: 1260px;
            min-height: calc(100vh - 120px);
            margin: 0 auto;
            padding: 55px 30px 90px;
            display: grid;
            grid-template-columns: minmax(0, 1.1fr) minmax(370px, 470px);
            align-items: center;
            gap: clamp(40px, 7vw, 115px);
            box-sizing: border-box;
        }
        .login-welcome {
            max-width: 650px;
        }
        .welcome-badge {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 9px 14px;
            background: #E1EFF6;
            border: 1px solid #CDE2EE;
            border-radius: 999px;
            color: #416D86;
            font-size: 11px;
            font-weight: 750;
            letter-spacing: 1px;
        }
        .welcome-badge::before {
            content: "";
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #6B9EB9;
        }
        .login-welcome h1 {
            font-size: clamp(38px, 4.4vw, 65px);
            line-height: 1.13;
            letter-spacing: -2.2px;
            font-weight: 800;
            color: #294B63;
            margin: 24px 0 20px;
        }
        .login-welcome h1 span {
            color: var(--sky);
        }
        .login-welcome p {
            max-width: 540px;
            color: #637D8E;
            font-size: 17px;
            line-height: 1.85;
            margin: 0;
        }
        .welcome-note {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 35px;
            color: #52768C;
            font-size: 14px;
            font-weight: 600;
        }
        .welcome-note svg {
            width: 38px;
            height: 38px;
            padding: 9px;
            box-sizing: border-box;
            background: #E1EFF6;
            border-radius: 11px;
        }
        .login-card {
            background: rgba(255, 255, 255, .89) !important;
            border: 1px solid rgba(209, 228, 238, .95) !important;
            box-shadow: 0 24px 65px rgba(41, 75, 99, .11) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 23px;
            padding: clamp(25px, 3.5vw, 43px);
            color: var(--ink) !important;
        }
        .card-icon {
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #E2EFF6;
            border: 1px solid #CFE3EE;
            border-radius: 17px;
            margin-bottom: 24px;
        }
        .card-icon img {
            width: 47px;
            height: 47px;
            object-fit: contain;
        }
        .login-card h2 {
            font-size: 27px;
            font-weight: 800;
            color: #294B63;
            margin: 0 0 8px;
            letter-spacing: -.7px;
        }
        .login-card .card-desc {
            font-size: 14px;
            line-height: 1.7;
            color: #71899A;
            margin: 0 0 28px;
        }
        .login-card .field-label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #365C74;
        }
        .login-card .login-input {
            display: block;
            width: 100%;
            height: 48px;
            box-sizing: border-box;
            border: 1px solid #D4E3EB !important;
            border-radius: 11px;
            background: #F8FBFD !important;
            color: #294B63 !important;
            padding: 0 14px;
            font-size: 14px;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        .login-card .login-input:focus {
            border-color: #79A7BF !important;
            box-shadow: 0 0 0 3px rgba(95, 145, 172, .14) !important;
        }
        .login-card .login-input::placeholder {
            color: #A1B4C0;
        }
        .login-card .field {
            margin-bottom: 20px;
        }
        .login-card .field-error {
            font-size: 12px;
            color: #B45353;
            margin-top: 6px;
        }
        .password-field {
            position: relative;
        }
        .password-field .login-input {
            padding-right: 52px;
        }
        .toggle-password {
            position: absolute;
            right: 7px;
            top: 6px;
            height: 36px;
            width: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 8px;
            color: #66879B;
            background: transparent;
            cursor: pointer;
        }
        .toggle-password:hover {
            background: #E6F0F6;
        }
        .toggle-password svg {
            width: 19px;
            height: 19px;
        }
        .remember-row {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 13px;
            color: #5B7486;
            margin: 4px 0 23px;
            cursor: pointer;
        }
        .remember-row input {
            width: 16px;
            height: 16px;
            accent-color: #5F91AC;
        }
        .login-submit {
            width: 100%;
            min-height: 49px;
            border: 0;
            border-radius: 11px;
            background: #5F91AC !important;
            color: #fff !important;
            font-weight: 750;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 9px 22px rgba(95, 145, 172, .22);
            transition: background .2s, transform .2s;
        }
        .login-submit:hover {
            background: #4D7E99 !important;
            transform: translateY(-1px);
        }
        .card-bottom {
            text-align: center;
            margin-top: 24px;
            padding-top: 22px;
            border-top: 1px solid #E3EDF3;
        }
        .card-bottom a {
            color: #527F9A;
            font-size: 13px;
            font-weight: 650;
            text-decoration: none;
        }
        .card-bottom a:hover {
            text-decoration: underline;
        }
        @media(max-width:900px) {
            .login-layout {
                grid-template-columns: 1fr;
                gap: 32px;
                padding-top: 50px;
            }
            .login-welcome {
                max-width: 650px;
            }
            .login-welcome h1 {
                font-size: 43px;
            }
            .login-card {
                width: 100%;
                max-width: 520px;
                box-sizing: border-box;
                justify-self: center;
            }
        }
        @media(max-width:540px) {
            .login-topbar {
                padding: 20px 18px 0;
            }
            .login-brand img {
                width: 44px;
                height: 44px;
            }
            .login-brand strong {
                font-size: 18px;
            }
            .login-brand small {
                display: none;
            }
            .topbar-back {
                padding: 10px 11px;
                font-size: 12px;
            }
            .login-layout {
                padding: 48px 18px 70px;
            }
            .login-welcome h1 {
                font-size: 35px;
                letter-spacing: -1.4px;
            }
            .login-welcome p {
                font-size: 14px;
            }
            .welcome-note {
                margin-top: 23px;
                font-size: 12px;
            }
            .login-card {
                padding: 25px 21px;
            }
        }
        /* Desktop: seluruh halaman login pas satu viewport */
        html { height: 100%; }
        body.auth-muted-sky { min-height: 100vh; min-height: 100dvh; }
        @media (min-width: 901px) {
            html, body.auth-muted-sky { height: 100%; overflow: hidden; }
            body.auth-muted-sky { display: flex; flex-direction: column; }
            .login-layout {
                flex: 1 1 auto;
                width: 100%;
                min-height: 0;
                height: 100%;
                padding: clamp(16px, 3vh, 36px) 30px;
                gap: clamp(28px, 5vw, 85px);
                grid-template-columns: minmax(0, 1.1fr) minmax(350px, 450px);
            }
            .login-welcome h1 { font-size: clamp(32px, 3.6vw, 54px); margin: 16px 0 12px; }
            .login-welcome p { font-size: 15px; line-height: 1.65; }
            .welcome-note { margin-top: 22px; }
            .login-card { padding: clamp(20px, 2.5vh, 30px) clamp(24px, 2.5vw, 34px); max-height: calc(100dvh - 32px); overflow-y: auto; }
            .card-icon { width: 50px; height: 50px; margin-bottom: 14px; }
            .card-icon img { width: 39px; height: 39px; }
            .login-card h2 { font-size: 24px; }
            .login-card .card-desc { margin-bottom: 17px; }
            .login-card .field { margin-bottom: 13px; }
            .login-card .login-input { height: 43px; }
            .toggle-password { top: 4px; height: 35px; }
            .remember-row { margin: 2px 0 15px; }
            .login-submit { min-height: 44px; }
            .card-bottom { margin-top: 15px; padding-top: 13px; }
            .login-scene .scene-waves { min-height: 110px; }
        }
        @media (min-width: 901px) and (max-height: 650px) {
            .login-card { padding: 15px 24px; }
            .card-icon { display: none; }
            .login-card .card-desc { margin-bottom: 10px; }
            .login-card .field { margin-bottom: 9px; }
            .login-welcome h1 { font-size: 36px; }
        }
        /* Tablet dan HP: izinkan scroll agar form tidak terpotong */
        @media (max-width: 900px) {
            html, body.auth-muted-sky { height: auto; overflow-x: hidden; overflow-y: auto; }
        }
    </style>
</head>
<body class="auth-muted-sky">
    <div class="login-scene" aria-hidden="true">
        <img class="scene-logo" src="{{ asset('storage/Assets/tut-wuri-handayani.png') }}" alt="">
        <svg class="scene-waves" viewBox="0 0 1440 260" preserveAspectRatio="none" fill="none"
            xmlns="http\://www.w3.org/2000/svg">
            <path d="M0 125C200 205 390 210 605 143C800 81 925 60 1110 129C1250 181 1350 100 1440 71V260H0V125Z"
                fill="#D6EAF4" fill-opacity=".65" />
            <path d="M0 194C225 238 398 225 611 171C824 117 1032 208 1200 172C1304 150 1382 115 1440 121V260H0V194Z"
                fill="#A3C4D4" fill-opacity=".25" />
        </svg>
    </div>
    <main class="login-layout">
        <section class="login-welcome" aria-labelledby="welcome-heading">
            <span class="welcome-badge">PORTAL INFORMASI PENDIDIKAN</span>
            <h1 id="welcome-heading">Pendidikan kuat,<br><span>masa depan hebat.</span></h1>
            <p>Jelajahi dan kelola informasi sekolah dalam satu peta. Bersama, kita dukung pemerataan pendidikan di
                setiap wilayah.</p>
            <div class="welcome-note">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9.5 12 5l9 4.5-9 4.5-9-4.5Z" />
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.5 11.5v5c3.1 2.4 7.9 2.4 11 0v-5M21 10v5" />
                </svg>
                <span>Sistem Informasi Geografis Sekolah</span>
            </div>
        </section>
        <section class="login-card" aria-labelledby="login-heading">
            <div class="card-icon"><img src="{{ asset('storage/Assets/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg.webp') }}" alt=""></div>
            <h2 id="login-heading">Selamat datang</h2>
            <p class="card-desc">Masuk untuk melanjutkan ke akun SIG Sekolah Anda.</p>
            <form method="POST" action="{{ route('login') }}" aria-label="Form masuk">
                @csrf
                <div class="field">
                    <label for="email" class="field-label">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}"
                        autocomplete="username" required autofocus class="login-input"
                        placeholder="Masukkan alamat email">
                    @error('email')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
                <div class="field">
                    <label for="password" class="field-label">Kata Sandi</label>
                    <div class="password-field">
                        <input type="password" id="password" name="password" autocomplete="current-password" required
                            class="login-input" placeholder="Masukkan kata sandi">
                        <button type="button" class="toggle-password" id="toggle-password"
                            aria-label="Tampilkan kata sandi" aria-pressed="false">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="field-error">{{ $message }}</p>
                    @enderror
                </div>
                <label class="remember-row"><input type="checkbox" name="remember" value="1"
                        {{ old('remember') ? 'checked' : '' }}> Ingat saya</label>
                <button type="submit" class="login-submit">Masuk</button>
            </form>
            <div class="card-bottom"><a href="{{ url('/') }}">Kembali ke peta publik</a></div>
        </section>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const button = document.getElementById('toggle-password');
            const password = document.getElementById('password');
            if (!button || !password) return;
            button.addEventListener('click', function() {
                const visible = password.type === 'password';
                password.type = visible ? 'text' : 'password';
                button.setAttribute('aria-pressed', String(visible));
                button.setAttribute('aria-label', visible ? 'Sembunyikan kata sandi' :
                    'Tampilkan kata sandi');
            });
        });
    </script>
</body>
</html>
