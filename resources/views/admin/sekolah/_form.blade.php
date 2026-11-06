
@php
    $sekolah = $sekolah ?? new \App\Models\Sekolah();
    $teks = [
        'npsn' => 'NPSN (8 digit)',
        'nama' => 'Nama Sekolah',
        'kecamatan' => 'Kecamatan',
        'telepon' => 'Telepon',
    ];
    $pilihan = [
        'jenjang' => ['SD', 'SMP', 'MTS', 'SMA', 'SMK'],
        'status' => ['Negeri', 'Swasta'],
    ];
    $cls = 'w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-800 outline-none transition focus:border-emerald-600 focus:ring-2 focus:ring-emerald-100';
    $user = auth()->user();
@endphp

<div class="w-full min-w-0 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
    <div class="border-b border-slate-200 px-5 py-5 sm:px-7">
        <h2 class="text-lg font-bold text-slate-900">Informasi Sekolah</h2>
        <p class="mt-1 text-sm text-slate-500">
            Lengkapi identitas dan tentukan titik lokasi sekolah.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-8 p-5 sm:p-7 xl:grid-cols-2">
        <!-- Identitas Sekolah -->
        <section class="min-w-0 space-y-5">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-base font-bold text-slate-900">Data Sekolah</h3>
                <p class="mt-1 text-sm text-slate-500">Identitas dan informasi dasar sekolah.</p>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                @foreach ($teks as $name => $label)
                    <div class="min-w-0">
                        <label for="{{ $name }}" class="mb-1.5 block text-sm font-semibold text-slate-700">{{ $label }}</label>
                        <input type="text" id="{{ $name }}" name="{{ $name }}"
                            value="{{ old($name, $sekolah->{$name} ?? '') }}"
                            @readonly($name === 'npsn' && $user?->isSekolah())
                            @if ($name === 'npsn') maxlength="8" inputmode="numeric" @endif
                            class="{{ $cls }} @error($name) border-red-500 @enderror">
                        @error($name)
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                @foreach ($pilihan as $name => $opsi)
                    <div class="min-w-0">
                        <label for="{{ $name }}" class="mb-1.5 block text-sm font-semibold text-slate-700">{{ ucfirst($name) }}</label>
                        <select id="{{ $name }}" name="{{ $name }}"
                            class="{{ $cls }} @error($name) border-red-500 @enderror">
                            <option value="">-- Pilih {{ ucfirst($name) }} --</option>
                            @foreach ($opsi as $o)
                                <option value="{{ $o }}" @selected(old($name, $sekolah->{$name} ?? '') === $o)>{{ $o }}</option>
                            @endforeach
                        </select>
                        @error($name)
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <div class="sm:col-span-2">
                    <label for="alamat" class="mb-1.5 block text-sm font-semibold text-slate-700">Alamat Sekolah</label>
                    <textarea id="alamat" name="alamat" rows="4"
                        class="{{ $cls }} @error('alamat') border-red-500 @enderror"
                        placeholder="Masukkan alamat lengkap sekolah">{{ old('alamat', $sekolah->alamat ?? '') }}</textarea>
                    @error('alamat')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <!-- Lokasi Sekolah -->
        <section class="min-w-0 space-y-5">
            <div class="border-b border-slate-100 pb-4">
                <h3 class="text-base font-bold text-slate-900">Lokasi Sekolah</h3>
                <p class="mt-1 text-sm text-slate-500">
                    Klik peta, geser marker, atau gunakan lokasi perangkat.
                </p>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3">
                <span class="inline-flex items-center gap-2 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-medium text-emerald-700">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Pilih titik lokasi
                </span>
                <button type="button" id="btn-lokasi-saya"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800 disabled:cursor-wait disabled:opacity-60">
                    <i class="bi bi-crosshair"></i>
                    Gunakan Lokasi Saya
                </button>
            </div>

            <div class="overflow-hidden rounded-xl border border-slate-200 shadow-sm">
                <div id="map-picker" class="h-[350px] w-full bg-slate-100 sm:h-[420px]"></div>
            </div>

            <p id="location-status" role="status" aria-live="polite" class="text-xs text-slate-500">
                Geser marker untuk menyesuaikan koordinat sekolah.
            </p>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <label for="latitude" class="mb-1.5 block text-sm font-semibold text-slate-700">Latitude</label>
                    <input type="number" step="any" min="-90" max="90" id="latitude" name="latitude"
                        value="{{ old('latitude', $sekolah->latitude ?? '') }}"
                        class="{{ $cls }} @error('latitude') border-red-500 @enderror">
                    @error('latitude')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="longitude" class="mb-1.5 block text-sm font-semibold text-slate-700">Longitude</label>
                    <input type="number" step="any" min="-180" max="180" id="longitude" name="longitude"
                        value="{{ old('longitude', $sekolah->longitude ?? '') }}"
                        class="{{ $cls }} @error('longitude') border-red-500 @enderror">
                    @error('longitude')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            @if ($user?->isSekolah())
                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                    <p class="font-semibold">Batas Perubahan Lokasi</p>
                    <p class="mt-1">
                        Anda hanya dapat mengubah titik lokasi sekolah maksimal 100 meter dari lokasi sebelumnya.
                    </p>
                </div>
            @endif

            <div class="rounded-lg border border-blue-100 bg-blue-50 p-4 text-sm text-blue-800">
                <p class="font-semibold">Petunjuk Penggunaan</p>
                <p class="mt-1">
                    Klik lokasi pada peta atau geser marker biru. Koordinat akan diperbarui secara otomatis.
                    Kamu juga dapat memasukkan koordinat secara manual.
                </p>
            </div>
        </section>
    </div>

    <!-- Footer dengan garis pemisah -->
    <div class="flex flex-wrap items-center justify-end gap-3 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:px-7">
        <a href="{{ $user?->isDinas() && Route::has('dinas.sekolah.index') ? route('dinas.sekolah.index') : route('dashboard') }}"
            class="inline-flex h-10 items-center justify-center rounded-lg border border-slate-300 bg-white px-5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
            Batal
        </a>
        <button type="submit"
            class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-emerald-700 px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
            <i class="bi bi-check-lg"></i>
            Simpan Perubahan
        </button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');
    const btnLokasi = document.getElementById('btn-lokasi-saya');
    const status = document.getElementById('location-status');

    if (!latInput || !lngInput || !document.getElementById('map-picker')) return;
    if (typeof L === 'undefined') {
        status.textContent = 'Leaflet gagal dimuat. Periksa koneksi internet.';
        return;
    }

    const valid = (lat, lng) =>
        Number.isFinite(lat) && Number.isFinite(lng) &&
        lat >= -90 && lat <= 90 && lng >= -180 && lng <= 180;

    const latAwal = latInput.value.trim() === '' ? NaN : Number(latInput.value);
    const lngAwal = lngInput.value.trim() === '' ? NaN : Number(lngInput.value);
    const sudahAda = valid(latAwal, lngAwal);
    const pusat = sudahAda ? [latAwal, lngAwal] : [-1.8374614675638135, 115.63343545771596];

    const osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; OpenStreetMap contributors'
    });
    const satelit = L.tileLayer(
        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 19,
            attribution: 'Tiles &copy; Esri'
        }
    );
    const topo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
        maxZoom: 17,
        attribution: '&copy; OpenTopoMap contributors'
    });

    const map = L.map('map-picker', {
        center: pusat,
        zoom: sudahAda ? 16 : 13,
        layers: [osm]
    });

    L.control.layers({
        'OpenStreetMap': osm,
        'Satelit (Esri)': satelit,
        'Topografi': topo
    }, null, {
        position: 'topright',
        collapsed: true
    }).addTo(map);

    const marker = L.marker(pusat, {
        draggable: true
    }).addTo(map);

    function isiKoordinat(lat, lng) {
        latInput.value = lat.toFixed(7);
        lngInput.value = lng.toFixed(7);
        status.textContent = `Koordinat dipilih: ${lat.toFixed(7)}, ${lng.toFixed(7)}`;
    }

    if (!sudahAda) isiKoordinat(pusat[0], pusat[1]);

    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        isiKoordinat(e.latlng.lat, e.latlng.lng);
    });

    marker.on('dragend', function() {
        const p = marker.getLatLng();
        isiKoordinat(p.lat, p.lng);
    });

    [latInput, lngInput].forEach(function(el) {
        el.addEventListener('input', function() {
            if (latInput.value.trim() === '' || lngInput.value.trim() === '') return;
            const lat = Number(latInput.value);
            const lng = Number(lngInput.value);
            if (valid(lat, lng)) {
                marker.setLatLng([lat, lng]);
                map.panTo([lat, lng]);
                status.textContent = 'Koordinat berhasil diperbarui.';
            }
        });
    });

    if (btnLokasi) {
        btnLokasi.addEventListener('click', function() {
            if (!navigator.geolocation) {
                status.textContent = 'Browser tidak mendukung geolocation.';
                return;
            }

            const teksAwal = btnLokasi.innerHTML;
            btnLokasi.disabled = true;
            btnLokasi.textContent = 'Mencari Lokasi...';
            status.textContent = 'Sedang mengambil lokasi perangkat...';

            navigator.geolocation.getCurrentPosition(
                function(pos) {
                    const lat = pos.coords.latitude;
                    const lng = pos.coords.longitude;
                    if (valid(lat, lng)) {
                        marker.setLatLng([lat, lng]);
                        map.flyTo([lat, lng], 17, {
                            animate: true,
                            duration: 1.5
                        });
                        isiKoordinat(lat, lng);
                    }
                    btnLokasi.disabled = false;
                    btnLokasi.innerHTML = teksAwal;
                },
                function(error) {
                    const pesan = {
                        1: 'Izin lokasi ditolak. Aktifkan izin lokasi pada browser.',
                        2: 'Lokasi perangkat tidak tersedia.',
                        3: 'Waktu permintaan lokasi habis.'
                    };
                    status.textContent = pesan[error.code] || 'Gagal mengambil lokasi.';
                    btnLokasi.disabled = false;
                    btnLokasi.innerHTML = teksAwal;
                }, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        });
    }

    setTimeout(function() {
        map.invalidateSize();
    }, 200);
});
</script>
@endpush
