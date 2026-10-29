@extends('layouts.app')
@section('title', 'Beranda')
@section('content')
<section class="welcome-card">
    <span class="welcome-eyebrow">Sistem Informasi Geografis</span>
    <h1>Temukan sekolah di sekitar Anda.</h1>
    <p>
        Jelajahi persebaran sekolah melalui peta interaktif, atau kelola informasi
        sekolah dalam satu tempat.
    </p>
    <div class="welcome-actions">
        <a href="{{ route('peta.index') }}" class="button">Lihat Peta Sekolah</a>
        <a href="{{ route('admin.sekolah.index') }}" class="button button-secondary">Kelola Data Sekolah</a>
    </div>
</section>
@endsection
