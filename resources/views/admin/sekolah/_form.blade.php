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
    $cls =
        'w-full rounded-lg border border-[#C9DCE7] bg-white px-4 py-2.5 text-sm text-[#294B63] outline-none transition focus:border-[#5F91AC] focus:ring-2 focus:ring-[#E1F0F7]';
    $user = auth()->user();
@endphp
<div id="school-form-shell" class="relative w-full min-w-0 overflow-hidden rounded-2xl border border-[#D6E5ED] bg-white/90 shadow-[0_10px_30px_rgba(42,83,108,.07)] backdrop-blur-xl">
    <div class="border-b border-[#D6E5ED] px-5 py-5 sm:px-7">
        <h2 class="text-lg font-bold text-[#294B63]">Informasi Sekolah</h2>
        <p class="mt-1 text-sm text-[#607C91]">
            Lengkapi identitas dan tentukan titik lokasi sekolah.
        </p>
    </div>
    <div class="grid grid-cols-1 gap-8 p-5 sm:p-7 xl:grid-cols-2">
        <!-- Identitas Sekolah -->
        <section class="min-w-0 space-y-5">
            <div class="border-b border-[#E6F0F5] pb-4">
                <h3 class="text-base font-bold text-[#294B63]">Data Sekolah</h3>
                <p class="mt-1 text-sm text-[#607C91]">Identitas dan informasi dasar sekolah.</p>
            </div>
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                @foreach ($teks as $name => $label)
                    <div class="min-w-0">
                        <label for="{{ $name }}"
                            class="mb-1.5 block text-sm font-semibold text-[#42647B]">{{ $label }}</label>
                        <input type="text" id="{{ $name }}" name="{{ $name }}"
                            value="{{ old($name, $sekolah->{$name} ?? '') }}" @readonly($name === 'npsn' && $user?->isSekolah()) @if ($name === 'npsn')
                        maxlength="8" inputmode="numeric"
                @endif
                class="{{ $cls }} @error($name)
border-red-500
@enderror">
                @error($name)
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            @endforeach
            @foreach ($pilihan as $name => $opsi)
                <div class="min-w-0">
                    <label for="{{ $name }}"
                        class="mb-1.5 block text-sm font-semibold text-[#42647B]">{{ ucfirst($name) }}</label>
                    <select id="{{ $name }}" name="{{ $name }}"
                        class="{{ $cls }} @error($name) border-red-500 @enderror">
                        <option value="">-- Pilih {{ ucfirst($name) }} --</option>
                        @foreach ($opsi as $o)
                            <option value="{{ $o }}" @selected(old($name, $sekolah->{$name} ?? '') === $o)>{{ $o }}
                            </option>
                        @endforeach
                    </select>
                    @error($name)
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
            <div class="sm:col-span-2">
                <label for="alamat" class="mb-1.5 block text-sm font-semibold text-[#42647B]">Alamat Sekolah</label>
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
        <div class="border-b border-[#E6F0F5] pb-4">
            <h3 class="text-base font-bold text-[#294B63]">Lokasi Sekolah</h3>
            <p class="mt-1 text-sm text-[#607C91]">
                Klik peta, geser marker, atau gunakan lokasi perangkat.
            </p>
        </div>
        <div class="flex flex-wrap items-center justify-between gap-3">
            <span
                class="inline-flex items-center gap-2 rounded-full bg-[#EDF6FB] px-3 py-1.5 text-xs font-medium text-[#4B7D98]">
                <span class="h-2 w-2 rounded-full bg-[#EDF6FB]0"></span>
                Pilih titik lokasi
            </span>
            <button type="button" id="btn-lokasi-saya"
                class="inline-flex items-center justify-center gap-2 rounded-lg bg-[#4B7D98] px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-[#294B63] disabled:cursor-wait disabled:opacity-60">
                <i class="bi bi-crosshair"></i>
                Gunakan Lokasi Saya
            </button>
            <button type="button" id="btn-isi-alamat"
                class="inline-flex items-center justify-center gap-2 rounded-lg
                    border border-[#4B7D98] bg-white px-4 py-2.5
                    text-sm font-semibold text-[#4B7D98]
                    transition hover:bg-[#EDF6FB]
                    disabled:cursor-wait disabled:opacity-60">
                <i class="bi bi-geo-alt"></i>
                Isi Alamat dari Peta
            </button>
        </div>
        <div class="overflow-hidden rounded-xl border border-[#D6E5ED] shadow-sm">
            <div id="map-picker" class="h-[350px] w-full bg-[#EDF6FB] sm:h-[420px]"></div>
        </div>
        <p id="location-status" role="status" aria-live="polite" class="text-xs text-[#607C91]">
            Geser marker untuk menyesuaikan koordinat sekolah.
        </p>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="latitude" class="mb-1.5 block text-sm font-semibold text-[#42647B]">Latitude</label>
                <input type="number" step="any" min="-90" max="90" id="latitude" name="latitude"
                    value="{{ old('latitude', $sekolah->latitude ?? '') }}"
                    class="{{ $cls }} @error('latitude') border-red-500 @enderror">
                @error('latitude')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label for="longitude" class="mb-1.5 block text-sm font-semibold text-[#42647B]">Longitude</label>
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
<div class="flex flex-wrap items-center justify-end gap-3 border-t border-[#D6E5ED] bg-[#F0F7FB] px-5 py-4 sm:px-7">
    <a href="{{ $user?->isDinas() && Route::has('dinas.sekolah.index') ? route('dinas.sekolah.index') : route('dashboard') }}"
        class="inline-flex h-10 items-center justify-center rounded-lg border border-[#C9DCE7] bg-white px-5 text-sm font-semibold text-[#42647B] transition hover:bg-[#EDF6FB]">
        Batal
    </a>
    <button type="submit"
        class="inline-flex h-10 items-center justify-center gap-2 rounded-lg bg-[#4B7D98] px-6 text-sm font-semibold text-white shadow-sm transition hover:bg-[#294B63]">
        <i class="bi bi-check-lg"></i>
        Simpan Perubahan
    </button>
</div>
<div id="school-form-skeleton" aria-hidden="true" class="absolute inset-0 z-[500] bg-[#F7FAFC] p-5 sm:p-7 transition-opacity duration-[400ms]">
<div class="school-skeleton-shimmer mb-5 h-7 w-52 rounded-lg"></div>
<div class="school-skeleton-shimmer mb-8 h-4 w-80 max-w-full rounded-md"></div>
<div class="grid grid-cols-1 gap-8 xl:grid-cols-2">
<div class="space-y-5"><div class="school-skeleton-shimmer h-6 w-40 rounded-lg"></div><div class="grid grid-cols-1 gap-5 sm:grid-cols-2">@for($i=0;$i<6;$i++)<div class="space-y-2"><div class="school-skeleton-shimmer h-4 w-24 rounded-md"></div><div class="school-skeleton-shimmer h-11 rounded-xl"></div></div>@endfor</div><div class="school-skeleton-shimmer h-24 rounded-xl"></div></div>
<div class="space-y-5"><div class="school-skeleton-shimmer h-6 w-40 rounded-lg"></div><div class="flex gap-3"><div class="school-skeleton-shimmer h-10 w-40 rounded-xl"></div><div class="school-skeleton-shimmer h-10 w-40 rounded-xl"></div></div><div class="school-skeleton-shimmer h-[350px] rounded-xl sm:h-[420px]"></div><div class="grid grid-cols-2 gap-4"><div class="school-skeleton-shimmer h-11 rounded-xl"></div><div class="school-skeleton-shimmer h-11 rounded-xl"></div></div></div>
</div>
</div>
</div>

@push('scripts')
<script>
(function(){
const skeleton=document.getElementById('school-form-skeleton');
if(!skeleton)return;
const started=Date.now();
let finished=false;
function hide(){
if(finished)return;
finished=true;
const minimumDuration=1800;
setTimeout(function(){
skeleton.classList.add('opacity-0','pointer-events-none');
setTimeout(function(){skeleton.remove();if(window.schoolFormMap){window.schoolFormMap.invalidateSize();}},400);
},Math.max(0,minimumDuration-(Date.now()-started)));
}
if(document.readyState==='complete')hide();else window.addEventListener('load',hide,{once:true});
setTimeout(hide,8000);
})();
</script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const latInput = document.getElementById('latitude');
            const lngInput = document.getElementById('longitude');
            const btnLokasi = document.getElementById('btn-lokasi-saya');
            const status = document.getElementById('location-status');
            const btnIsiAlamat = document.getElementById('btn-isi-alamat');
            const alamatInput = document.getElementById('alamat');
            const kecamatanInput = document.getElementById('kecamatan');
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
                maxZoom: 18,
                attribution: '&copy; OpenStreetMap contributors'
            });
            const satelit = L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    maxZoom: 18,
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
            window.schoolFormMap = map;
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
            let waktuGeocodingTerakhir = 0;
            if (btnIsiAlamat) {
                btnIsiAlamat.addEventListener('click', async function() {
                    // Mengambil koordinat marker
                    const posisi = marker.getLatLng();
                    const lat = posisi.lat;
                    const lng = posisi.lng;
                    if (!valid(lat, lng)) {
                        status.textContent = 'Koordinat sekolah tidak valid.';
                        return;
                    }
                    // Membatasi permintaan minimal 1 detik
                    const sekarang = Date.now();
                    if (sekarang - waktuGeocodingTerakhir < 1200) {
                        status.textContent = 'Tunggu sebentar sebelum mencoba lagi.';
                        return;
                    }
                    waktuGeocodingTerakhir = sekarang;
                    // Menonaktifkan tombol saat proses berjalan
                    btnIsiAlamat.disabled = true;
                    const teksAwal = btnIsiAlamat.innerHTML;
                    btnIsiAlamat.textContent = 'Mengambil Alamat...';
                    status.textContent = 'Mencari alamat berdasarkan koordinat marker...';
                    try {
                        const url = new URL(
                            'https://nominatim.openstreetmap.org/reverse'
                        );
                        url.searchParams.set('format', 'jsonv2');
                        url.searchParams.set('lat', lat);
                        url.searchParams.set('lon', lng);
                        url.searchParams.set('zoom', '18');
                        url.searchParams.set('addressdetails', '1');
                        url.searchParams.set('accept-language', 'id');
                        const response = await fetch(url);
                        if (!response.ok) {
                            throw new Error('HTTP ' + response.status);
                        }
                        const data = await response.json();
                        if (data.error || !data.display_name) {
                            throw new Error(
                                data.error || 'Alamat tidak ditemukan.'
                            );
                        }
                        // Mengisi alamat sekolah
                        if (alamatInput) {
                            alamatInput.value = data.display_name;
                            alamatInput.dispatchEvent(
                                new Event('input', {
                                    bubbles: true
                                })
                            );
                        }
                        // Mengisi kecamatan jika tersedia
                        const a = data.address || {};
                        const kecamatan =
                            a.city_district ||
                            a.district ||
                            a.municipality ||
                            '';
                        if (kecamatanInput && kecamatan) {
                            kecamatanInput.value = kecamatan;
                            kecamatanInput.dispatchEvent(
                                new Event('input', {
                                    bubbles: true
                                })
                            );
                        }
                        status.textContent =
                            'Alamat berhasil diambil. Periksa kembali sebelum menyimpan.';
                    } catch (error) {
                        console.error('Reverse geocoding gagal:', error);
                        status.textContent =
                            'Gagal mengambil alamat: ' + error.message;
                    } finally {
                        btnIsiAlamat.disabled = false;
                        btnIsiAlamat.innerHTML = teksAwal;
                    }
                });
            }
            setTimeout(function() {
                map.invalidateSize();
            }, 200);
        });
    </script>
@endpush
