@extends('layouts.app')
@section('title', 'Tambah Sekolah')
@section('content')
<div class="page-heading">
    <div>
        <h1>Tambah Sekolah</h1>
        <p>Lengkapi profil sekolah dan tentukan titik lokasinya.</p>
    </div>
</div>
<form method="POST" action="{{ route('admin.sekolah.store') }}">
 @csrf
 @include('admin.sekolah._form', ['sekolah' => new \App\Models\Sekolah])
</form>
@endsection