@extends('layouts.app')
@section('title', 'Log Aktivitas')
@section('content')
    <div class="log-page relative mx-auto max-w-7xl space-y-6" id="log-page">
        <div>
            <h1 class="log-title text-2xl">Log Aktivitas Sekolah</h1>
            <p class="log-subtitle mt-1 text-sm">Riwayat perubahan data sekolah oleh pengguna sistem.</p>
        </div>
        <div class="log-card">
            <div class="overflow-x-auto">
                <table class="log-table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Pengguna</th>
                            <th>Sekolah</th>
                            <th>Aktivitas</th>
                            <th>Waktu</th>
                            <th>Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $logs->firstItem() + $loop->index }}</td>
                                <td class="log-user">{{ $log->nama_pengguna }}</td>
                                <td>{{ $log->nama_sekolah }}</td>
                                <td><span @class([
                                    'log-badge',
                                    'log-badge-add' => $log->aktivitas === 'tambah',
                                    'log-badge-edit' => $log->aktivitas === 'ubah',
                                    'log-badge-delete' => $log->aktivitas === 'hapus',
                                    'log-badge-other' => !in_array($log->aktivitas, [
                                        'tambah',
                                        'ubah',
                                        'hapus',
                                    ]),
                                ])>{{ ucfirst($log->aktivitas) }}</span></td>
                                <td class="whitespace-nowrap">{{ $log->created_at?->format('d/m/Y H:i:s') }}</td>
                                <td>
                                    <details class="log-details">
                                        <summary>Lihat Perubahan</summary>
                                        <div class="log-details-panel space-y-4">
                                            <div>
                                                <p class="log-json-label">Sebelum:</p>
                                                <pre>{{ json_encode($log->data_sebelum, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                            <div>
                                                <p class="log-json-label">Sesudah:</p>
                                                <pre>{{ json_encode($log->data_sesudah, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                            </div>
                                        </div>
                                    </details>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="log-empty">Belum ada aktivitas yang tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="log-pagination">{{ $logs->links() }}</div>
        </div>
        <div class="log-skeleton" id="log-skeleton" aria-hidden="true">
            <div class="log-skeleton-line log-skeleton-title"></div>
            <div class="log-skeleton-line log-skeleton-subtitle"></div>
            <div class="log-skeleton-table">
                <div class="log-skeleton-header">
                    @for ($i = 0; $i < 6; $i++)
                        <div class="log-skeleton-line log-skeleton-header-cell"></div>
                    @endfor
                </div>
                @for ($row = 0; $row < 6; $row++)
                    <div class="log-skeleton-row">
                        @for ($col = 0; $col < 6; $col++)
                            <div class="log-skeleton-line log-skeleton-row-cell"></div>
                        @endfor
                    </div>
                @endfor
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        (function() {
            const skeleton = document.getElementById('log-skeleton');
            if (!skeleton) return;
            let finished = false;

            function hideSkeleton() {
                if (finished) return;
                finished = true;
                skeleton.classList.add('is-hidden');
                window.setTimeout(function() {
                    skeleton.remove()
                }, 400)
            }
            if (document.readyState === 'complete') {
                window.requestAnimationFrame(hideSkeleton)
            } else {
                window.addEventListener('load', hideSkeleton, {
                    once: true
                })
            }
            window.setTimeout(hideSkeleton, 8000);
        })();
    </script>
@endpush
