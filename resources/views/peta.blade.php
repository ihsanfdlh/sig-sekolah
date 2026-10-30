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
        :root { --primary:#2563eb; --primary-dark:#1d4ed8; --background:#f8fafc; --white:#fff; --text:#0f172a; --muted:#64748b; --border:#e2e8f0; }
        * { margin:0; padding:0; box-sizing:border-box; }
        html,body { width:100%; height:100%; }
        body { font-family:system-ui,-apple-system,sans-serif; color:var(--text); background:var(--background); display:flex; flex-direction:column; }
        .navbar { width:100%; min-height:72px; background:var(--white); border-bottom:1px solid var(--border); display:flex; align-items:center; gap:22px; padding:12px 24px; position:relative; z-index:1200; box-shadow:0 2px 12px rgba(0,0,0,.04); }
        .brand { display:flex; align-items:center; gap:11px; flex-shrink:0; }
        .brand-icon { width:43px; height:43px; display:flex; align-items:center; justify-content:center; background:var(--primary); color:white; border-radius:11px; font-size:21px; }
        .brand-text h1 { font-size:17px; font-weight:750; white-space:nowrap; }
        .brand-text p { font-size:11px; color:var(--muted); margin-top:3px; }
        .header-controls { display:flex; align-items:center; gap:10px; flex:1; min-width:0; }
        .search-box { position:relative; flex:1; min-width:180px; max-width:420px; }
        .search-box > i { position:absolute; top:50%; left:14px; transform:translateY(-50%); color:var(--muted); pointer-events:none; }
        .search-input { width:100%; height:42px; padding:0 15px 0 40px; border:1px solid var(--border); border-radius:9px; background:#f8fafc; color:var(--text); font-size:13px; outline:none; transition:.2s; }
        .search-input:focus { border-color:var(--primary); background:white; box-shadow:0 0 0 3px rgba(37,99,235,.1); }
        .rekomendasi-box { position:absolute; top:calc(100% + 8px); left:0; right:0; background:white; border:1px solid var(--border); border-radius:10px; box-shadow:0 10px 30px rgba(0,0,0,.15); max-height:320px; overflow-y:auto; display:none; z-index:2000; }
        .rekomendasi-item { padding:12px 14px; border-bottom:1px solid #f1f5f9; cursor:pointer; transition:.2s; }
        .rekomendasi-item:last-child { border-bottom:none; }
        .rekomendasi-item:hover { background:#eff6ff; }
        .rekomendasi-nama { font-size:13px; font-weight:650; margin-bottom:5px; }
        .rekomendasi-info { font-size:11px; color:var(--muted); display:flex; align-items:center; gap:6px; }
        .filter-wrapper { display:flex; align-items:center; gap:9px; }
        .filter-select { height:42px; min-width:130px; padding:0 12px; background:white; border:1px solid var(--border); border-radius:9px; color:#334155; font-size:13px; cursor:pointer; outline:none; }
        .filter-select:focus { border-color:var(--primary); }
        .btn-reset { height:42px; padding:0 14px; border:1px solid var(--border); border-radius:9px; background:white; color:#475569; font-size:13px; font-weight:600; display:flex; align-items:center; justify-content:center; gap:7px; cursor:pointer; transition:.2s; }
        .btn-reset:hover { background:#f1f5f9; }
        .btn-admin { height:42px; padding:0 15px; background:var(--primary); color:white; border-radius:9px; text-decoration:none; display:flex; align-items:center; justify-content:center; gap:8px; font-size:13px; font-weight:600; white-space:nowrap; transition:.2s; }
        .btn-admin:hover { background:var(--primary-dark); }
        .stat-header { display:flex; align-items:center; gap:8px; padding:9px 12px; background:#eff6ff; border:1px solid #dbeafe; border-radius:9px; white-space:nowrap; }
        .stat-header i { color:var(--primary); font-size:17px; }
        .stat-header strong { font-size:14px; color:var(--primary); }
        .stat-header span { font-size:12px; color:var(--muted); }
        .map-container { position:relative; flex:1; min-height:0; width:100%; }
        #map { width:100%; height:100%; z-index:1; }
        .map-info { position:absolute; left:20px; top:20px; z-index:500; background:white; padding:13px 17px; border-radius:11px; box-shadow:0 4px 20px rgba(0,0,0,.12); pointer-events:none; }
        .map-info h3 { font-size:13px; font-weight:700; margin-bottom:5px; }
        .map-info h3 i { color:var(--primary); margin-right:5px; }
        .map-info p { font-size:11px; color:var(--muted); }
        .leaflet-popup-content-wrapper { border-radius:12px; padding:4px; }
        .leaflet-popup-content { margin:12px; min-width:220px; }
        .popup-title { font-size:15px; font-weight:700; margin-bottom:11px; padding-right:12px; }
        .popup-badges { display:flex; gap:6px; margin-bottom:14px; }
        .badge { background:#dbeafe; color:#1d4ed8; font-size:11px; font-weight:600; padding:5px 9px; border-radius:6px; }
        .badge-status { background:#dcfce7; color:#15803d; }
        .popup-row { display:flex; align-items:flex-start; gap:9px; font-size:12px; line-height:1.5; margin-bottom:10px; }
        .popup-row i { color:var(--primary); font-size:14px; }
        .popup-row span { color:#475569; }
        @media (max-width:1250px) {
            .navbar { gap:15px; padding:12px 16px; }
            .brand-text p { display:none; }
            .filter-select { min-width:110px; }
            .btn-reset span { display:none; }
        }
        @media (max-width:1000px) {
            .navbar { flex-wrap:wrap; gap:12px; }
            .brand { width:100%; }
            .header-controls { width:100%; flex:auto; }
            .search-box { max-width:none; }
        }
        @media (max-width:650px) {
            .navbar { padding:10px 12px; }
            .brand-icon { width:36px; height:36px; font-size:18px; }
            .brand-text h1 { font-size:15px; }
            .header-controls { flex-wrap:wrap; }
            .search-box { flex:1 1 100%; max-width:none; }
            .filter-wrapper { flex:1; }
            .filter-select { flex:1; min-width:0; width:50%; }
            .stat-header { flex:1; justify-content:center; }
            .btn-admin span { display:none; }
            .btn-admin { padding:0 13px; }
            .map-info { top:12px; left:12px; padding:10px 12px; }
            .map-info h3 { font-size:12px; }
            .map-info p { font-size:10px; }
        }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="brand">
            <div class="brand-icon"><i class="bi bi-geo-alt-fill"></i></div>
            <div class="brand-text"><h1>WebGIS Peta Sekolah</h1><p>Sistem Informasi Geografis Sekolah</p></div>
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
                    <option value="SD">SD</option><option value="SMP">SMP</option>
                    <option value="SMA">SMA</option><option value="SMK">SMK</option>
                </select>
                <select id="filter-status" class="filter-select" aria-label="Filter status sekolah">
                    <option value="">Semua Status</option>
                    <option value="Negeri">Negeri</option><option value="Swasta">Swasta</option>
                </select>
                <select id="filter-kecamatan" class="filter-select" aria-label="Filter kecamatan">
                    <option value="">Semua Kecamatan</option>
                    @foreach ($kecamatan as $item)
                        <option value="{{ $item->kecamatan }}">{{ $item->kecamatan }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn-reset" id="btn-reset" title="Reset Filter"><i class="bi bi-arrow-counterclockwise"></i><span>Reset</span></button>
            <div class="stat-header"><i class="bi bi-buildings"></i><strong id="jumlah">0</strong><span>Sekolah</span></div>
            <a href="{{ route('admin.sekolah.index') }}" class="btn-admin" title="Kelola Data Sekolah"><i class="bi bi-gear-fill"></i><span>Kelola Data</span></a>
        </div>
    </header>
    <main class="map-container">
        <div class="map-info"><h3><i class="bi bi-map"></i>Peta Persebaran Sekolah</h3><p>Klik titik lokasi untuk melihat informasi sekolah</p></div>
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
            maxZoom:18, attribution:'&copy; OpenStreetMap contributors'
        });
        const satelit = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom:18, attribution:'Tiles &copy; Esri'
        });
        const topo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
            maxZoom:17, attribution:'&copy; OpenTopoMap'
        });
        const map = L.map('map', {
            center:[-1.8374614675638135,115.63343545771596], zoom:12, layers:[osm], zoomControl:false
        });
        L.control.zoom({ position:'bottomright' }).addTo(map);
        const markersCluster = L.markerClusterGroup({
            showCoverageOnHover:false, spiderfyOnMaxZoom:true, maxClusterRadius:50
        });
        map.addLayer(markersCluster);
        L.control.layers(
            { 'OpenStreetMap':osm, 'Satelit (Esri)':satelit, 'Topografi':topo },
            { 'Titik Sekolah':markersCluster },
            { position:'topright', collapsed:true }
        ).addTo(map);

        function esc(teks) {
            const div = document.createElement('div');
            div.textContent = teks ?? '';
            return div.innerHTML;
        }

        const lapisan = L.geoJSON(null, {
            filter: fitur => {
                if (!fitur.geometry || fitur.geometry.type !== 'Point') return false;
                const koordinat = fitur.geometry.coordinates;
                return Array.isArray(koordinat) && koordinat.length >= 2 &&
                    Number.isFinite(koordinat[0]) && Number.isFinite(koordinat[1]) &&
                    koordinat[0] >= -180 && koordinat[0] <= 180 &&
                    koordinat[1] >= -90 && koordinat[1] <= 90;
            },
            onEachFeature: (fitur, layer) => {
                const p = fitur.properties || {};
                const [lng, lat] = fitur.geometry.coordinates;
                dataSekolahList.push({
                    nama:p.nama || 'Tanpa Nama', jenjang:p.jenjang || '-',
                    status:p.status || '-', layer, latlng:[lat,lng]
                });
                layer.bindPopup(`
                    <div class="popup-title">${esc(p.nama || 'Tanpa Nama')}</div>
                    <div class="popup-badges">
                        <span class="badge">${esc(p.jenjang || '-')}</span>
                        <span class="badge badge-status">${esc(p.status || '-')}</span>
                    </div>
                    <div class="popup-row"><i class="bi bi-card-text"></i><span><b>NPSN:</b> ${esc(p.npsn || '-')}</span></div>
                    <div class="popup-row"><i class="bi bi-geo-alt"></i><span>${esc(p.alamat || '-')}, ${esc(p.kecamatan || '-')}</span></div>
                    <div class="popup-row"><i class="bi bi-telephone"></i><span>${esc(p.telepon || '-')}</span></div>
                    <div class="popup-row"><i class="bi bi-crosshair"></i><span>${lat}, ${lng}</span></div>
                `);
            }
        });

        async function muatData() {
            if (requestController) requestController.abort();
            const controller = new AbortController();
            requestController = controller;
            const params = new URLSearchParams({
                jenjang:filterJenjang.value, status:filterStatus.value,
                kecamatan:filterKecamatan.value
            });
            const url = `{{ route('peta.data') }}?${params}`;
            jumlahElement.textContent = '...';
            try {
                const respon = await fetch(url, {
                    signal:controller.signal, headers:{ 'Accept':'application/json' }
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
                    map.fitBounds(lapisan.getBounds().pad(0.15), { maxZoom:15 });
                }
            } catch (error) {
                if (error.name === 'AbortError') return;
                console.error('Gagal memuat data GeoJSON:', error);
                jumlahElement.textContent = 'Error';
            } finally {
                if (requestController === controller) requestController = null;
            }
        }

        inputCari.addEventListener('input', function () {
            const kataKunci = this.value.trim().toLowerCase();
            boxRekomendasi.innerHTML = '';
            if (!kataKunci) {
                boxRekomendasi.style.display = 'none';
                return;
            }
            const hasil = dataSekolahList.filter(item =>
                item.nama.toLowerCase().includes(kataKunci)
            ).slice(0,20);
            if (hasil.length === 0) {
                boxRekomendasi.innerHTML = '<div class="rekomendasi-item"><div class="rekomendasi-nama">Sekolah tidak ditemukan</div></div>';
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
