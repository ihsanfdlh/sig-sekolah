<!DOCTYPE html>
<html lang="id" class="login-page">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#F0F7FB">
    <title>Masuk - SIG Sekolah</title>
    <link rel="icon" href="{{ asset('storage/Assets/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg.webp') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>
<body class="auth-muted-sky">
<div id="login-skeleton-overlay" aria-hidden="true"></div>
    <div class="login-scene" aria-hidden="true">
        <img class="scene-logo" src="{{ asset('storage/Assets/tut-wuri-handayani.png') }}" alt="">
        <svg class="scene-waves" viewBox="0 0 1440 260" preserveAspectRatio="none" fill="none"
            xmlns="http\\://www.w3.org/2000/svg">
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
<script id="login-skeleton-js">
(function(){
const overlay=document.getElementById('login-skeleton-overlay');
if(!overlay)return;
let closed=false;
function block(element,options={}){
if(!element||!element.getClientRects().length)return;
const r=element.getBoundingClientRect();
if(r.width<2||r.height<2||r.bottom<0||r.top>innerHeight)return;
const el=document.createElement('div');
el.className=options.card?'login-sk-card':'login-sk-block';
el.style.left=Math.max(0,r.left)+'px';
el.style.top=Math.max(0,r.top)+'px';
el.style.width=Math.max(0,Math.min(r.right,innerWidth)-Math.max(0,r.left))+'px';
el.style.height=Math.max(0,Math.min(r.bottom,innerHeight)-Math.max(0,r.top))+'px';
if(options.radius)el.style.borderRadius=options.radius;
overlay.appendChild(el);
}
function draw(){
if(closed)return;
overlay.replaceChildren();
block(document.querySelector('.login-card'),{card:true});
const items=['.login-brand img','.login-brand strong','.login-brand small','.topbar-back','.welcome-badge','.login-welcome h1','.login-welcome p','.welcome-note svg','.welcome-note span','.card-icon','.login-card h2','.card-desc','.field-label','.login-input','.toggle-password','.remember-row','.login-submit','.card-bottom a'];
items.forEach(selector=>document.querySelectorAll(selector).forEach(el=>block(el,{radius:el.matches('.login-brand img,.card-icon')?'13px':null})));
}
function close(){
if(closed)return;
closed=true;
overlay.classList.add('is-done');
setTimeout(()=>overlay.remove(),350);
}
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',draw,{once:true});else draw();
window.addEventListener('load',close,{once:true});
window.addEventListener('pageshow',event=>{if(event.persisted)close()});
setTimeout(close,8000);
})();
</script>
</body>
</html>
