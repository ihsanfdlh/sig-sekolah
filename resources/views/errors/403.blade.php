<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Ditolak</title>
    <style>
        * { box-sizing: border-box; }
        body {
            display: grid;
            min-height: 100vh;
            place-items: center;
            margin: 0;
            padding: 24px;
            background:
                radial-gradient(ellipse at 10% 0%, rgba(180, 218, 196, .36), transparent 34%),
                radial-gradient(ellipse at 100% 20%, rgba(199, 210, 254, .32), transparent 38%),
                linear-gradient(145deg, #f3f7f5, #eef2f8 58%, #f7f8f6);
            color: #192a22;
            font-family: system-ui, sans-serif;
        }
        .box {
            width: min(100%, 30rem);
            padding: clamp(24px, 6vw, 40px);
            border: 1px solid rgba(255, 255, 255, .86);
            border-top: 4px solid #c59a47;
            border-radius: 20px;
            background: rgba(255, 255, 255, .7);
            box-shadow: 0 22px 60px rgba(25, 55, 39, .14);
            backdrop-filter: blur(22px);
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
        h1 { margin: 0; color: #174d3c; font-size: clamp(26px, 5vw, 34px); }
        p { color: #68786f; line-height: 1.65; }
        a {
            display: inline-flex;
            min-height: 42px;
            align-items: center;
            padding: 0 16px;
            border-radius: 10px;
            background: linear-gradient(135deg, #247555, #312e81);
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