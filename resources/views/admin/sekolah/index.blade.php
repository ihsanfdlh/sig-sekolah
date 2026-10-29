@extends('layouts.app')
@section('title', 'Data Sekolah')
@section('content')
@php
    $jenjangStyle = [
        'SD' => 'bg-red-100 text-red-800 border-red-300',
        'SMP' => 'bg-blue-100 text-blue-800 border-blue-300',
        'MTS' => 'bg-green-100 text-green-800 border-green-300',
        'SMK' => 'bg-orange-100 text-orange-800 border-orange-300',
        'SMA' => 'bg-yellow-100 text-yellow-800 border-yellow-300',
    ];
@endphp
<div class="page-heading">
    <div>
        <h1>Data Sekolah</h1>
        <p>Kelola informasi sekolah yang tampil pada peta publik.</p>
    </div>
    <a href="{{ route('admin.sekolah.create') }}" class="button">
        + Tambah Sekolah
    </a>
</div>

<form method="GET" class="search-form">
    <input
        type="text"
        name="q"
        value="{{ request('q') }}"
        placeholder="Cari nama, NPSN, atau kecamatan"
        class="form-control"
        aria-label="Cari sekolah"
    >
    <button class="button" type="submit">Cari</button>
</form>

<div class="table-card">
    <div class="table-scroll">
        <table class="school-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NPSN</th>
                    <th>Nama</th>
                    <th>Jenjang</th>
                    <th>Status</th>
                    <th>Kecamatan</th>
                    <th>Koordinat</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($sekolahs as $s)
                    <tr>
                        <td>{{ $sekolahs->firstItem() + $loop->index }}</td>
                        <td>{{ $s->npsn }}</td>
                        <td>{{ $s->nama }}</td>
                        <td>
                            <span class="school-badge {{ $jenjangStyle[strtoupper($s->jenjang)] ?? 'bg-green-100 text-gray-800 border-gray-300' }}">
                                {{ $s->jenjang }}
                            </span>
                        </td>
                        <td><span class="school-badge school-badge-status">{{ $s->status }}</span></td>
                        <td>{{ $s->kecamatan }}</td>
                        <td class="coordinates">{{ $s->latitude }}, {{ $s->longitude }}</td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('admin.sekolah.edit', $s) }}">Ubah</a>
                                <form
                                    method="POST"
                                    action="{{ route('admin.sekolah.destroy', $s) }}"
                                    onsubmit="return confirm('Hapus data ini?')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty-state">Belum ada data.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="pagination">{{ $sekolahs->links() }}</div>
@endsection
