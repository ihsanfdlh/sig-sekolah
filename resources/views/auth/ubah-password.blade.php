
@extends('layouts.app')
@section('title', 'Ubah Kata Sandi')
@section('content')
<div class="mx-auto max-w-xl">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Ubah Kata Sandi</h1>
        <p class="mt-1 text-sm text-slate-500">Perbarui kata sandi akun Anda untuk menjaga keamanan.</p>
    </div>
    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="mb-6 flex items-center gap-3 border-b border-slate-100 pb-5">
            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                {{ strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <p class="font-semibold text-slate-900">{{ auth()->user()->name }}</p>
                <p class="text-sm text-slate-500">{{ auth()->user()->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
            @csrf
            @method('PUT')
            <div>
                <label for="password_lama" class="mb-1.5 block text-sm font-semibold text-slate-700">Kata Sandi Lama</label>
                <input type="password" id="password_lama" name="password_lama" required autocomplete="current-password" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                @error('password_lama')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password" class="mb-1.5 block text-sm font-semibold text-slate-700">Kata Sandi Baru</label>
                <input type="password" id="password" name="password" required minlength="8" autocomplete="new-password" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                <p class="mt-1 text-xs text-slate-500">Minimal 8 karakter.</p>
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="password_confirmation" class="mb-1.5 block text-sm font-semibold text-slate-700">Konfirmasi Kata Sandi Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8" autocomplete="new-password" class="w-full rounded-lg border border-slate-300 px-4 py-2.5 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
            </div>
            <div class="flex justify-end gap-3 border-t border-slate-100 pt-5">
                <a href="{{ route('dashboard') }}" class="rounded-lg border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50">Batal</a>
                <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">Simpan Kata Sandi</button>
            </div>
        </form>
    </div>
</div>
@endsection