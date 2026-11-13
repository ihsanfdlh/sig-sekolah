@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<style>
.dashboard-sky{--sky:#5F91AC;--sky-dark:#294B63;--sky-soft:#EAF4FA;position:relative}
.dashboard-sky .dashboard-panel{background:rgba(255,255,255,.88);border:1px solid #D6E5ED;box-shadow:0 12px 35px rgba(41,75,99,.07);border-radius:20px;backdrop-filter:blur(12px)}
.dashboard-sky .dashboard-skeleton{position:absolute;inset:0;z-index:20;background:#F7FAFC;transition:opacity .35s ease,visibility .35s ease;pointer-events:none}
.dashboard-sky .dashboard-skeleton.is-hidden{opacity:0;visibility:hidden}
.dashboard-sky .skeleton-bar{background:linear-gradient(100deg,#E1EBF1 15%,#F8FCFE 40%,#E1EBF1 65%);background-size:220% 100%;animation:dashboard-shimmer 1.35s linear infinite;border-radius:9px}
@keyframes dashboard-shimmer{to{background-position-x:-220%}}
@media(prefers-reduced-motion:reduce){.dashboard-sky .skeleton-bar{animation:none}.dashboard-sky .dashboard-skeleton{transition:none}}
</style>
<div class="dashboard-sky mx-auto w-full max-w-7xl" id="dashboard-sky">
    <div class="mb-7 flex flex-wrap items-center justify-between gap-4">
        <div>
            <span class="mb-3 inline-flex items-center rounded-full border border-[#CDE2EE] bg-[#E1EFF6] px-3 py-1.5 text-xs font-bold tracking-wide text-[#416D86]">PORTAL INFORMASI PENDIDIKAN</span>
            <h1 class="text-2xl font-extrabold tracking-tight text-[#294B63] sm:text-3xl">Selamat datang, {{ $user->name }}</h1>
            <p class="mt-2 text-sm leading-relaxed text-[#607C91]">Ruang kerja {{ $user->roleLabel() }} untuk pengelolaan informasi pendidikan.</p>
        </div>
    </div>
    @if($user->isAdmin())
        <div class="grid gap-4 md:grid-cols-3">
            @foreach($jumlahPerRole as $label => $jumlah)
                <div class="dashboard-panel p-6">
                    <div class="mb-5 flex h-11 w-11 items-center justify-center rounded-xl bg-[#E1EFF6] text-[#5F91AC]">
                        <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                    <p class="text-4xl font-extrabold tracking-tight text-[#294B63]">{{ $jumlah }}</p>
                    <p class="mt-2 text-sm font-medium text-[#607C91]">Akun {{ $label }}</p>
                </div>
            @endforeach
        </div>
    @elseif($user->isDinas())
        <div class="dashboard-panel w-full p-6 sm:max-w-sm">
            <div class="mb-5 flex h-11 w-11 items-center justify-center rounded-xl bg-[#E1EFF6] text-[#5F91AC]">
                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-7h6v7"/></svg>
            </div>
            <p class="text-4xl font-extrabold text-[#294B63]">{{ $jumlahSekolah }}</p>
            <p class="mt-2 text-sm font-medium text-[#607C91]">Sekolah terdata</p>
        </div>
    @elseif($sekolah)
        <div class="dashboard-panel max-w-2xl p-6 sm:p-8">
            <div class="mb-5 flex h-12 w-12 items-center justify-center rounded-xl bg-[#E1EFF6] text-[#5F91AC]">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18M5 21V9l7-5 7 5v12M9 21v-7h6v7"/></svg>
            </div>
            <h2 class="text-xl font-extrabold text-[#294B63]">{{ $sekolah->nama }}</h2>
            <p class="mt-2 text-sm text-[#607C91]">NPSN {{ $sekolah->npsn }} · {{ $sekolah->jenjang }} {{ $sekolah->status }}</p>
            <div class="mt-7 flex items-center justify-between gap-3 text-sm"><span class="font-semibold text-[#294B63]">Kelengkapan profil</span><strong class="text-[#5F91AC]">{{ $kelengkapan }}%</strong></div>
            <div class="mt-2 h-3 w-full overflow-hidden rounded-full bg-[#E1EFF6]" role="progressbar" aria-label="Kelengkapan profil" aria-valuenow="{{ $kelengkapan }}" aria-valuemin="0" aria-valuemax="100"><div class="h-full rounded-full bg-[#5F91AC] transition-all duration-500" style="width: {{ max(0, min(100, (float) $kelengkapan)) }}%"></div></div>
        </div>
    @else
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-sm font-medium text-amber-900">Akun Anda belum ditautkan ke sekolah. Hubungi admin.</div>
    @endif
    <div class="dashboard-skeleton" id="dashboard-skeleton" aria-hidden="true">
        <div class="mb-3 skeleton-bar h-7 w-48 max-w-full"></div>
        <div class="mb-3 skeleton-bar h-9 w-72 max-w-full"></div>
        <div class="mb-7 skeleton-bar h-4 w-96 max-w-full"></div>
        @if($user->isAdmin())
            <div class="grid gap-4 md:grid-cols-3">
                @foreach($jumlahPerRole as $label => $jumlah)
                    <div class="dashboard-panel p-6"><div class="skeleton-bar mb-5 h-11 w-11 rounded-xl"></div><div class="skeleton-bar h-10 w-20"></div><div class="skeleton-bar mt-3 h-4 w-32"></div></div>
                @endforeach
            </div>
        @elseif($user->isDinas())
            <div class="dashboard-panel w-full p-6 sm:max-w-sm"><div class="skeleton-bar mb-5 h-11 w-11"></div><div class="skeleton-bar h-10 w-20"></div><div class="skeleton-bar mt-3 h-4 w-32"></div></div>
        @elseif($sekolah)
            <div class="dashboard-panel max-w-2xl p-6 sm:p-8"><div class="skeleton-bar mb-5 h-12 w-12"></div><div class="skeleton-bar h-7 w-64 max-w-full"></div><div class="skeleton-bar mt-3 h-4 w-80 max-w-full"></div><div class="skeleton-bar mt-8 h-4 w-44"></div><div class="skeleton-bar mt-3 h-3 w-full rounded-full"></div></div>
        @else
            <div class="dashboard-panel p-6"><div class="skeleton-bar h-5 w-80 max-w-full"></div></div>
        @endif
    </div>
</div>
<script>
(function(){
    const skeleton=document.getElementById('dashboard-skeleton');
    if(!skeleton)return;
    const started=performance.now();
    const minimumDuration=190;
    let finished=false;
    function hideSkeleton(){
        if(finished)return;
        finished=true;
        const remaining=Math.max(0,minimumDuration-(performance.now()-started));
        setTimeout(function(){
            skeleton.classList.add('is-hidden');
            setTimeout(function(){skeleton.remove();},400);
        },remaining);
    }
    if(document.readyState==='complete'){hideSkeleton();}else{window.addEventListener('load',hideSkeleton,{once:true});}
    setTimeout(hideSkeleton,8000);
})();
</script>
@endsection
