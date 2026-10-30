<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - SIG Sekolah</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">

    {{-- Membaca file build/manifest.json secara opsional --}}
    @php
        $manifestPath = public_path('build/manifest.json');
        $manifest = file_exists($manifestPath) ? json_decode(file_get_contents($manifestPath), true) : null;
    @endphp

    @if ($manifest && isset($manifest['resources/css/app.css']['file']))
        <link rel="stylesheet" href="{{ asset('build/' . $manifest['resources/css/app.css']['file']) }}">
        <script type="module" src="{{ asset('build/' . $manifest['resources/js/app.js']['file']) }}"></script>
    @else
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>