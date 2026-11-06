@extends('layouts.app')
@section('title', 'Log Aktivitas')
@section('content')
<div class="mx-auto max-w-7xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Log Aktivitas Sekolah</h1>
        <p class="mt-1 text-sm text-slate-500">Riwayat perubahan data sekolah oleh pengguna sistem.</p>
    </div>
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="px-5 py-4">No.</th>
                        <th class="px-5 py-4">Pengguna</th>
                        <th class="px-5 py-4">Sekolah</th>
                        <th class="px-5 py-4">Aktivitas</th>
                        <th class="px-5 py-4">Waktu</th>
                        <th class="px-5 py-4">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-50">
                            <td class="px-5 py-4">{{ $logs->firstItem() + $loop->index }}</td>
                            <td class="px-5 py-4 font-medium">{{ $log->nama_pengguna }}</td>
                            <td class="px-5 py-4">{{ $log->nama_sekolah }}</td>
                            <td class="px-5 py-4">
                                <span @class([
                                    'rounded-full px-3 py-1 text-xs font-semibold',
                                    'bg-green-100 text-green-700' => $log->aktivitas === 'tambah',
                                    'bg-blue-100 text-blue-700' => $log->aktivitas === 'ubah',
                                    'bg-red-100 text-red-700' => $log->aktivitas === 'hapus',
                                ])>
                                    {{ ucfirst($log->aktivitas) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">{{ $log->created_at?->format('d/m/Y H:i:s') }}</td>
                            <td class="px-5 py-4">
                                <details class="min-w-52">
                                    <summary class="cursor-pointer font-medium text-blue-600">Lihat Perubahan</summary>
                                    <div class="mt-3 space-y-3 rounded-lg bg-slate-50 p-3 text-xs">
                                        <div>
                                            <p class="mb-1 font-semibold">Sebelum:</p>
                                            <pre class="overflow-x-auto whitespace-pre-wrap">{{ json_encode($log->data_sebelum, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                        <div>
                                            <p class="mb-1 font-semibold">Sesudah:</p>
                                            <pre class="overflow-x-auto whitespace-pre-wrap">{{ json_encode($log->data_sesudah, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    </div>
                                </details>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-10 text-center text-slate-500">Belum ada aktivitas yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-slate-100 p-4">
            {{ $logs->links() }}
        </div>
    </div>
</div>
@endsection
