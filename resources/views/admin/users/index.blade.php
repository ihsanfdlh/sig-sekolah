@extends('layouts.app')
@section('title', 'Pengguna')
@section('content')
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Pengguna</h1>
        <a href="{{ route('admin.users.create') }}" class="px-4 py-2 bg-blue-700 text-white rounded">+ Tambah Pengguna</a>
    </div>
    <form method="GET" class="mb-4 flex gap-2">
        <select name="role" class="border rounded px-3 py-2">
            <option value="">Semua role</option>
            @foreach (['admin' => 'Admin', 'dinas' => 'Dinas Pendidikan', 'sekolah' => 'Sekolah'] as $nilai => $label)
                <option value="{{ $nilai }}" @selected(request('role') === $nilai)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
        <button class="px-4 py-2 bg-gray-700 text-white rounded">Filter</button>
    </form>
    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-200 text-left">
                <tr>
                    <th class="p-3">Nama</th>
                    <th class="p-3">Email</th>
                    <th class="p-3">Role</th>
                    <th class="p-3">Sekolah</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $u)
                    <tr class="border-t">
                        <td class="p-3">{{ $u->name }}</td>
                        <td class="p-3">{{ $u->email }}</td>
                        <td class="p-3">{{ $u->roleLabel() }}</td>
                        <td class="p-3">{{ $u->sekolah?->nama ?? '-' }}</td>
                        <td class="px-4 py-3">
                            @if ($u->is_active)
                                <span
                                    class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Aktif</span>
                            @else
                                <span
                                    class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if (auth()->user()->isAdmin() && auth()->id() !== $u->id)
                                <form method="POST" action="{{ route('admin.users.toggle-status', $u) }}"
                                    onsubmit="return confirm('Yakin ingin mengubah status akun ini?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="rounded-lg px-4 py-2 text-sm font-semibold text-white {{ $u->is_active ? 'bg-red-600 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700' }}">
                                        {{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            @else
                                <span class="text-sm text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="p-3 flex gap-3">
                            <a href="{{ route('admin.users.edit', $u) }}" class="text-blue-700">Ubah</a>
                            @if (!$u->is(auth()->user()))
                                <form method="POST" action="{{ route('admin.users.destroy', $u) }}"
                                    onsubmit="return confirm('Hapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-4 text-center">Belum ada pengguna.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $users->links() }}</div>
@endsection
