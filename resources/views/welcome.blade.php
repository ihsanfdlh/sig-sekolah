{{-- resources/views/peta/header-sekolah.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIG Sekolah | Sistem Informasi Geografis Sekolah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root{--primary:#5F91AC;--secondary:#A3C4D4;--ink:#25445a;--muted:#52677a;--light:#f4faff}
        *{box-sizing:border-box}html{scroll-behavior:smooth}body{margin:0;font-family:'Plus Jakarta Sans',Arial,sans-serif;color:var(--ink);background:#fff}
        a{text-decoration:none;color:inherit}button{font:inherit}
        .site-header{position:relative;z-index:10;background:linear-gradient(100deg,#f6fbff 0%,#eaf5fc 100%);box-shadow:0 3px 20px rgba(55,114,146,.12)}
        .nav-inner{width:min(100% - 64px,1880px);margin:auto;min-height:104px;display:flex;align-items:center;justify-content:space-between;gap:28px}
        .brand{display:flex;align-items:center;gap:19px;flex-shrink:0}.brand-logo{width:75px;height:75px;object-fit:contain}.brand-divider{height:59px;width:2px;background:#c7dce9}.brand-title{font-size:29px;font-weight:800;letter-spacing:-1.2px;line-height:1.2}.brand-subtitle{font-size:13px;font-weight:500;margin-top:5px;white-space:nowrap}
        .nav-links{display:flex;align-items:center;gap:10px}.nav-link{display:flex;align-items:center;gap:10px;padding:14px 17px;border-radius:11px;font-size:15px;font-weight:700;white-space:nowrap;transition:background .2s,color .2s}.nav-link:hover{background:#e0f0f9}.nav-link.active{background:var(--primary);color:#fff}.nav-link svg,.login svg{width:21px;height:21px;stroke:currentColor;stroke-width:1.8;fill:none;stroke-linecap:round;stroke-linejoin:round}.login{background:#81b4d0;color:#fff;border-radius:11px;padding:15px 27px;font-size:16px;font-weight:700;display:flex;gap:10px;align-items:center;transition:background .2s}.login:hover{background:var(--primary)}.mobile-toggle{display:none;border:0;background:transparent;color:var(--ink);cursor:pointer}
        .hero{min-height:560px;position:relative;overflow:hidden;background:linear-gradient(112deg,#e2f3fd 0%,#f8fbff 43%,#e4f3fc 100%)}.hero::before{content:"";position:absolute;inset:0;background:radial-gradient(ellipse at 70% 26%,rgba(255,255,255,.68),transparent 52%);pointer-events:none}
        .hero-inner{width:min(100% - 120px,1740px);margin:auto;position:relative;z-index:2;padding-top:94px;padding-bottom:170px}.hero-content{max-width:820px}.hero h1{font-size:clamp(36px,3.25vw,62px);line-height:1.19;letter-spacing:-2.1px;margin:0;font-weight:800;color:#2b4e66}.hero-rule{width:105px;height:7px;background:#61accd;border-radius:10px;margin:25px 0 28px}.hero p{font-size:clamp(17px,1.4vw,25px);line-height:1.7;color:#4c6277;max-width:800px;margin:0 0 42px}.hero-actions{display:flex;gap:25px;flex-wrap:wrap}.btn{min-height:64px;display:inline-flex;align-items:center;justify-content:center;gap:13px;border-radius:13px;padding:17px 32px;font-size:18px;font-weight:700;box-shadow:0 3px 2px rgba(41,82,112,.12);transition:transform .2s,background .2s}.btn:hover{transform:translateY(-2px)}.btn svg{width:23px;height:23px;stroke:currentColor;stroke-width:1.9;fill:none;stroke-linecap:round;stroke-linejoin:round}.btn-primary{background:var(--primary);color:white}.btn-primary:hover{background:#4b7e99}.btn-secondary{background:#e0f0fa;color:#25445a}.btn-secondary:hover{background:#cce7f6}
        .hero-emblem{position:absolute;right:7%;top:6%;width:min(35vw,590px);height:auto;opacity:.13;filter:grayscale(1) sepia(.45) hue-rotate(155deg) saturate(1.6);pointer-events:none;z-index:1}.wave{position:absolute;left:0;bottom:-1px;width:100%;height:205px;z-index:1;pointer-events:none}.wave-back{fill:#b9dced;opacity:.85}.wave-front{fill:#7eb4d0;opacity:.82}
        .below{padding:42px 24px;text-align:center;background:#fff;color:#647b8d;font-size:14px}
        @media(max-width:1450px){.nav-inner{width:min(100% - 40px,1880px);gap:14px}.brand-title{font-size:24px}.brand-subtitle{font-size:11px}.brand-logo{width:61px;height:61px}.nav-link{font-size:13px;padding:12px 10px;gap:7px}.nav-link svg{width:18px;height:18px}.login{padding:13px 17px;font-size:14px}.hero-inner{width:min(100% - 90px,1740px)}}
        @media(max-width:1050px){.nav-inner{min-height:84px}.mobile-toggle{display:block}.nav-links{display:none;position:absolute;top:100%;left:0;right:0;background:#f3faff;padding:14px 24px 24px;box-shadow:0 15px 20px rgba(30,70,90,.13);flex-direction:column;align-items:stretch}.nav-links.open{display:flex}.nav-link{padding:14px 18px}.login{margin-left:auto}.hero-emblem{right:-5%;top:15%;width:50vw;opacity:.09}.hero-inner{padding-top:86px}}
        @media(max-width:640px){.nav-inner{width:calc(100% - 28px);min-height:72px;gap:10px}.brand{gap:9px}.brand-logo{width:45px;height:45px}.brand-divider{height:38px;width:1px}.brand-title{font-size:18px;letter-spacing:-.6px}.brand-subtitle{font-size:9px;white-space:normal}.login{font-size:0;padding:10px 12px}.login svg{width:20px;height:20px}.hero{min-height:540px}.hero-inner{width:calc(100% - 42px);padding-top:70px;padding-bottom:150px}.hero h1{font-size:35px;letter-spacing:-1.3px}.hero p{font-size:16px;margin-bottom:28px}.hero-rule{margin:22px 0}.hero-actions{gap:12px}.btn{font-size:14px;min-height:52px;padding:13px 16px}.btn svg{width:19px;height:19px}.hero-emblem{width:75vw;right:-24%;top:20%;opacity:.07}.wave{height:125px}}
    </style>
</head>
<body>
@php
    // Letakkan logo asli di public/images/tut-wuri-handayani.png
    $logo = asset('storage/Assets/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg.webp');
@endphp
<header class="site-header">
    <div class="nav-inner">
        <a class="brand" href="{{ url('/') }}" aria-label="SIG Sekolah - Beranda">
            <img class="brand-logo" src="{{ $logo }}" alt="Logo Tut Wuri Handayani">
            <span class="brand-divider"></span>
            <span><span class="brand-title" style="display:block">SIG Sekolah</span><span class="brand-subtitle" style="display:block">Sistem Informasi Geografis Sekolah</span></span>
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
</body>
</html>
