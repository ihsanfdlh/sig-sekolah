
<!DOCTYPE html>
<html lang="id" class="standalone-map-page">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebGIS Peta Sekolah</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    @vite(['resources/css/app.css'])

</head>
<body>
    <header class="navbar">
        <div class="header-background-art" aria-hidden="true">
            <svg fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 2L1 7l11 5 11-5L12 2zm0 3.8L18.6 7 12 8.8 5.4 7 12 5.8zM4 9.5v5.8c0 3.2 3.6 5.7 8 5.7s8-2.5 8-5.7V9.5l-8 3.6-8-3.6zm8 7.3c-3.1 0-5.8-1.5-6-3.3l6 2.7 6-2.7c-.2 1.8-2.9 3.3-6 3.3z"/>
                <path d="M12 11.5L2 7v8.5c0 4.1 4.5 7.5 10 7.5s10-3.4 10-7.5V7l-10 4.5zm0 11.5c-4.4 0-8-2.7-8-6v-5.2l8 3.6 8-3.6V17c0 3.3-3.6 6-8 6z" opacity=".5"/>
            </svg>
        </div>
        <div class="brand">
            <div class="brand-icon"><i class="bi bi-geo-alt-fill"></i></div>
            <div class="brand-text">
                <h1>WebGIS Peta Sekolah</h1>
                <p>Sistem Informasi Geografis Sekolah</p>
            </div>
        </div>
        <div class="header-controls">
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" id="input-cari" class="search-input" placeholder="Cari nama sekolah..." autocomplete="off" aria-label="Cari nama sekolah">
                <div id="rekomendasi-list" class="rekomendasi-box"></div>
            </div>
            <div class="filter-wrapper">
                <select id="filter-jenjang" class="filter-select" aria-label="Filter jenjang pendidikan">
                    <option value="">Semua Jenjang</option>
                    <option value="SD">SD</option>
                    <option value="SMP">SMP</option>
                    <option value="MTS">MTS</option>
                    <option value="SMA">SMA</option>
                    <option value="SMK">SMK</option>
                </select>
                <select id="filter-status" class="filter-select" aria-label="Filter status sekolah">
                    <option value="">Semua Status</option>
                    <option value="Negeri">Negeri</option>
                    <option value="Swasta">Swasta</option>
                </select>
                <select id="filter-kecamatan" class="filter-select" aria-label="Filter kecamatan">
                    <option value="">Semua Kecamatan</option>
                    @foreach ($kecamatan as $item)
                        <option value="{{ $item->kecamatan }}">{{ $item->kecamatan }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn-reset" id="btn-reset" title="Reset Filter">
                <i class="bi bi-arrow-counterclockwise"></i><span>Reset</span>
            </button>
            <div class="stat-header">
                <i class="bi bi-buildings"></i>
                <strong id="jumlah">0</strong>
                <span>Sekolah</span>
            </div>
            @auth
                <a href="{{ route('dashboard') }}" class="btn-admin">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-admin">Masuk</a>
            @endauth
            @auth
                <form method="POST" action="{{ route('logout') }}" class="form-logout">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Keluar ({{ auth()->user()->name }})</span>
                    </button>
                </form>
            @endauth
        </div>
    </header>
    <main class="map-container">
        <div class="map-info">
            <h3><i class="bi bi-map"></i>Peta Persebaran Sekolah</h3>
            <p>Klik titik lokasi untuk melihat informasi sekolah</p>
        </div>
        <div id="map"></div>
    </main>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script>
        const inputCari = document.getElementById('input-cari');
        const boxRekomendasi = document.getElementById('rekomendasi-list');
        const filterJenjang = document.getElementById('filter-jenjang');
        const filterStatus = document.getElementById('filter-status');
        const filterKecamatan = document.getElementById('filter-kecamatan');
        const jumlahElement = document.getElementById('jumlah');
        let dataSekolahList = [];
        let requestController = null;

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
            attribution: '&copy; OpenTopoMap'
        });
        const map = L.map('map', {
            center: [-1.8374614675638135, 115.63343545771596],
            zoom: 12,
            layers: [osm],
            zoomControl: false
        });
        L.control.zoom({
            position: 'bottomright'
        }).addTo(map);
        const markersCluster = L.markerClusterGroup({
            showCoverageOnHover: false,
            spiderfyOnMaxZoom: true,
            maxClusterRadius: 50
        });
        map.addLayer(markersCluster);
        L.control.layers({
            'OpenStreetMap': osm,
            'Satelit (Esri)': satelit,
            'Topografi': topo
        }, {
            'Titik Sekolah': markersCluster
        }, {
            position: 'topright',
            collapsed: true
        }).addTo(map);

        function esc(teks) {
            const div = document.createElement('div');
            div.textContent = teks ?? '';
            return div.innerHTML;
        }

        const lapisan = L.geoJSON(null, {
            // Validasi koordinat sekolah
            filter: fitur => {
                if (!fitur.geometry || fitur.geometry.type !== 'Point') {
                    return false;
                }
                const koordinat = fitur.geometry.coordinates;
                return Array.isArray(koordinat)
                    && koordinat.length >= 2
                    && Number.isFinite(koordinat[0])
                    && Number.isFinite(koordinat[1])
                    && koordinat[0] >= -180
                    && koordinat[0] <= 180
                    && koordinat[1] >= -90
                    && koordinat[1] <= 90;
            },
            onEachFeature: (fitur, layer) => {
                const p = fitur.properties || {};
                const [lng, lat] = fitur.geometry.coordinates;

                // Mengamankan teks HTML
                const aman = nilai => String(nilai ?? '').replace(
                    /[&<>"']/g,
                    karakter => ({
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#39;'
                    })[karakter]
                );

                // Format jumlah siswa dan guru
                const formatAngka = nilai => {
                    if (nilai === null || nilai === undefined || nilai === '') {
                        return '-';
                    }
                    const angka = Number(nilai);
                    return Number.isFinite(angka)
                        ? angka.toLocaleString('id-ID')
                        : '-';
                };

                const urlProfil = p.url_profil || null;

                // Data untuk fitur pencarian sekolah
                dataSekolahList.push({
                    nama: p.nama || 'Tanpa Nama',
                    jenjang: p.jenjang || '-',
                    status: p.status || '-',
                    layer: layer,
                    latlng: [lat, lng]
                });

                // Logo sekolah
                const logo = p.logo
                    ? `
                        <img
                            src="${aman(p.logo)}"
                            alt="Logo ${aman(p.nama || 'Sekolah')}"
                            onerror="
                                this.hidden = true;
                                this.nextElementSibling.hidden = false;
                            "
                            class="school-popup-logo"
                        >
                        <div class="school-popup-logo-fallback" hidden>🏫</div>
                    `
                    : `
                        <div class="school-popup-logo-fallback">🏫</div>
                    `;

                // Tombol profil publik
                const tombolProfil = urlProfil
                    ? `
                        <a
                            href="${aman(urlProfil)}"
                            class="school-popup-profile-link"
                        >
                            <i class="bi bi-building"></i>
                            Lihat Profil Publik
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    `
                    : `
                        <div class="school-popup-profile-unavailable">
                            Profil publik belum tersedia
                        </div>
                    `;

                // Tampilan popup sekolah
                const popup = `
                    <div class="school-popup">
                        <!-- Header sekolah -->
                        <div class="school-popup-header">
                            ${logo}
                            <div class="school-popup-header-content">
                                <h3 class="school-popup-title">
                                    ${aman(p.nama || 'Tanpa Nama')}
                                </h3>
                                <div class="school-popup-badges">
                                    <span class="badge">
                                        ${aman(p.jenjang || '-')}
                                    </span>
                                    <span class="badge badge-status">
                                        ${aman(p.status || '-')}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi sekolah -->
                        <div class="popup-row">
                            <i class="bi bi-upc-scan"></i>
                            <span>
                                <b>NPSN:</b>
                                ${aman(p.npsn || '-')}
                            </span>
                        </div>
                        <div class="popup-row">
                            <i class="bi bi-geo-alt"></i>
                            <span>
                                <b>Alamat:</b>
                                ${aman(p.alamat || '-')}
                            </span>
                        </div>
                        <div class="popup-row">
                            <i class="bi bi-map"></i>
                            <span>
                                <b>Kecamatan:</b>
                                ${aman(p.kecamatan || '-')}
                            </span>
                        </div>
                        <div class="popup-row">
                            <i class="bi bi-telephone"></i>
                            <span>
                                <b>Telepon:</b>
                                ${aman(p.telepon || '-')}
                            </span>
                        </div>

                        <!-- Profil sekolah -->
                        <div class="school-popup-section">
                            Profil Sekolah
                        </div>
                        <div class="popup-row">
                            <i class="bi bi-person-badge"></i>
                            <span>
                                <b>Kepala Sekolah:</b>
                                ${aman(p.kepala_sekolah || '-')}
                            </span>
                        </div>
                        <div class="popup-row">
                            <i class="bi bi-award"></i>
                            <span>
                                <b>Akreditasi:</b>
                                ${aman(p.akreditasi || '-')}
                            </span>
                        </div>
                        <div class="popup-row">
                            <i class="bi bi-people"></i>
                            <span>
                                <b>Jumlah Siswa:</b>
                                ${formatAngka(p.jumlah_siswa)}
                            </span>
                        </div>
                        <div class="popup-row">
                            <i class="bi bi-person-workspace"></i>
                            <span>
                                <b>Jumlah Guru:</b>
                                ${formatAngka(p.jumlah_guru)}
                            </span>
                        </div>

                        <!-- Koordinat sekolah -->
                        <div class="school-popup-coordinate-section">
                            <div class="school-popup-coordinate-label">
                                <i class="bi bi-crosshair"></i>
                                Koordinat:
                                ${lat.toFixed(6)}, ${lng.toFixed(6)}
                            </div>
                            <!-- Tombol menuju profil publik Laravel -->
                            ${tombolProfil}
                        </div>
                    </div>
                `;

                // Hubungkan popup dengan marker
                layer.bindPopup(popup, {
                    maxWidth: 340,
                    minWidth: 270,
                    autoPan: true,
                    keepInView: true
                });
            }
        });

        async function muatData() {
            if (requestController) requestController.abort();
            const controller = new AbortController();
            requestController = controller;
            const params = new URLSearchParams({
                jenjang: filterJenjang.value,
                status: filterStatus.value,
                kecamatan: filterKecamatan.value
            });
            const url = `{{ route('peta.data') }}?${params}`;
            jumlahElement.textContent = '...';
            try {
                const respon = await fetch(url, {
                    signal: controller.signal,
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                if (!respon.ok) throw new Error(`HTTP ${respon.status}`);
                const data = await respon.json();
                if (data.type !== 'FeatureCollection' || !Array.isArray(data.features)) {
                    throw new Error('Format GeoJSON tidak valid');
                }
                dataSekolahList = [];
                lapisan.clearLayers();
                markersCluster.clearLayers();
                lapisan.addData(data);
                markersCluster.addLayer(lapisan);
                jumlahElement.textContent = dataSekolahList.length.toLocaleString('id-ID');
                inputCari.value = '';
                boxRekomendasi.style.display = 'none';
                if (lapisan.getLayers().length > 0) {
                    map.fitBounds(lapisan.getBounds().pad(0.15), {
                        maxZoom: 15
                    });
                }
            } catch (error) {
                if (error.name === 'AbortError') return;
                console.error('Gagal memuat data GeoJSON:', error);
                jumlahElement.textContent = 'Error';
            } finally {
                if (requestController === controller) requestController = null;
            }
        }

        inputCari.addEventListener('input', function() {
            const kataKunci = this.value.trim().toLowerCase();
            boxRekomendasi.innerHTML = '';
            if (!kataKunci) {
                boxRekomendasi.style.display = 'none';
                return;
            }
            const hasil = dataSekolahList.filter(item =>
                item.nama.toLowerCase().includes(kataKunci)
            ).slice(0, 20);
            if (hasil.length === 0) {
                boxRekomendasi.innerHTML =
                    '<div class="rekomendasi-item"><div class="rekomendasi-nama">Sekolah tidak ditemukan</div></div>';
                boxRekomendasi.style.display = 'block';
                return;
            }
            hasil.forEach(item => {
                const div = document.createElement('div');
                div.className = 'rekomendasi-item';
                div.innerHTML = `
                    <div class="rekomendasi-nama">${esc(item.nama)}</div>
                    <div class="rekomendasi-info"><i class="bi bi-mortarboard"></i>${esc(item.jenjang)} &bull; ${esc(item.status)}</div>
                `;
                div.addEventListener('click', () => {
                    inputCari.value = item.nama;
                    boxRekomendasi.style.display = 'none';
                    if (!map.hasLayer(markersCluster)) map.addLayer(markersCluster);
                    markersCluster.zoomToShowLayer(item.layer, () => item.layer.openPopup());
                });
                boxRekomendasi.appendChild(div);
            });
            boxRekomendasi.style.display = 'block';
        });

        filterJenjang.addEventListener('change', muatData);
        filterStatus.addEventListener('change', muatData);
        filterKecamatan.addEventListener('change', muatData);
        document.getElementById('btn-reset').addEventListener('click', () => {
            filterJenjang.value = '';
            filterStatus.value = '';
            filterKecamatan.value = '';
            inputCari.value = '';
            boxRekomendasi.style.display = 'none';
            muatData();
        });
        document.addEventListener('click', event => {
            if (!event.target.closest('.search-box')) boxRekomendasi.style.display = 'none';
        });
        window.addEventListener('resize', () => map.invalidateSize());
        muatData();
    </script>
</body>
</html>
