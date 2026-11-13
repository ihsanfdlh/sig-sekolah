@extends('layouts.public')
@section('title', 'Cek Lokasi')
@section('page-style-class', 'location-page')


@section('content')
    <div class="toolbar">
        Klik di mana saja pada peta untuk melihat alamat dan sekolah terdekat.
    </div>

    <div id="public-map-frame" class="public-map-frame is-loading" aria-busy="true">
        <div id="peta" class="peta">
            <button type="button" id="btn-lokasi" class="btn-lokasi">
                📍 Lokasi Saya
            </button>
        </div>
        <div id="peta-loading" class="public-map-loading" role="status" aria-live="polite">
            <span class="public-map-loading-spinner" aria-hidden="true"></span>
            <span>Memuat data sekolah...</span>
        </div>
    </div>

    <div id="hasil" class="panel location-results-panel" hidden></div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script src="https://unpkg.com/leaflet.heat@0.2.0/dist/leaflet-heat.js"></script>
    <script>
        const map = Peta.buat('peta');
        const pusatJaro = [-1.8505, 115.6350];
        const zoomAwal = 12;
        map.setView(pusatJaro, zoomAwal);
        map.eachLayer(function(layer) {
            if (layer instanceof L.TileLayer) {
                map.removeLayer(layer);
            }
        });
        const atribusiOSM = '&copy; OpenStreetMap contributors';
        const atribusiEsri = 'Tiles &copy; Esri';
        const atribusiCarto = '&copy; OpenStreetMap contributors &copy; CARTO';
        const baseLayers = {
            'Jalanan (OpenStreetMap)': L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: atribusiOSM,
                maxZoom: 18
            }),
            'Satelit (Esri)': L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    attribution: atribusiEsri,
                    maxZoom: 18
                }),
            'Satelit + Nama Lokasi': L.layerGroup([
                L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: atribusiEsri,
                        maxZoom: 18
                    }),
                L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                        attribution: atribusiEsri,
                        maxZoom: 18
                    })
            ]),
            'Topografi (Esri)': L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
                    attribution: atribusiEsri,
                    maxZoom: 18
                })
        };
        const layerAwal = baseLayers['Jalanan (OpenStreetMap)'];
        layerAwal.addTo(map);
        const layerMarker = L.layerGroup();
        const layerKlaster = L.markerClusterGroup({
            showCoverageOnHover: false,
            maxClusterRadius: 60,
            spiderfyOnMaxZoom: true,
            disableClusteringAtZoom: 17,
            chunkedLoading: true
        });
        const layerPanas = L.heatLayer([], {
            radius: 25,
            blur: 18,
            maxZoom: 17,
            minOpacity: 0.3
        });
        layerKlaster.addTo(map);

        L.control.layers(baseLayers, null, {
            collapsed: true,
            position: 'topleft',
            autoZIndex: true
        }).addTo(map);

        const lapisan = L.layerGroup().addTo(map);
        const layerRadius = L.layerGroup().addTo(map);
        const panel = document.getElementById('hasil');
        let semua = [];
        let terakhir = 0;
        let requestPencarianTerakhir = 0;

        Peta.ambilSekolah().then((fitur) => {
            semua = fitur;
        }).catch((error) => {
            console.error('Gagal memuat data sekolah:', error);
        }).finally(() => {
            window.aturLoadingPetaPublik(false);
        });

        // n sekolah terdekat dari sebuah titik [lat, lng]
        function terdekat(titik, n = 3) {
            return semua
                .map((f) => ({
                    fitur: f,
                    jarak: Peta.jarakKm(titik, Peta.latlng(f))
                }))
                .sort((a, b) => a.jarak - b.jarak)
                .slice(0, n);
        }

        async function ambilAlamat(lat, lng) {
            const url = 'https://nominatim.openstreetmap.org/reverse' +
                '?format=jsonv2&addressdetails=1&accept-language=id' +
                `&lat=${lat}&lon=${lng}`;

            const respon = await fetch(url);
            if (!respon.ok) throw new Error('HTTP ' + respon.status);

            return respon.json();
        }

        async function cekLokasi(e) {
            // Kebijakan Nominatim: maksimal 1 permintaan per detik
            const sekarang = Date.now();
            if (sekarang - terakhir < 1200) return;
            terakhir = sekarang;
            const requestId = ++requestPencarianTerakhir;
            window.aturLoadingPetaPublik(true);

            try {
            const {
                lat,
                lng
            } = e.latlng;
            const titik = [lat, lng];
            const sekolahRadius = analisisRadius(titik);

            lapisan.clearLayers();
            L.circleMarker(titik, {
                radius: 9,
                color: '#fff',
                weight: 3,
                fillColor: '#ef4444',
                fillOpacity: 1,
            }).addTo(lapisan);

            const dekat = terdekat(titik);
            dekat.forEach((d) => {
                L.polyline([titik, Peta.latlng(d.fitur)], {
                    color: '#2563eb',
                    weight: 2,
                    dashArray: '6 6',
                }).addTo(lapisan);
            });

            panel.hidden = false;
            panel.textContent = 'Mencari alamat...';

            let bagianAlamat = '<em>Alamat tidak dapat dimuat.</em>';
            try {
                const data = await ambilAlamat(lat, lng);
                if (requestId !== requestPencarianTerakhir) return;
                const a = data.address || {};

                const baris = [
                    ['Jalan', a.road],
                    ['Kelurahan/Desa', a.suburb || a.neighbourhood || a.village],
                    ['Kota/Kabupaten', a.city || a.town || a.municipality || a.county],
                    ['Provinsi', a.state],
                    ['Kode pos', a.postcode],
                ].map(([k, v]) => `
                <tr>
                    <td class="address-detail-label">${k}</td>
                    <td>${Peta.esc(v) || '-'}</td>
                </tr>`).join('');

                bagianAlamat = `
                <p class="address-display-name">${Peta.esc(data.display_name || '-')}</p>
                <table class="address-detail-table">${baris}</table>`;
            } catch (err) {
                if (requestId !== requestPencarianTerakhir) return;

                console.error(err);
            }

            function analisisRadius(titik) {

                // Hapus lingkaran radius sebelumnya
                layerRadius.clearLayers();

                // Membuat lingkaran radius 1 KM
                L.circle(titik, {
                    radius: 1000,
                    color: '#2563eb',
                    weight: 2,
                    fillColor: '#3b82f6',
                    fillOpacity: 0.12
                }).addTo(layerRadius);

                // Mencari sekolah dalam radius 1 KM
                const sekolahDalamRadius = semua.filter((f) => {
                    try {
                        const koordinat = Peta.latlng(f);

                        const jarak = Peta.jarakKm(titik, koordinat);

                        return Number.isFinite(jarak) && jarak <= 1;
                    } catch (error) {
                        return false;
                    }
                });

                // Tandai sekolah dalam radius
                sekolahDalamRadius.forEach((f) => {
                    L.circleMarker(Peta.latlng(f), {
                            radius: 6,
                            color: '#15803d',
                            weight: 2,
                            fillColor: '#22c55e',
                            fillOpacity: 1
                        })
                        .bindPopup(Peta.popup(f))
                        .addTo(layerRadius);
                });

                return sekolahDalamRadius;
            }

            const daftar = dekat.map((d) => `
            <li class="nearby-school-item" data-npsn="${Peta.esc(d.fitur.properties.npsn)}">
                <strong>${Peta.esc(d.fitur.properties.nama)}</strong><br>
                <small>${d.jarak.toFixed(2)} km (garis lurus)</small>
            </li>`).join('');

            panel.innerHTML = `
            <strong>Analisis Jangkauan 1 KM</strong>
            <div class="radius-summary">
                <strong>${sekolahRadius.length} sekolah</strong>
                <div class="radius-summary-description">
                    Berada dalam radius 1 km dari titik yang dipilih.
                </div>
            </div>
            <strong>Koordinat</strong>
            <div class="location-coordinates">${lat.toFixed(6)}, ${lng.toFixed(6)}</div>
            <strong class="location-section-title">Alamat</strong>
            ${bagianAlamat}
            <strong class="location-section-title">Sekolah terdekat</strong>
            <ul class="nearby-school-list">${daftar}</ul>`;
            } catch (error) {
                if (requestId !== requestPencarianTerakhir) return;

                console.error('Gagal menganalisis lokasi:', error);
                panel.textContent = 'Gagal memuat informasi lokasi.';
            } finally {
                if (requestId === requestPencarianTerakhir) {
                    window.aturLoadingPetaPublik(false);
                }
            }
        }

        map.on('click', cekLokasi);


        const btnLokasi = document.getElementById('btn-lokasi');

        btnLokasi.addEventListener('click', () => {

            // Memeriksa dukungan geolocation
            if (!navigator.geolocation) {
                alert('Browser tidak mendukung fitur lokasi.');
                return;
            }

            // Menonaktifkan tombol sementara
            btnLokasi.disabled = true;
            btnLokasi.textContent = 'Mencari lokasi...';

            // Mengambil koordinat pengguna
            navigator.geolocation.getCurrentPosition(

                // Jika lokasi berhasil ditemukan
                (posisi) => {
                    const lat = posisi.coords.latitude;
                    const lng = posisi.coords.longitude;

                    // Mengarahkan peta ke lokasi pengguna
                    map.setView([lat, lng], 16);

                    // Menjalankan fungsi pencarian yang sudah ada
                    cekLokasi({
                        latlng: L.latLng(lat, lng)
                    });

                    // Mengembalikan tombol
                    btnLokasi.disabled = false;
                    btnLokasi.textContent = '📍 Lokasi Saya';
                },

                // Jika gagal mendapatkan lokasi
                (error) => {
                    btnLokasi.disabled = false;
                    btnLokasi.textContent = '📍 Lokasi Saya';

                    const pesan = {
                        1: 'Izin lokasi ditolak. Aktifkan izin lokasi browser.',
                        2: 'Lokasi tidak tersedia.',
                        3: 'Waktu pencarian lokasi habis.'
                    };

                    alert(pesan[error.code] || 'Gagal mendapatkan lokasi.');
                },

                // Pengaturan geolocation
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        });


        // Klik pada daftar sekolah terdekat: terbang ke sekolah tersebut
        panel.addEventListener('click', (e) => {
            const li = e.target.closest('[data-npsn]');
            if (!li) return;

            const f = semua.find((x) => x.properties.npsn === li.dataset.npsn);
            if (f) {
                L.popup().setLatLng(Peta.latlng(f)).setContent(Peta.popup(f)).openOn(map);
                map.flyTo(Peta.latlng(f), 17);
            }
        });
    </script>
@endpush
