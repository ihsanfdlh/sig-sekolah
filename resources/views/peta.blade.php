
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebGIS Peta Sekolah</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #247555;
            --primary-dark: #1a6048;
            --background: #f1f5f4;
            --white: #fff;
            --text: #192a22;
            --muted: #68786f;
            --border: #dfe8e1;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            width: 100%;
            height: 100%;
        }
        body {
            font-family: system-ui, -apple-system, sans-serif;
            color: var(--text);
            background:
                radial-gradient(ellipse at 10% 0%, rgba(180, 218, 196, .36), transparent 34%),
                radial-gradient(ellipse at 100% 20%, rgba(199, 210, 254, .32), transparent 38%),
                linear-gradient(145deg, #f3f7f5, #eef2f8 58%, #f7f8f6);
            display: flex;
            flex-direction: column;
        }
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1200;
            width: 100%;
            min-height: 76px;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            gap: 22px;
            overflow: hidden;
            color: white;
            background: linear-gradient(110deg, #123e32, #312e81, #0f172a);
            box-shadow: 0 10px 24px rgba(15, 23, 42, .2);
        }
        .header-background-art {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            overflow: hidden;
            opacity: .1;
            pointer-events: none;
        }
        .header-background-art svg {
            width: 24rem;
            height: 24rem;
            margin-right: -2.5rem;
            transform: rotate(6deg);
        }
        .brand {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 14px;
            flex: 0 0 auto;
        }
        .brand-icon {
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, .2);
            border-radius: 16px;
            background: rgba(255, 255, 255, .1);
            color: #b4dac4;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .12);
        }
        .brand-text h1 {
            color: white;
            font-size: 18px;
            font-weight: 700;
            letter-spacing: -.02em;
            line-height: 1.2;
            white-space: nowrap;
        }
        .brand-text p {
            margin-top: 3px;
            color: rgba(180, 218, 196, .8);
            font-size: 12px;
        }
        .header-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            min-width: 0;
            position: relative;
            z-index: 1;
        }
        .search-box {
            position: relative;
            flex: 1;
            min-width: 180px;
            max-width: 420px;
        }
        .search-box > i {
            position: absolute;
            top: 50%;
            left: 14px;
            transform: translateY(-50%);
            color: var(--muted);
            pointer-events: none;
        }
        .search-input {
            width: 100%;
            height: 42px;
            padding: 0 15px 0 40px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: rgba(255, 255, 255, .76);
            backdrop-filter: blur(12px);
            color: var(--text);
            font-size: 13px;
            outline: none;
            transition: .2s;
        }
        .search-input:focus {
            border-color: var(--primary);
            background: rgba(255, 255, 255, .9);
            backdrop-filter: blur(16px);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, .1);
        }
        .rekomendasi-box {
            position: absolute;
            top: calc(100% + 8px);
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, .76);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border);
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .15);
            max-height: 320px;
            overflow-y: auto;
            display: none;
            z-index: 2000;
        }
        .rekomendasi-item {
            padding: 12px 14px;
            border-bottom: 1px solid #f1f5f9;
            cursor: pointer;
            transition: .2s;
        }
        .rekomendasi-item:last-child {
            border-bottom: none;
        }
        .rekomendasi-item:hover {
            background: #eff6ff;
        }
        .rekomendasi-nama {
            font-size: 13px;
            font-weight: 650;
            margin-bottom: 5px;
        }
        .rekomendasi-info {
            font-size: 11px;
            color: var(--muted);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .filter-wrapper {
            display: flex;
            align-items: center;
            gap: 9px;
        }
        .filter-select {
            height: 42px;
            min-width: 130px;
            padding: 0 12px;
            background: rgba(255, 255, 255, .76);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border);
            border-radius: 9px;
            color: #334155;
            font-size: 13px;
            cursor: pointer;
            outline: none;
        }
        .filter-select:focus {
            border-color: var(--primary);
        }
        .btn-reset {
            height: 42px;
            padding: 0 14px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: rgba(255, 255, 255, .84);
            backdrop-filter: blur(16px);
            color: #475569;
            font-size: 13px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            cursor: pointer;
            transition: .2s;
        }
        .btn-reset:hover {
            background: #f1f5f9;
        }
        .btn-admin {
            height: 42px;
            padding: 0 15px;
            background: var(--primary);
            color: white;
            border-radius: 9px;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            transition: .2s;
        }
        .btn-admin:hover {
            background: var(--primary-dark);
        }
        /* Logout: konsisten dengan tombol navbar */
        .form-logout {
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
        }
        .btn-logout {
            height: 42px;
            padding: 0 15px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: rgba(239, 68, 68, .15);
            border: 1px solid rgba(248, 113, 113, .4);
            border-radius: 9px;
            color: #fecaca;
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            white-space: nowrap;
            cursor: pointer;
            transition: .2s;
        }
        .btn-logout:hover {
            background: #dc2626;
            border-color: #dc2626;
            color: white;
            box-shadow: 0 4px 12px rgba(220, 38, 38, .25);
        }
        .btn-logout:active {
            transform: scale(.98);
        }
        .btn-logout:focus-visible {
            outline: 2px solid #fecaca;
            outline-offset: 2px;
        }
        .btn-logout i {
            font-size: 16px;
        }
        .stat-header {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 12px;
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 9px;
            background: rgba(255, 255, 255, .1);
            color: white;
            white-space: nowrap;
        }
        .stat-header i {
            color: #bfdbfe;
            font-size: 17px;
        }
        .stat-header strong {
            font-size: 14px;
            color: white;
        }
        .stat-header span {
            font-size: 12px;
            color: #dbeafe;
        }
        .map-container {
            position: relative;
            flex: 1;
            min-height: 0;
            width: 100%;
        }
        #map {
            width: 100%;
            height: 100%;
            z-index: 1;
        }
        .map-info {
            position: absolute;
            left: 20px;
            top: 20px;
            z-index: 500;
            background: rgba(255, 255, 255, .82);
            backdrop-filter: blur(18px);
            padding: 13px 17px;
            border-radius: 11px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .12);
            pointer-events: none;
        }
        .map-info h3 {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 5px;
        }
        .map-info h3 i {
            color: var(--primary);
            margin-right: 5px;
        }
        .map-info p {
            font-size: 11px;
            color: var(--muted);
        }
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            padding: 4px;
            background: rgba(255, 255, 255, .9);
            backdrop-filter: blur(18px);
        }
        .leaflet-popup-content {
            margin: 12px;
            min-width: 220px;
        }
        .popup-title {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 11px;
            padding-right: 12px;
        }
        .popup-badges {
            display: flex;
            gap: 6px;
            margin-bottom: 14px;
        }
        .badge {
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 11px;
            font-weight: 600;
            padding: 5px 9px;
            border-radius: 6px;
        }
        .badge-status {
            background: #dcfce7;
            color: #15803d;
        }
        .popup-row {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 10px;
        }
        .popup-row i {
            color: var(--primary);
            font-size: 14px;
        }
        .popup-row span {
            color: #475569;
        }
        @media (max-width: 1250px) {
            .navbar {
                gap: 15px;
                padding: 12px 16px;
            }
            .brand-text p {
                display: none;
            }
            .filter-select {
                min-width: 110px;
            }
            .btn-reset span {
                display: none;
            }
        }
        @media (max-width: 1000px) {
            .navbar {
                flex-wrap: wrap;
                gap: 12px;
            }
            .brand {
                width: 100%;
            }
            .header-controls {
                width: 100%;
                flex: auto;
            }
            .search-box {
                max-width: none;
            }
        }
        @media (max-width: 650px) {
            .navbar {
                padding: 10px 12px;
            }
            .brand-icon {
                width: 36px;
                height: 36px;
            }
            .brand-text h1 {
                font-size: 15px;
            }
            .header-controls {
                flex-wrap: wrap;
            }
            .search-box {
                flex: 1 1 100%;
                max-width: none;
            }
            .filter-wrapper {
                flex: 1;
            }
            .filter-select {
                flex: 1;
                min-width: 0;
                width: 50%;
            }
            .stat-header {
                flex: 1;
                justify-content: center;
            }
            .map-info {
                top: 12px;
                left: 12px;
                padding: 10px 12px;
            }
            .map-info h3 {
                font-size: 12px;
            }
            .map-info p {
                font-size: 10px;
            }
        }
    </style>
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
                            style="
                                width:80px;
                                height:80px;
                                object-fit:contain;
                                border-radius:12px;
                                border:1px solid #e2e8f0;
                                padding:6px;
                                background:white;
                                flex-shrink:0;
                            "
                            onerror="
                                this.style.display='none';
                                this.nextElementSibling.style.display='flex';
                            "
                        >
                        <div style="
                            display:none;
                            width:80px;
                            height:80px;
                            align-items:center;
                            justify-content:center;
                            background:#eff6ff;
                            border-radius:12px;
                            font-size:32px;
                            flex-shrink:0;
                        ">🏫</div>
                    `
                    : `
                        <div style="
                            display:flex;
                            width:80px;
                            height:80px;
                            align-items:center;
                            justify-content:center;
                            background:#eff6ff;
                            border-radius:12px;
                            font-size:32px;
                            flex-shrink:0;
                        ">🏫</div>
                    `;

                // Tombol profil publik
                const tombolProfil = urlProfil
                    ? `
                        <a
                            href="${aman(urlProfil)}"
                            style="
                                display:flex;
                                align-items:center;
                                justify-content:center;
                                gap:8px;
                                width:100%;
                                box-sizing:border-box;
                                padding:12px;
                                border-radius:9px;
                                background:#2563eb;
                                color:#ffffff;
                                font-size:13px;
                                font-weight:600;
                                text-decoration:none;
                                cursor:pointer;
                                margin-top:12px;
                            "
                        >
                            <i class="bi bi-building"></i>
                            Lihat Profil Publik
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    `
                    : `
                        <div style="
                            margin-top:12px;
                            padding:10px;
                            background:#f1f5f9;
                            color:#64748b;
                            border-radius:8px;
                            font-size:12px;
                            text-align:center;
                        ">
                            Profil publik belum tersedia
                        </div>
                    `;

                // Tampilan popup sekolah
                const popup = `
                    <div style="
                        width:270px;
                        max-width:100%;
                        font-family:system-ui,sans-serif;
                        color:#0f172a;
                    ">
                        <!-- Header sekolah -->
                        <div style="
                            display:flex;
                            align-items:center;
                            gap:12px;
                            padding-bottom:14px;
                            margin-bottom:14px;
                            border-bottom:1px solid #e2e8f0;
                        ">
                            ${logo}
                            <div style="min-width:0;flex:1;">
                                <h3 style="
                                    font-size:15px;
                                    font-weight:700;
                                    line-height:1.4;
                                    margin:0 0 8px;
                                    overflow-wrap:anywhere;
                                ">
                                    ${aman(p.nama || 'Tanpa Nama')}
                                </h3>
                                <div style="
                                    display:flex;
                                    gap:5px;
                                    flex-wrap:wrap;
                                ">
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
                        <div style="
                            border-top:1px solid #e2e8f0;
                            margin:12px 0;
                            padding-top:12px;
                            font-size:12px;
                            font-weight:700;
                            color:#2563eb;
                        ">
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
                        <div style="
                            border-top:1px solid #e2e8f0;
                            padding-top:12px;
                            margin-top:12px;
                        ">
                            <div style="
                                font-size:11px;
                                color:#64748b;
                                margin-bottom:8px;
                            ">
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
