<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#F7FAFC">
    @vite(['resources/css/app.css'])
    <title>403 - Akses Ditolak | SIG Sekolah</title>
</head>
<body class="relative isolate grid min-h-screen place-items-center overflow-hidden bg-[#F7FAFC] px-4 py-10 text-[#294B63] antialiased sm:px-6">
    <div aria-hidden="true" class="pointer-events-none absolute inset-0 overflow-hidden">
        <div class="absolute -left-24 -top-24 h-72 w-72 rounded-full bg-[#E1EFF6] blur-3xl"></div>
        <div class="absolute -bottom-28 -right-20 h-96 w-96 rounded-full bg-[#DCECF5] blur-3xl"></div>
        <img
            src="{{ asset('storage/Assets/tut-wuri-handayani.png') }}"
            alt=""
            class="absolute -bottom-16 -right-10 h-72 w-72 object-contain opacity-[0.07] grayscale sm:h-96 sm:w-96"
        >
    </div>

    <main class="relative w-full max-w-xl">
        <a href="{{ route('peta.index') }}" class="mb-6 flex items-center gap-3 text-[#294B63] no-underline">
            <img
                src="{{ asset('storage/Assets/Logo_of_Ministry_of_Education_and_Culture_of_Republic_of_Indonesia.svg.webp') }}"
                alt=""
                class="h-12 w-12 object-contain"
            >
            <span class="border-l border-[#C9DEE9] pl-3">
                <strong class="block text-lg font-extrabold leading-tight">SIG Sekolah</strong>
                <span class="mt-1 block text-xs font-medium text-[#607C91]">Sistem Informasi Geografis Sekolah</span>
            </span>
        </a>

        <section aria-labelledby="access-denied-title" class="overflow-hidden rounded-3xl border border-[#D6E5ED] bg-white/90 shadow-[0_18px_55px_rgba(41,75,99,.09)] backdrop-blur-xl">
            <div class="flex items-start gap-4 border-b border-[#E5EFF4] px-6 py-6 sm:px-8 sm:py-7">
                <div class="grid h-14 w-14 shrink-0 place-items-center rounded-2xl bg-[#E1EFF6] text-[#5F91AC]">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" class="h-7 w-7" aria-hidden="true">
                        <path d="M12 3 4.5 6v5.5c0 4.2 3.2 7.7 7.5 9.5 4.3-1.8 7.5-5.3 7.5-9.5V6L12 3Z"/>
                        <path d="M12 8v4"/>
                        <path d="M12 16h.01"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <span class="inline-flex rounded-full border border-[#F2D6D6] bg-[#FFF5F5] px-3 py-1 text-xs font-bold tracking-wide text-[#A94747]">
                        403 · AKSES DITOLAK
                    </span>
                    <h1 id="access-denied-title" class="mt-3 text-2xl font-extrabold tracking-tight text-[#294B63] sm:text-3xl">
                        Anda tidak memiliki akses
                    </h1>
                </div>
            </div>

            <div class="space-y-6 px-6 py-6 sm:px-8 sm:py-8">
                <p class="text-sm leading-7 text-[#607C91] sm:text-base">
                    Anda tidak memiliki hak akses ke halaman atau data ini. Pastikan Anda masuk dengan akun yang tepat atau hubungi administrator jika membutuhkan akses.
                </p>

                <div class="flex items-start gap-3 rounded-xl border border-[#D6E5ED] bg-[#F2F8FC] p-4 text-sm leading-6 text-[#496B82]">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="mt-0.5 h-5 w-5 shrink-0 text-[#5F91AC]" aria-hidden="true">
                        <rect x="4" y="10" width="16" height="11" rx="2"/>
                        <path d="M8 10V7a4 4 0 0 1 8 0v3"/>
                    </svg>
                    <p>Halaman ini hanya tersedia untuk pengguna dengan izin yang sesuai.</p>
                </div>

                <a
                    href="{{ auth()->check() ? route('dashboard') : route('peta.index') }}"
                    class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-[#5F91AC] px-5 py-2.5 text-sm font-bold text-white no-underline shadow-[0_5px_15px_rgba(95,145,172,.2)] transition hover:bg-[#4B7D98] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#5F91AC]"
                >
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4" aria-hidden="true">
                        <path d="m15 18-6-6 6-6"/>
                        <path d="M20 12H9"/>
                    </svg>
                    {{ auth()->check() ? 'Kembali ke Dashboard' : 'Kembali ke Peta' }}
                </a>
            </div>
        </section>
    </main>
</body>
</html>
