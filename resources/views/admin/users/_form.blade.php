@php
    $cls = 'block w-full rounded-xl border border-[#D4E3EB] bg-[#F8FBFD] px-4 py-2.5 text-sm text-[#294B63] outline-none transition focus:border-[#79A7BF] focus:ring-2 focus:ring-[#DCECF5]';
@endphp
<div class="w-full overflow-hidden relative rounded-2xl border border-[#D6E5ED] bg-white/90 shadow-[0_18px_55px_rgba(41,75,99,0.09)] backdrop-blur-xl">

<div class="sig-form-skeleton" data-sig-form-skeleton aria-hidden="true">
<div class="border-b border-[#D6E5ED] px-5 py-5 sm:px-7"><span class="sig-form-skeleton-line h-6 w-48"></span><span class="sig-form-skeleton-line mt-3 h-4 w-3/4 max-w-96"></span></div>
<div class="space-y-7 p-5 sm:p-7">
<div><span class="sig-form-skeleton-line h-5 w-44"></span><span class="sig-form-skeleton-line mt-3 h-4 w-3/4 max-w-96"></span><div class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2"><div><span class="sig-form-skeleton-line h-4 w-20"></span><span class="sig-form-skeleton-line mt-3 h-11 w-full"></span></div><div><span class="sig-form-skeleton-line h-4 w-20"></span><span class="sig-form-skeleton-line mt-3 h-11 w-full"></span></div></div></div>
<div class="border-t border-[#D6E5ED] pt-6"><span class="sig-form-skeleton-line h-5 w-52"></span><span class="sig-form-skeleton-line mt-3 h-4 w-3/4 max-w-96"></span><div class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2"><div><span class="sig-form-skeleton-line h-4 w-20"></span><span class="sig-form-skeleton-line mt-3 h-11 w-full"></span></div><div><span class="sig-form-skeleton-line h-4 w-20"></span><span class="sig-form-skeleton-line mt-3 h-11 w-full"></span></div></div></div>
<div class="border-t border-[#D6E5ED] pt-6"><span class="sig-form-skeleton-line h-5 w-44"></span><span class="sig-form-skeleton-line mt-3 h-4 w-3/4 max-w-96"></span><div class="mt-5 grid grid-cols-1 gap-5 md:grid-cols-2"><div><span class="sig-form-skeleton-line h-4 w-28"></span><span class="sig-form-skeleton-line mt-3 h-11 w-full"></span></div><div><span class="sig-form-skeleton-line h-4 w-28"></span><span class="sig-form-skeleton-line mt-3 h-11 w-full"></span></div></div></div>
</div>
<div class="flex justify-end gap-3 border-t border-[#D6E5ED] px-5 py-4"><span class="sig-form-skeleton-line h-10 w-24"></span><span class="sig-form-skeleton-line h-10 w-28"></span></div>
</div>
    <!-- Header Form -->
    <div class="border-b border-[#D6E5ED] px-5 py-5 sm:px-7">
        <h2 class="text-lg font-bold text-[#294B63]">Informasi Pengguna</h2>
        <p class="mt-1 text-sm text-[#71899A]">
            Kelola identitas, hak akses, dan keamanan akun pengguna.
        </p>
    </div>
    <!-- Isi Form -->
    <div class="space-y-7 p-5 sm:p-7">
        <!-- Identitas Pengguna -->
        <section>
            <div class="mb-5">
                <h3 class="text-base font-semibold text-[#294B63]">Identitas Pengguna</h3>
                <p class="mt-1 text-sm text-[#71899A]">
                    Informasi dasar akun yang digunakan untuk masuk ke sistem.
                </p>
            </div>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <!-- Nama -->
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-[#365C74]">
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
                    <label for="email" class="mb-2 block text-sm font-semibold text-[#365C74]">
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
        <section class="border-t border-[#D6E5ED] pt-6">
            <div class="mb-5">
                <h3 class="text-base font-semibold text-[#294B63]">Hak Akses Pengguna</h3>
                <p class="mt-1 text-sm text-[#71899A]">
                    Tentukan peran pengguna dan sekolah yang terhubung dengan akun.
                </p>
            </div>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <!-- Role -->
                <div>
                    <label for="role" class="mb-2 block text-sm font-semibold text-[#365C74]">
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
                    <label for="sekolah_id" class="mb-2 block text-sm font-semibold text-[#365C74]">
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
        <section class="border-t border-[#D6E5ED] pt-6">
            <div class="mb-5">
                <h3 class="text-base font-semibold text-[#294B63]">Keamanan Akun</h3>
                <p class="mt-1 text-sm text-[#71899A]">
                    Atur kata sandi untuk menjaga keamanan akun pengguna.
                </p>
            </div>
            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                <!-- Kata Sandi -->
                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-[#365C74]">
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
                            class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-[#66879B] transition hover:text-[#4D7E99] focus:outline-none">
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
                        <p class="mt-2 text-xs text-[#71899A]">
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
                        class="mb-2 block text-sm font-semibold text-[#365C74]">
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
                            class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-[#66879B] transition hover:text-[#4D7E99] focus:outline-none">
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
    <div class="flex flex-wrap items-center justify-end gap-3 border-t border-[#D6E5ED] bg-[#F2F8FC] px-5 py-4 sm:px-7">
        <a href="{{ route('admin.users.index') }}"
            class="inline-flex h-10 items-center justify-center rounded-lg border border-[#D4E3EB] bg-[#F8FBFD] px-5 text-sm font-semibold text-[#365C74] transition hover:bg-[#E6F0F6]">
            Batal
        </a>
        <button type="submit"
            class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-[#5F91AC] px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-[#4D7E99]">
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
    const skeleton = document.querySelector('[data-sig-form-skeleton]');
    if (skeleton) {
        const minimumDuration = 1800;
        const startedAt = performance.now();
        let hidden = false;
        const hideSkeleton = () => {
            if (hidden) return;
            hidden = true;
            const remaining = Math.max(0, minimumDuration - (performance.now() - startedAt));
            setTimeout(() => {
                skeleton.classList.add('is-hidden');
                setTimeout(() => skeleton.remove(), 450);
            }, remaining);
        };
        if (document.readyState === 'complete') hideSkeleton();
        else window.addEventListener('load', hideSkeleton, { once: true });
        setTimeout(hideSkeleton, 8000);
    }
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
