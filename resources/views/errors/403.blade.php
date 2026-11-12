<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Ditolak</title>
    <style>
        * { box-sizing: border-box; }
        body {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            display: grid;
            min-height: 100vh;
            place-items: center;
            margin: 0;
            padding: 24px;
            background:
                radial-gradient(ellipse at 12% 12%, rgba(94, 169, 130, .34), transparent 36%),
                radial-gradient(ellipse at 88% 18%, rgba(129, 140, 248, .38), transparent 38%),
                linear-gradient(125deg, #0f2927, #142d3b 52%, #252b55);
            color: #fff;
            font-family: system-ui, sans-serif;
        }
        body::before {
            position: absolute;
            z-index: -1;
            right: -60px;
            bottom: -110px;
            width: min(52vw, 520px);
            aspect-ratio: 1;
            background: url('{{ asset('storage/Assets/tut-wuri-handayani.png') }}') center / contain no-repeat;
            content: "";
            opacity: .1;
            filter: grayscale(1) brightness(0) invert(1);
        }
        .box {
            position: relative;
            width: min(100%, 30rem);
            padding: clamp(24px, 6vw, 40px);
            border: 1px solid rgba(255, 255, 255, .48);
            border-radius: 20px;
            background: rgba(255, 255, 255, .16);
            box-shadow: 0 28px 80px rgba(4, 12, 27, .32), inset 0 1px 0 rgba(255, 255, 255, .22);
            backdrop-filter: blur(28px) saturate(145%);
        }
        .eyebrow {
            display: inline-flex;
            margin-bottom: 12px;
            padding: 5px 10px;
            border: 1px solid rgba(49, 46, 129, .1);
            border-radius: 999px;
            background: rgba(49, 46, 129, .08);
            color: #312e81;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .06em;
        }
        h1 { margin: 0; color: #fff; font-size: clamp(26px, 5vw, 34px); }
        p { color: rgba(226, 232, 240, .82); line-height: 1.65; }
        a {
            display: inline-flex;
            min-height: 42px;
            align-items: center;
            padding: 0 16px;
            border-radius: 10px;
            background: linear-gradient(110deg, #247555, #312e81);
            color: white;
            font-size: 14px;
            font-weight: 650;
            text-decoration: none;
            transition: transform .18s ease, box-shadow .18s ease;
        }
        a:hover { transform: translateY(-1px); box-shadow: 0 8px 18px rgba(31, 57, 74, .16); }
        a:focus-visible { outline: 3px solid rgba(197, 154, 71, .65); outline-offset: 3px; }
    </style>
</head>
<body>
    <div class="box">
        <span class="eyebrow">SIG Sekolah</span>
        <h1>403 - Akses Ditolak</h1>
        <p>Anda tidak memiliki hak akses ke halaman atau data ini.</p>
        <a href="{{ auth()->check() ? route('dashboard') : url('/') }}">Kembali</a>
    </div>
</body>
</html>