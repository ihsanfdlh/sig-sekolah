@extends('layouts.app')
@section('title', 'Pengguna')
@section('content')

    <div id="users-page" class="relative mx-auto max-w-7xl space-y-6 text-[#294B63]">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <div
                    class="mb-2 inline-flex items-center gap-2 rounded-full border border-[#CDE2EE] bg-[#E7F2F8] px-3 py-1 text-[11px] font-bold uppercase tracking-wider text-[#527F9A]">
                    Manajemen Akun</div>
                <h1 class="text-2xl font-extrabold tracking-tight text-[#294B63] sm:text-3xl">Pengguna</h1>
                <p class="mt-1 text-sm text-[#607C91]">Kelola akun, hak akses, dan status pengguna SIG Sekolah.</p>
            </div>
            <a href="{{ route('admin.users.create') }}"
                class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[#5F91AC] px-5 py-3 text-sm font-bold text-white shadow-[0_6px_16px_rgba(95,145,172,.18)] transition hover:bg-[#4B7D98] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#5F91AC]">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round">
                    <path d="M12 5v14M5 12h14" />
                </svg>
                Tambah Pengguna
            </a>
        </div>
        <div
            class="overflow-hidden rounded-2xl border border-[#D6E5ED] bg-white/90 shadow-[0_10px_30px_rgba(42,83,108,.07)] backdrop-blur-xl">
            <div
                class="flex flex-col gap-4 border-b border-[#E2EDF3] bg-[#F8FBFE] px-5 py-5 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-base font-extrabold text-[#294B63]">Daftar Pengguna</h2>
                    <p class="mt-1 text-xs text-[#607C91]">Gunakan filter untuk menampilkan pengguna berdasarkan peran.</p>
                </div>
                <form method="GET" class="flex flex-wrap items-end gap-2">
                    <div class="min-w-[180px] flex-1 sm:flex-none">
                        <label for="filter-role" class="mb-1.5 block text-xs font-bold text-[#45677D]">Filter Peran</label>
                        <select id="filter-role" name="role"
                            class="min-h-11 w-full rounded-xl border border-[#D2E2EB] bg-white px-3 py-2.5 text-sm font-medium text-[#294B63] outline-none transition focus:border-[#5F91AC] focus:ring-2 focus:ring-[#5F91AC]/20">
                            <option value="">Semua role</option>
                            @foreach (['admin' => 'Admin', 'dinas' => 'Dinas Pendidikan', 'sekolah' => 'Sekolah'] as $nilai => $label)
                                <option value="{{ $nilai }}" @selected(request('role') === $nilai)>{{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[#5F91AC] px-5 py-2.5 text-sm font-bold text-white transition hover:bg-[#4B7D98]">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"
                            stroke-linecap="round">
                            <path d="M4 7h16M7 12h10M10 17h4" />
                        </svg>
                        Filter
                    </button>
                    @if (request()->filled('role'))
                        <a href="{{ route('admin.users.index') }}"
                            class="inline-flex min-h-11 items-center justify-center rounded-xl border border-[#D2E2EB] bg-white px-4 py-2.5 text-sm font-semibold text-[#52748A] transition hover:bg-[#EAF4FA]">Reset</a>
                    @endif
                </form>
            </div>
            <div class="w-full overflow-x-auto">
                <table class="w-full min-w-[900px] border-collapse text-left text-sm">
                    <thead class="bg-[#EDF6FB] text-[#45677D]">
                        <tr>
                            <th scope="col" class="px-5 py-4 text-xs font-extrabold uppercase tracking-wide">Nama</th>
                            <th scope="col" class="px-5 py-4 text-xs font-extrabold uppercase tracking-wide">Email</th>
                            <th scope="col" class="px-5 py-4 text-xs font-extrabold uppercase tracking-wide">Role</th>
                            <th scope="col" class="px-5 py-4 text-xs font-extrabold uppercase tracking-wide">Sekolah</th>
                            <th scope="col" class="px-5 py-4 text-xs font-extrabold uppercase tracking-wide">Status</th>
                            <th scope="col" class="px-5 py-4 text-xs font-extrabold uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#E7EFF4] bg-white/70">
                        @forelse ($users as $u)
                            <tr class="transition-colors hover:bg-[#F4FAFD]">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3"><span
                                            class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-[#DDECF5] text-sm font-extrabold text-[#456F87]">{{ strtoupper(mb_substr($u->name, 0, 1)) }}</span><span
                                            class="font-bold text-[#294B63]">{{ $u->name }}</span></div>
                                </td>
                                <td class="px-5 py-4 text-[#5C788B]">{{ $u->email }}</td>
                                <td class="px-5 py-4"><span
                                        class="inline-flex rounded-lg border border-[#D1E5F0] bg-[#EDF6FB] px-2.5 py-1 text-xs font-bold text-[#456F87]">{{ $u->roleLabel() }}</span>
                                </td>
                                <td class="px-5 py-4 text-[#5C788B]">{{ $u->sekolah?->nama ?? '-' }}</td>
                                <td class="px-5 py-4">
                                    @if ($u->is_active)
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700"><span
                                                class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>Aktif</span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-3 py-1 text-xs font-bold text-rose-700"><span
                                                class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap items-center gap-2">
                                        @if (auth()->user()->isAdmin() && auth()->id() !== $u->id)
                                            <form method="POST" action="{{ route('admin.users.toggle-status', $u) }}"
                                                onsubmit="return confirm('Yakin ingin mengubah status akun ini?')">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                    class="rounded-lg border px-3 py-2 text-xs font-bold transition {{ $u->is_active ? 'border-amber-200 bg-amber-50 text-amber-700 hover:bg-amber-100' : 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }}">{{ $u->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.users.edit', $u) }}"
                                            class="inline-flex rounded-lg border border-[#CFE3EE] bg-[#EDF6FB] px-3 py-2 text-xs font-bold text-[#477B98] transition hover:bg-[#DDECF5]">Ubah</a>
                                        @if (!$u->is(auth()->user()))
                                            <form method="POST" action="{{ route('admin.users.destroy', $u) }}"
                                                onsubmit="return confirm('Hapus pengguna ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-bold text-rose-700 transition hover:bg-rose-100">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <div
                                        class="mx-auto mb-3 grid h-12 w-12 place-items-center rounded-2xl bg-[#EDF6FB] text-[#5F91AC]">
                                        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="1.7">
                                            <circle cx="9" cy="8" r="3" />
                                            <path d="M3 20v-2a6 6 0 0 1 12 0v2M17 8h5M19.5 5.5v5" />
                                        </svg></div>
                                    <p class="font-bold text-[#294B63]">Belum ada pengguna</p>
                                    <p class="mt-1 text-xs text-[#607C91]">Belum ada data pengguna untuk filter yang
                                        dipilih.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if (method_exists($users, 'links'))
                <div class="border-t border-[#E2EDF3] bg-[#FAFCFE] px-5 py-4">{{ $users->withQueryString()->links() }}
                </div>
            @endif
        </div>
        <div id="users-page-skeleton" aria-hidden="true"
            class="pointer-events-none absolute inset-0 z-20 overflow-hidden rounded-2xl bg-[#F7FAFC] transition-opacity duration-500">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-3">
                    <div class="user-skeleton-shimmer h-5 w-36 rounded-full"></div>
                    <div class="user-skeleton-shimmer h-9 w-48 rounded-lg"></div>
                    <div class="user-skeleton-shimmer h-4 w-72 max-w-full rounded-md"></div>
                </div>
                <div class="user-skeleton-shimmer h-11 w-44 rounded-xl"></div>
            </div>
            <div class="mt-6 overflow-hidden rounded-2xl border border-[#D6E5ED] bg-white">
                <div
                    class="flex flex-col gap-4 border-b border-[#E2EDF3] p-5 sm:flex-row sm:items-center sm:justify-between">
                    <div class="space-y-3">
                        <div class="user-skeleton-shimmer h-5 w-40 rounded-md"></div>
                        <div class="user-skeleton-shimmer h-3 w-64 max-w-full rounded-md"></div>
                    </div>
                    <div class="flex gap-2">
                        <div class="user-skeleton-shimmer h-11 w-40 rounded-xl"></div>
                        <div class="user-skeleton-shimmer h-11 w-20 rounded-xl"></div>
                    </div>
                </div>
                <div class="space-y-0">
                    <div class="user-skeleton-shimmer h-12 w-full opacity-70"></div>
                    @for ($i = 0; $i < 6; $i++)
                        <div class="flex items-center gap-4 border-b border-[#E7EFF4] px-5 py-4">
                            <div class="user-skeleton-shimmer h-10 w-10 shrink-0 rounded-xl"></div>
                            <div class="user-skeleton-shimmer h-4 w-32 flex-1 rounded-md"></div>
                            <div class="user-skeleton-shimmer hidden h-4 flex-1 rounded-md sm:block"></div>
                            <div class="user-skeleton-shimmer hidden h-7 w-20 rounded-lg md:block"></div>
                            <div class="user-skeleton-shimmer hidden h-7 w-16 rounded-full lg:block"></div>
                            <div class="user-skeleton-shimmer h-8 w-28 rounded-lg"></div>
                        </div>
                    @endfor
                </div>
                <div class="p-5">
                    <div class="user-skeleton-shimmer h-9 w-64 max-w-full rounded-lg"></div>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function() {
            const hide = () => {
                const skeleton = document.getElementById('users-page-skeleton');
                if (!skeleton) return;
                skeleton.classList.add('opacity-0');
                window.setTimeout(() => skeleton.remove(), 550)
            };
            if (document.readyState === 'complete') {
                requestAnimationFrame(hide)
            } else {
                window.addEventListener('load', hide, {
                    once: true
                })
            }
            window.setTimeout(hide, 20000)
        })();
    </script>
@endsection
