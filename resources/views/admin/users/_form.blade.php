
@php
    $cls = 'block w-full rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100';
@endphp

<div class="w-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <!-- Header Form -->
    <div class="border-b border-slate-200 px-5 py-5 sm:px-7">
        <h2 class="text-lg font-bold text-slate-900">Informasi Pengguna</h2>
        <p class="mt-1 text-sm text-slate-500">
            Kelola identitas, hak akses, dan keamanan akun pengguna.
        </p>
    </div>

    <!-- Isi Form -->
    <div class="space-y-7 p-5 sm:p-7">
        <!-- Identitas Pengguna -->
        <section>
            <div class="mb-5">
                <h3 class="text-base font-semibold text-slate-900">Identitas Pengguna</h3>
                <p class="mt-1 text-sm text-slate-500">
                    Informasi dasar akun yang digunakan untuk masuk ke sistem.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <!-- Nama -->
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                        Nama
                    </label>
                    <input type="text" id="name" name="name"
                        value="{{ old('name', $user->name) }}"
                        class="{{ $cls }} @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                        Email
                    </label>
                    <input type="email" id="email" name="email"
                        value="{{ old('email', $user->email) }}"
                        class="{{ $cls }} @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <!-- Hak Akses Pengguna -->
        <section class="border-t border-slate-200 pt-6">
            <div class="mb-5">
                <h3 class="text-base font-semibold text-slate-900">Hak Akses Pengguna</h3>
                <p class="mt-1 text-sm text-slate-500">
                    Tentukan peran pengguna dan sekolah yang terhubung dengan akun.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <!-- Role -->
                <div>
                    <label for="role" class="mb-2 block text-sm font-semibold text-slate-700">
                        Role
                    </label>
                    <select id="role" name="role"
                        class="{{ $cls }} @error('role') border-red-500 @enderror">
                        @foreach (['admin' => 'Admin', 'dinas' => 'Dinas Pendidikan', 'sekolah' => 'Sekolah'] as $nilai => $label)
                            <option value="{{ $nilai }}"
                                @selected(old('role', $user->role?->value ?? 'sekolah') === $nilai)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sekolah -->
                <div id="blok-sekolah">
                    <label for="sekolah_id" class="mb-2 block text-sm font-semibold text-slate-700">
                        Sekolah
                    </label>
                    <select id="sekolah_id" name="sekolah_id"
                        class="{{ $cls }} @error('sekolah_id') border-red-500 @enderror">
                        <option value="">-- Pilih sekolah --</option>
                        @foreach ($sekolahs as $s)
                            <option value="{{ $s->id }}"
                                @selected((int) old('sekolah_id', $user->sekolah_id) === (int) $s->id)>
                                {{ $s->npsn }} - {{ $s->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('sekolah_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <!-- Keamanan Akun -->
        <section class="border-t border-slate-200 pt-6">
            <div class="mb-5">
                <h3 class="text-base font-semibold text-slate-900">Keamanan Akun</h3>
                <p class="mt-1 text-sm text-slate-500">
                    Atur kata sandi untuk menjaga keamanan akun pengguna.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <!-- Kata Sandi -->
                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">
                        Kata Sandi
                    </label>

                    <div class="relative">
                        <input type="password" id="password" name="password"
                            autocomplete="new-password"
                            class="{{ $cls }} pr-12 @error('password') border-red-500 @enderror">

                        <button type="button"
                            data-toggle-password="password"
                            aria-label="Tampilkan kata sandi"
                            aria-pressed="false"
                            class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-slate-400 transition hover:text-blue-600 focus:outline-none">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path class="eye-outline"
                                    d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                <circle class="eye-pupil" cx="12" cy="12" r="3"/>
                                <path class="eye-slash hidden" d="M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>

                    @if ($user->exists)
                        <p class="mt-2 text-xs text-slate-500">
                            Kosongkan jika tidak ingin mengganti kata sandi.
                        </p>
                    @endif

                    @error('password')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ulangi Kata Sandi -->
                <div>
                    <label for="password_confirmation"
                        class="mb-2 block text-sm font-semibold text-slate-700">
                        Ulangi Kata Sandi
                    </label>

                    <div class="relative">
                        <input type="password" id="password_confirmation"
                            name="password_confirmation"
                            autocomplete="new-password"
                            class="{{ $cls }} pr-12">

                        <button type="button"
                            data-toggle-password="password_confirmation"
                            aria-label="Tampilkan konfirmasi kata sandi"
                            aria-pressed="false"
                            class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-slate-400 transition hover:text-blue-600 focus:outline-none">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round" viewBox="0 0 24 24"
                                aria-hidden="true">
                                <path class="eye-outline"
                                    d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12z"/>
                                <circle class="eye-pupil" cx="12" cy="12" r="3"/>
                                <path class="eye-slash hidden" d="M3 3l18 18"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer Form -->
    <div class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:px-7">
        <a href="{{ route('admin.users.index') }}"
            class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
            Batal
        </a>

        <button type="submit"
            class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-blue-600 px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
            <svg width="17" height="17" fill="none" stroke="currentColor"
                stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l4 4v12a2 2 0 0 1-2 2z"/>
                <polyline stroke-linecap="round" stroke-linejoin="round"
                    points="17 21 17 13 7 13 7 21"/>
                <polyline stroke-linecap="round" stroke-linejoin="round"
                    points="7 3 7 8 15 8"/>
            </svg>
            Simpan
        </button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Menampilkan pilihan sekolah berdasarkan role
    const role = document.getElementById('role');
    const blok = document.getElementById('blok-sekolah');

    if (role && blok) {
        function aturBlokSekolah() {
            const isSekolah = role.value === 'sekolah';
            blok.classList.toggle('hidden', !isSekolah);
            const sekolahInput = document.getElementById('sekolah_id');
            if (sekolahInput) {
                sekolahInput.disabled = !isSekolah;
            }
        }

        role.addEventListener('change', aturBlokSekolah);
        aturBlokSekolah();
    }

    // Toggle tampil/sembunyikan kata sandi
    document.querySelectorAll('[data-toggle-password]').forEach(function(button) {
        const inputId = button.getAttribute('data-toggle-password');
        const input = document.getElementById(inputId);

        if (!input) return;

        button.addEventListener('click', function() {
            const tampil = input.type === 'password';

            input.type = tampil ? 'text' : 'password';

            const garisMata = button.querySelector('.eye-slash');
            if (garisMata) {
                garisMata.classList.toggle('hidden', !tampil);
            }

            button.setAttribute('aria-pressed', String(tampil));
            button.setAttribute(
                'aria-label',
                tampil ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'
            );
        });
    });
});
</script>
@endpush
