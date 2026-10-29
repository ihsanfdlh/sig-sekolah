@extends('layouts.app')
@section('title', 'Ubah Sekolah')
@section('content')
<div class="page-heading">
    <div>
        <h1>Ubah Sekolah</h1>
        <p>Perbarui profil sekolah dan titik lokasinya.</p>
    </div>
</div>
<form method="POST" action="{{ route('admin.sekolah.update', $sekolah) }}">
 @csrf
 @method('PUT')
 @include('admin.sekolah._form', ['sekolah' => $sekolah])
</form>
@endsection