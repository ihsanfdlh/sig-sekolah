{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="id" class="welcome-page">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@vite(['resources/css/app.css'])
<title>SIG Sekolah | Sistem Informasi Geografis Sekolah</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">


</head>
<body>
<div id="page-skeleton" class="skeleton-overlay" role="status" aria-label="Memuat halaman" aria-live="polite">
<div class="skeleton-nav"><span class="sk-block sk-logo"></span><div class="sk-brand"><span class="sk-block"></span><span class="sk-block"></span></div><div class="sk-navlinks"><span class="sk-block"></span><span class="sk-block"></span><span class="sk-block"></span><span class="sk-block"></span><span class="sk-block"></span></div><span class="sk-block sk-login"></span></div>
<div class="skeleton-hero"><span class="sk-block sk-heading"></span><span class="sk-block sk-heading second"></span><span class="sk-block sk-rule"></span><span class="sk-block sk-text"></span><span class="sk-block sk-text short"></span><div class="sk-buttons"><span class="sk-block sk-button"></span><span class="sk-block sk-button"></span></div></div>
</div>
@php
// Letakkan logo asli di public/images/tut-wuri-handayani.png
$logo = asset('storage/Assets/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg.webp');
@endphp
<header class="site-header">
<div class="nav-inner">
<a class="brand" href="{{ url('/') }}" aria-label="SIG Sekolah - Beranda">
<img class="brand-logo" src="{{ $logo }}" alt="Logo Tut Wuri Handayani">
<span class="brand-divider"></span>
<span><span class="brand-title">SIG Sekolah</span><span class="brand-subtitle">Sistem Informasi Geografis Sekolah</span></span>
</a>
<nav class="nav-links" id="nav-links" aria-label="Navigasi utama">
<a href="{{ url('/') }}" class="nav-link active"><svg viewBox="0 0 24 24"><path d="m3 10 9-7 9 7v10h-6v-7H9v7H3z"/></svg>Beranda</a>
<a href="{{ url('/') }}#peta" class="nav-link"><svg viewBox="0 0 24 24"><path d="m3 6 6-3 6 3 6-3v15l-6 3-6-3-6 3zM9 3v15m6-12v15"/></svg>Peta Sekolah</a>
<a href="{{ url('/wilayah') }}" class="nav-link"><svg viewBox="0 0 24 24"><path d="m12 2 10 6-10 6L2 8zm-10 11 10 6 10-6M2 18l10 5 10-5"/></svg>Wilayah</a>
<a href="{{ url('/data-sekolah') }}" class="nav-link"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M8 8h9M8 12h9M8 16h9"/></svg>Data Sekolah</a>
<a href="{{ url('/statistik') }}" class="nav-link"><svg viewBox="0 0 24 24"><path d="M3 21h18M6 18v-5h3v5m3 0V8h3v10m3 0V4h3v14"/></svg>Statistik</a>
<a href="{{ url('/tentang') }}" class="nav-link"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 11v6m0-10v.2"/></svg>Tentang</a>
</nav>
<a class="login" href="{{ url('/login') }}"><svg viewBox="0 0 24 24"><circle cx="12" cy="7" r="4"/><path d="M4 21v-2a8 8 0 0 1 16 0v2z"/></svg>Login</a>
<button class="mobile-toggle" type="button" id="mobile-toggle" aria-controls="nav-links" aria-expanded="false" aria-label="Buka menu"><svg width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 7h16M4 12h16M4 17h16"/></svg></button>
</div>
</header>
<main>
<section class="hero">
<img class="hero-emblem" src="{{ $logo }}" alt="" aria-hidden="true">
<div class="hero-inner">
<div class="hero-content">
<h1>Sistem Informasi Geografis<br>Persebaran Sekolah</h1>
<div class="hero-rule"></div>
<p>Menampilkan data, lokasi, dan informasi sekolah secara spasial, akurat, dan mudah diakses.</p>
<div class="hero-actions">
<a class="btn btn-primary" href="{{ url('/') }}#peta"><svg viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>Lihat Peta Sekolah <span aria-hidden="true">→</span></a>
<a class="btn btn-secondary" href="{{ url('/data-sekolah') }}"><svg viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 2 18 2 18 0V5M3 12c0 2 18 2 18 0"/></svg>Lihat Data Sekolah</a>
</div>
</div>
</div>
<svg class="wave" viewBox="0 0 1440 210" preserveAspectRatio="none" aria-hidden="true"><path class="wave-back" d="M0 100 C210 180 410 225 660 135 S980 20 1170 100 S1340 75 1440 20 L1440 210 L0 210 Z"/><path class="wave-front" d="M0 185 C220 250 440 250 680 165 S950 120 1090 155 S1300 110 1440 60 L1440 210 L0 210 Z"/></svg>
</section>
</main>
<script>
const menuButton = document.getElementById('mobile-toggle');
const navLinks = document.getElementById('nav-links');
menuButton.addEventListener('click', () => {
const isOpen = navLinks.classList.toggle('open');
menuButton.setAttribute('aria-expanded', String(isOpen));
});
navLinks.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
navLinks.classList.remove('open');
menuButton.setAttribute('aria-expanded', 'false');
}));
</script>
<script>
(function(){
const overlay=document.getElementById('page-skeleton');
let done=false;
function hideSkeleton(){if(done)return;done=true;overlay.classList.add('is-hidden');window.setTimeout(()=>{overlay.hidden=true;},400);}
if(document.readyState==='complete'){hideSkeleton();}else{window.addEventListener('load',hideSkeleton,{once:true});}
window.addEventListener('pageshow',function(event){if(event.persisted){done=false;overlay.hidden=true;}});
document.querySelectorAll('a[href]').forEach(function(link){link.addEventListener('click',function(event){if(event.defaultPrevented||event.button!==0||event.ctrlKey||event.metaKey||event.shiftKey||event.altKey||link.target==='_blank'||link.hasAttribute('download'))return;const target=new URL(link.href,location.href);if(target.origin!==location.origin||target.pathname===location.pathname&&target.search===location.search&&target.hash)return;done=false;overlay.hidden=false;requestAnimationFrame(()=>overlay.classList.remove('is-hidden'));});});
})();
</script>
</body>
</html>
