<!DOCTYPE html>
<html lang="id" class="access-denied-page">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css'])
    <title>403 - Akses Ditolak</title>

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