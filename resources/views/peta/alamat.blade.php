@extends('layouts.public')
@section('title', 'Cek Lokasi')

@push('styles')
    <style>
        .peta-container {
            position: relative;
        }

        .btn-lokasi {
            position: absolute;
            top: 15px;
            left: 60px;
            z-index: 1000;

            display: flex;
            align-items: center;
            gap: 8px;

            padding: 10px 14px;
            background: #ffffff;
            color: #374151;

            border: 1px solid #e5e7eb;
            border-radius: 8px;

            font-size: 13px;
            font-weight: 600;
            font-family: inherit;

            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-lokasi:hover {
            background: #f3f4f6;
        }

        .btn-lokasi:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>
@endpush

@section('content')
    <div class="toolbar">
        Klik di mana saja pada peta untuk melihat alamat dan sekolah terdekat.
    </div>

    <div id="peta" class="peta">
        <button type="button" id="btn-lokasi" class="btn-lokasi">
            📍 Lokasi Saya
        </button>
    </div>

    <div id="hasil" class="panel"
        style="top: 64px; right: 12px; width: 320px; max-height: 70%;
            overflow: auto; display: none;"></div>
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

        Peta.ambilSekolah().then((fitur) => {
            semua = fitur;
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

            panel.style.display = 'block';
            panel.textContent = 'Mencari alamat...';

            let bagianAlamat = '<em>Alamat tidak dapat dimuat.</em>';
            try {
                const data = await ambilAlamat(lat, lng);
                const a = data.address || {};

                const baris = [
                    ['Jalan', a.road],
                    ['Kelurahan/Desa', a.suburb || a.neighbourhood || a.village],
                    ['Kota/Kabupaten', a.city || a.town || a.municipality || a.county],
                    ['Provinsi', a.state],
                    ['Kode pos', a.postcode],
                ].map(([k, v]) => `
                <tr>
                    <td style="padding: 2px 8px 2px 0; color: #6b7280;">${k}</td>
                    <td>${Peta.esc(v) || '-'}</td>
                </tr>`).join('');

                bagianAlamat = `
                <p style="margin: 6px 0;">${Peta.esc(data.display_name || '-')}</p>
                <table style="font-size: 13px;">${baris}</table>`;
            } catch (err) {
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
            <li data-npsn="${Peta.esc(d.fitur.properties.npsn)}"
                style="padding: 6px 0; border-top: 1px solid #e5e7eb; cursor: pointer;">
                <strong>${Peta.esc(d.fitur.properties.nama)}</strong><br>
                <small>${d.jarak.toFixed(2)} km (garis lurus)</small>
            </li>`).join('');

            panel.innerHTML = `
            <strong>Analisis Jangkauan 1 KM</strong>
            <div style="
                padding: 10px;
                margin: 8px 0 12px;
                background: #eff6ff;
                border: 1px solid #bfdbfe;
                border-radius: 8px;
                color: #1d4ed8;
            ">
                <strong>${sekolahRadius.length} sekolah</strong>
                <div style="font-size: 12px; margin-top: 4px;">
                    Berada dalam radius 1 km dari titik yang dipilih.
                </div>
            </div>
            <strong>Koordinat</strong>
            <div style="font-family: monospace;">${lat.toFixed(6)}, ${lng.toFixed(6)}</div>
            <strong style="display: block; margin-top: 8px;">Alamat</strong>
            ${bagianAlamat}
            <strong style="display: block; margin-top: 8px;">Sekolah terdekat</strong>
            <ul style="list-style: none; margin: 4px 0 0; padding: 0;">${daftar}</ul>`;
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
