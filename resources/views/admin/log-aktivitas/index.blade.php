@extends('layouts.app')
@section('title', 'Log Aktivitas')
@push('styles')
    <style>
        .log-page {
            --log-primary: #5F91AC;
            --log-ink: #294B63;
            --log-muted: #607C91;
            --log-line: #D6E5ED;
            color: var(--log-ink)
        }

        .log-page .log-title {
            color: var(--log-ink);
            font-weight: 800;
            letter-spacing: -.035em
        }

        .log-page .log-subtitle {
            color: var(--log-muted)
        }

        .log-page .log-card {
            overflow: hidden;
            border: 1px solid var(--log-line);
            border-radius: 20px;
            background: rgba(255, 255, 255, .92);
            box-shadow: 0 14px 40px rgba(41, 75, 99, .08);
            backdrop-filter: blur(16px)
        }

        .log-page .log-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            text-align: left;
            font-size: 13px
        }

        .log-page .log-table thead {
            background: #EAF4FA;
            color: #365D75
        }

        .log-page .log-table th {
            padding: 17px 20px;
            font-size: 12px;
            font-weight: 800;
            white-space: nowrap;
            letter-spacing: .02em
        }

        .log-page .log-table td {
            padding: 17px 20px;
            color: #456478;
            vertical-align: top
        }

        .log-page .log-table tbody tr:not(:last-child) td {
            border-bottom: 1px solid #E7F0F5
        }

        .log-page .log-table tbody tr:hover {
            background: #F6FAFD
        }

        .log-page .log-table .log-user {
            font-weight: 750;
            color: #294B63
        }

        .log-page .log-badge {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 5px 11px;
            font-size: 11px;
            font-weight: 750
        }

        .log-page .log-badge-add {
            background: #E3F4ED;
            color: #247456
        }

        .log-page .log-badge-edit {
            background: #E5F1F9;
            color: #416D86
        }

        .log-page .log-badge-delete {
            background: #FCEBE9;
            color: #A6534F
        }

        .log-page .log-badge-other {
            background: #EFF3F6;
            color: #607C91
        }

        .log-page .log-details {
            min-width: 210px
        }

        .log-page .log-details summary {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            cursor: pointer;
            color: #4B819E;
            font-weight: 750;
            list-style: none;
            user-select: none
        }

        .log-page .log-details summary::-webkit-details-marker {
            display: none
        }

        .log-page .log-details summary::after {
            content: '+';
            display: grid;
            place-items: center;
            width: 19px;
            height: 19px;
            border-radius: 6px;
            background: #E5F1F9;
            font-size: 15px
        }

        .log-page .log-details[open] summary::after {
            content: '−'
        }

        .log-page .log-details-panel {
            margin-top: 13px;
            padding: 14px;
            border: 1px solid #DCE9F0;
            border-radius: 12px;
            background: #F4F9FC
        }

        .log-page .log-details-panel pre {
            margin: 0;
            max-width: 440px;
            overflow-x: auto;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
            color: #49667A;
            font-size: 11px;
            line-height: 1.6
        }

        .log-page .log-details-panel .log-json-label {
            margin: 0 0 7px;
            font-weight: 800;
            color: #365D75
        }

        .log-page .log-pagination {
            border-top: 1px solid #E5EFF5;
            padding: 17px 20px;
            background: rgba(248, 252, 254, .7)
        }

        .log-page .log-empty {
            padding: 52px 20px !important;
            text-align: center;
            color: #7890A0 !important
        }

        .log-page .log-skeleton {
            position: absolute;
            inset: 0;
            z-index: 10;
            background: #F7FAFC;
            pointer-events: none;
            transition: opacity .35s ease, visibility .35s ease
        }

        .log-page .log-skeleton.is-hidden {
            opacity: 0;
            visibility: hidden
        }

        .log-page .log-skeleton-line {
            position: relative;
            overflow: hidden;
            border-radius: 9px;
            background: #DFEAF1
        }

        .log-page .log-skeleton-line::after {
            content: '';
            position: absolute;
            inset: 0;
            transform: translateX(-100%);
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .82), transparent);
            animation: log-shimmer 1.35s ease-in-out infinite
        }

        @keyframes log-shimmer {
            100% {
                transform: translateX(100%)
            }
        }

        @media(prefers-reduced-motion:reduce) {
            .log-page .log-skeleton-line::after {
                animation: none
            }
        }

        @media(max-width:640px) {
            .log-page .log-card {
                border-radius: 15px
            }

            .log-page .log-table th,
            .log-page .log-table td {
                padding: 13px 15px
            }

            .log-page .log-pagination {
                padding: 14px
            }
        }
    </style>
@endpush
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
            <div class="log-skeleton-line" style="width:240px;height:29px;margin-bottom:12px"></div>
            <div class="log-skeleton-line" style="width:min(370px,85%);height:16px;margin-bottom:28px"></div>
            <div style="overflow:hidden;border:1px solid #D6E5ED;border-radius:20px;background:#fff">
                <div
                    style="display:grid;grid-template-columns:55px 1.2fr 1.4fr 90px 1.1fr 1fr;gap:20px;padding:20px;background:#EAF4FA;min-width:700px">
                    @for ($i = 0; $i < 6; $i++)
                        <div class="log-skeleton-line" style="height:14px"></div>
                    @endfor
                </div>
                @for ($row = 0; $row < 6; $row++)
                    <div
                        style="display:grid;grid-template-columns:55px 1.2fr 1.4fr 90px 1.1fr 1fr;gap:20px;padding:22px 20px;border-top:1px solid #E7F0F5;min-width:700px">
                        @for ($col = 0; $col < 6; $col++)
                            <div class="log-skeleton-line" style="height:16px"></div>
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
