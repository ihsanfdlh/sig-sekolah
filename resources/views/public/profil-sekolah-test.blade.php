
@extends('layouts.app')
@section('title', 'Uji Profil Sekolah')
@section('content')
<div class="max-w-5xl mx-auto px-6 py-10">
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Uji Profil Sekolah Publik</h1>
        <div class="space-y-3">
            <p><strong>ID Sekolah:</strong> {{ $sekolah->id }}</p>
            <p><strong>Nama Sekolah:</strong> {{ $sekolah->nama }}</p>
            <p><strong>NPSN:</strong> {{ $sekolah->npsn }}</p>
            <p><strong>Jenjang:</strong> {{ $sekolah->jenjang }}</p>
            <p><strong>Status:</strong> {{ $sekolah->status }}</p>
            <p><strong>Alamat:</strong> {{ $sekolah->alamat }}</p>
            <p><strong>Kecamatan:</strong> {{ $sekolah->kecamatan }}</p>
            <p><strong>Kepala Sekolah:</strong> {{ $sekolah->profil?->kepala_sekolah ?? '-' }}</p>
            <p><strong>Akreditasi:</strong> {{ $sekolah->profil?->akreditasi ?? '-' }}</p>
            <p><strong>Jumlah Siswa:</strong> {{ $sekolah->profil?->jumlah_siswa ?? '-' }}</p>
            <p><strong>Jumlah Guru:</strong> {{ $sekolah->profil?->jumlah_guru ?? '-' }}</p>
            <p><strong>Logo:</strong> {{ $sekolah->profil?->logo ?? '-' }}</p>
        </div>
        <div class="mt-6 rounded-lg bg-green-100 p-4 text-green-800">
            Blade berhasil dirender jika halaman ini tampil.
        </div>
    </div>
</div>
@endsection
