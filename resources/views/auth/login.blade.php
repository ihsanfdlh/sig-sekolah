<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk - SIG Sekolah</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="app-page auth-page">
    <div class="auth-card">
        <div class="auth-brand-mark mb-5">
            <svg viewBox="0 0 24 24" width="27" height="27" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="m3 9 9-5 9 5-9 5-9-5Z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 11.3V16c2.8 2.2 7.2 2.2 10 0v-4.7M21 9v6"/>
            </svg>
        </div>
        <h1 class="text-2xl font-bold text-slate-900">SIG Sekolah</h1>
        <p class="mb-6 mt-2 text-sm text-slate-600">Masuk untuk mengelola data sekolah.</p>
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="mb-1.5 block">Email</label>
                <input type="email" id="email" name="email"
                    value="{{ old('email') }}" required autofocus
                    class="form-control">
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="mb-1.5 block">Kata Sandi</label>
                <input type="password" id="password" name="password" required class="form-control">
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <label class="flex items-center gap-2">
                <input type="checkbox" name="remember" value="1"> Ingat saya
            </label>
            <button class="button w-full">Masuk</button>
        </form>
        <a href="{{ url('/') }}" class="mt-5 block text-sm font-medium text-blue-700">Kembali ke peta publik</a>
    </div>
</body>
</html>