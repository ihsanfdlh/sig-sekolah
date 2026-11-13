@extends('layouts.app')
@section('title', 'Data Sekolah')
@section('content')
    @php
        $jenjangStyle = [
            'SD' => 'bg-rose-50 text-rose-700 border-rose-200',
            'SMP' => 'bg-sky-50 text-sky-700 border-sky-200',
            'MTS' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'SMK' => 'bg-orange-50 text-orange-700 border-orange-200',
            'SMA' => 'bg-amber-50 text-amber-700 border-amber-200',
        ];
        $statusStyle = [
            'Swasta' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'Negeri' => 'bg-sky-50 text-sky-700 border-sky-200',
        ];
    @endphp

    <div class="relative mx-auto w-full max-w-7xl pb-10" id="school-page-root" aria-busy="true">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <div>
                <div
                    class="mb-2 inline-flex items-center gap-2 rounded-full border border-[#D6E5ED] bg-[#EDF6FB] px-3 py-1 text-xs font-bold uppercase tracking-widest text-[#5F91AC]">
                    Manajemen Data Pendidikan</div>
                <h1 class="text-2xl font-extrabold tracking-tight text-[#294B63] sm:text-3xl">Data Sekolah</h1>
                <p class="mt-2 text-sm text-[#607C91]">Kelola informasi sekolah yang tampil pada peta publik.</p>
            </div>
            <a href="{{ route('dinas.sekolah.create') }}"
                class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[#5F91AC] px-5 py-2.5 text-sm font-bold text-white shadow-[0_5px_15px_rgba(95,145,172,.2)] transition hover:bg-[#4B7D98] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#5F91AC]"><svg
                    class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round">
                    <path d="M12 5v14M5 12h14" />
                </svg>Tambah Sekolah</a>
        </div>
        <section
            class="overflow-hidden rounded-2xl border border-[#D6E5ED] bg-white/90 shadow-[0_10px_30px_rgba(42,83,108,.07)] backdrop-blur-xl">
            <div class="border-b border-[#E1ECF2] bg-[#F8FBFD] px-5 py-5 sm:px-6">
                <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center"><label for="school-search"
                        class="sr-only">Cari sekolah</label>
                    <div class="relative min-w-0 flex-1"><svg
                            class="pointer-events-none absolute left-3.5 top-1/2 h-5 w-5 -translate-y-1/2 text-[#83A5B9]"
                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <circle cx="11" cy="11" r="7" />
                            <path d="m20 20-4-4" />
                        </svg><input id="school-search" type="search" name="q" value="{{ request('q') }}"
                            placeholder="Cari nama, NPSN, atau kecamatan"
                            class="min-h-11 w-full rounded-xl border border-[#CFDFE9] bg-white py-2.5 pl-11 pr-4 text-sm text-[#294B63] outline-none transition placeholder:text-[#91A9B8] focus:border-[#5F91AC] focus:ring-2 focus:ring-[#DCECF5]"
                            aria-label="Cari sekolah"></div><button type="submit"
                        class="min-h-11 rounded-xl bg-[#5F91AC] px-6 text-sm font-semibold text-white transition hover:bg-[#4B7D98]">Cari</button>
                    @if (request()->filled('q'))
                        <a href="{{ url()->current() }}"
                            class="inline-flex min-h-11 items-center justify-center rounded-xl border border-[#D6E5ED] bg-white px-4 text-sm font-semibold text-[#607C91] transition hover:bg-[#EDF6FB]">Reset</a>
                    @endif
                </form>
            </div>
            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-[1000px] border-collapse text-left text-sm">
                    <thead class="bg-[#EDF6FB] text-xs font-bold uppercase tracking-wider text-[#607C91]">
                        <tr>
                            <th class="px-5 py-4">No</th>
                            <th class="px-4 py-4">NPSN</th>
                            <th class="px-4 py-4">Nama Sekolah</th>
                            <th class="px-4 py-4">Jenjang</th>
                            <th class="px-4 py-4">Status</th>
                            <th class="px-4 py-4">Kecamatan</th>
                            <th class="px-4 py-4">Koordinat</th>
                            <th class="px-5 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E6EFF4] bg-white/80">
                        @forelse($sekolahs as $s)
                            <tr class="transition hover:bg-[#F5FAFD]">
                                <td class="px-5 py-4 text-[#607C91]">{{ ($sekolahs->firstItem() ?? 1) + $loop->index }}</td>
                                <td class="px-4 py-4 font-mono text-xs font-semibold text-[#607C91]">{{ $s->npsn }}
                                </td>
                                <td class="max-w-[260px] px-4 py-4"><span
                                        class="block break-words font-bold text-[#294B63]">{{ $s->nama }}</span></td>
                                <td class="px-4 py-4"><span
                                        class="inline-flex rounded-lg border px-2.5 py-1 text-xs font-bold {{ $jenjangStyle[strtoupper($s->jenjang ?? '')] ?? 'border-[#D6E5ED] bg-[#EDF6FB] text-[#607C91]' }}">{{ $s->jenjang }}</span>
                                </td>
                                <td class="px-4 py-4"><span
                                        class="inline-flex rounded-lg border px-2.5 py-1 text-xs font-bold {{ $statusStyle[$s->status] ?? 'border-[#D6E5ED] bg-[#EDF6FB] text-[#607C91]' }}">{{ $s->status }}</span>
                                </td>
                                <td class="px-4 py-4 text-[#607C91]">{{ $s->kecamatan ?: '-' }}</td>
                                <td class="px-4 py-4 font-mono text-xs text-[#607C91]">
                                    {{ $s->latitude !== null && $s->longitude !== null ? $s->latitude . ', ' . $s->longitude : '-' }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2"><a
                                            href="{{ route('sekolah.edit', $s) }}"
                                            class="inline-flex min-h-9 items-center justify-center rounded-lg border border-[#C8DEEA] bg-[#EDF6FB] px-3 text-xs font-bold text-[#416F8A] transition hover:bg-[#DCECF5]">Ubah</a>
                                        <form method="POST" action="{{ route('dinas.sekolah.destroy', $s) }}"
                                            onsubmit="return confirm('Hapus data ini?')">@csrf @method('DELETE')<button
                                                type="submit"
                                                class="inline-flex min-h-9 items-center justify-center rounded-lg border border-rose-200 bg-rose-50 px-3 text-xs font-bold text-rose-700 transition hover:bg-rose-100">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-16 text-center">
                                    <div
                                        class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-[#EDF6FB] text-[#5F91AC]">
                                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.7">
                                            <path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-7h6v7" />
                                        </svg></div>
                                    <p class="font-semibold text-[#294B63]">Belum ada data sekolah</p>
                                    <p class="mt-1 text-sm text-[#607C91]">Coba kata kunci lain atau tambahkan sekolah baru.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if (method_exists($sekolahs, 'links'))
                <div class="border-t border-[#E1ECF2] bg-[#F8FBFD] px-5 py-4 sm:px-6">
                    {{ $sekolahs->withQueryString()->links() }}</div>
            @endif
        </section>
        <div id="school-page-skeleton" class="absolute inset-0 z-20 rounded-2xl bg-[#F7FAFC]" aria-hidden="true">
            <div class="mb-6 flex items-center justify-between gap-4">
                <div class="space-y-3">
                    <div class="school-skeleton-shimmer h-5 w-40 rounded-full"></div>
                    <div class="school-skeleton-shimmer h-9 w-56 rounded-xl"></div>
                    <div class="school-skeleton-shimmer h-4 w-72 max-w-full rounded-lg"></div>
                </div>
                <div class="school-skeleton-shimmer h-11 w-36 rounded-xl"></div>
            </div>
            <div class="overflow-hidden rounded-2xl border border-[#D6E5ED] bg-white">
                <div class="flex gap-3 border-b border-[#E1ECF2] p-5">
                    <div class="school-skeleton-shimmer h-11 flex-1 rounded-xl"></div>
                    <div class="school-skeleton-shimmer h-11 w-20 rounded-xl"></div>
                </div>
                <div class="school-skeleton-shimmer h-12 w-full rounded-none"></div>
                @for ($i = 0; $i < 7; $i++)
                    <div class="flex gap-5 border-b border-[#E6EFF4] px-5 py-5">
                        <div class="school-skeleton-shimmer h-5 w-8 rounded-md"></div>
                        <div class="school-skeleton-shimmer h-5 w-24 rounded-md"></div>
                        <div class="school-skeleton-shimmer h-5 flex-1 rounded-md"></div>
                        <div class="school-skeleton-shimmer h-5 w-20 rounded-md"></div>
                        <div class="school-skeleton-shimmer h-5 w-20 rounded-md"></div>
                        <div class="school-skeleton-shimmer hidden h-5 w-24 rounded-md md:block"></div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
    <script>
        (function() {
            const started = Date.now();
            const minimumDuration = 500;
            const maximumDuration = 8000;
            let finished = false;

            function hideSkeleton() {
                if (finished) return;
                finished = true;
                const remaining = Math.max(0, minimumDuration - (Date.now() - started));
                setTimeout(function() {
                    const skeleton = document.getElementById('school-page-skeleton');
                    const root = document.getElementById('school-page-root');
                    if (skeleton) {
                        skeleton.classList.add('school-skeleton-hidden');
                        setTimeout(function() {
                            skeleton.remove()
                        }, 450)
                    }
                    if (root) root.setAttribute('aria-busy', 'false')
                }, remaining)
            }
            if (document.readyState === 'complete') {
                hideSkeleton()
            } else {
                window.addEventListener('load', hideSkeleton, {
                    once: true
                })
            }
            setTimeout(hideSkeleton, maximumDuration)
        })();
    </script>
@endsection
