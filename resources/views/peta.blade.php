<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Peta Sekolah</title>
  
  <!-- Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
  
  <!-- Leaflet MarkerCluster CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">

  <style>
    html, body { 
      height: 100%; 
      margin: 0; 
      font-family: system-ui, sans-serif; 
    }
    header { 
      height: 56px; 
      box-sizing: border-box; 
      padding: 0 16px; 
      display: flex; 
      align-items: center; 
      gap: 16px; 
      background: #1e3a8a; 
      color: #fff; 
    }
    header h1 { font-size: 18px; margin: 0; }
    header a { color: #bfdbfe; margin-left: auto; }
    
    .search-box {
      position: relative;
      width: 260px;
    }

    .search-box input {
      width: 100%;
      padding: 7px 12px;
      border-radius: 6px;
      border: 1px solid #cbd5e1;
      outline: none;
      color: #1e293b;
      box-sizing: border-box;
    }

    .search-box input:focus {
      border-color: #3b82f6;
      box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }

    .rekomendasi-box {
      position: absolute;
      top: 100%;
      left: 0;
      right: 0;
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      max-height: 220px;
      overflow-y: auto;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      z-index: 1000;
      display: none;
      margin-top: 4px;
    }

    .rekomendasi-item {
      padding: 8px 12px;
      cursor: pointer;
      color: #1e293b;
      font-size: 14px;
      border-bottom: 1px solid #f1f5f9;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .rekomendasi-item:last-child {
      border-bottom: none;
    }

    .rekomendasi-item:hover {
      background-color: #f1f5f9;
      color: #2563eb;
    }

    .tag-info {
      font-size: 11px;
      background-color: #e2e8f0;
      color: #475569;
      padding: 2px 6px;
      border-radius: 4px;
    }

    #map { height: calc(100% - 56px); }
  </style>
</head>
<body>
  <header>
    <h1>Peta Sekolah</h1>
    <select id="filter-jenjang">
      <option value="">Semua jenjang</option>
      <option value="SD">SD</option>
      <option value="SMP">SMP</option>
      <option value="SMA">SMA</option>
      <option value="SMK">SMK</option>
    </select>
    <select id="filter-status">
      <option value="">Semua Status</option>
      <option value="Negeri">Negeri</option>
      <option value="Swasta">Swasta</option>
    </select>
    <span id="jumlah"></span>
    
    <div class="search-box">
      <input 
        type="text" 
        id="input-cari" 
        placeholder="Cari nama sekolah..." 
        autocomplete="off"
      >
      <div id="rekomendasi-list" class="rekomendasi-box"></div>
    </div>

    <a href="{{ route('admin.sekolah.index') }}">Kelola Data</a>
  </header>

  <div id="map"></div>

  <!-- JS Leaflet & Plugin -->
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

  <script>
    let dataSekolahList = [];

    // --- 1. DEKLARASI PETA DASAR (BASEMAPS) ---
    const osm = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap contributors'
    });

    const satelit = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
      maxZoom: 19,
      attribution: 'Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
    });

    const topo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
      maxZoom: 17,
      attribution: 'Map data: &copy; OpenStreetMap contributors, SRTM | Map style: &copy; OpenTopoMap (CC-BY-SA)'
    });

    // Inisialisasi Peta dengan Basemap Utama (OSM)
    const map = L.map('map', {
      center: [-3.3194, 114.5908],
      zoom: 12,
      layers: [osm] // Basemap default
    });

    // --- 2. DEKLARASI MARKER CLUSTER ---
    const markersCluster = L.markerClusterGroup();
    map.addLayer(markersCluster);

    // --- 3. MENAMBAHKAN KONTROL LAYER ---
    const baseMaps = {
      "OpenStreetMap": osm,
      "Satelit (Esri)": satelit,
      "Topografi": topo
    };

    const overlayMaps = {
      "Titik Sekolah": markersCluster
    };

    L.control.layers(baseMaps, overlayMaps, { position: 'topright' }).addTo(map);

    function esc(teks) {
      const d = document.createElement('div');
      d.textContent = teks ?? '';
      return d.innerHTML;
    }

    const lapisan = L.geoJSON(null, {
      onEachFeature: (fitur, layer) => {
        const p = fitur.properties;
        const [lng, lat] = fitur.geometry.coordinates;

        dataSekolahList.push({
          nama: p.nama,
          jenjang: p.jenjang,
          status: p.status,
          layer: layer,
          latlng: [lat, lng]
        });

        layer.bindPopup(`
          <strong>${esc(p.nama)}</strong><br>
          NPSN: ${esc(p.npsn)}<br>
          ${esc(p.jenjang)} ${esc(p.status)}<br>
          ${esc(p.alamat)}, ${esc(p.kecamatan)}<br>
          Telp: ${esc(p.telepon) || '-'}<br>
          <small>Koordinat: ${lat}, ${lng}</small>
        `);
      }
    });

    async function muatData() {
      try {
        const jenjang = document.getElementById('filter-jenjang')?.value || '';
        const status = document.getElementById('filter-status')?.value || '';

        const url = `{{ route('peta.data') }}?jenjang=${jenjang}&status=${status}`;
        const respon = await fetch(url);
        const data = await respon.json();
        
        dataSekolahList = [];
        lapisan.clearLayers();
        markersCluster.clearLayers();

        lapisan.addData(data);
        markersCluster.addLayer(lapisan);
        
        document.getElementById('jumlah').textContent = (data.features ? data.features.length : 0) + ' sekolah';
        
        if (data.features && data.features.length > 0) {
          map.fitBounds(markersCluster.getBounds().pad(0.1));
        }
      } catch (error) {
        console.error('Gagal memuat dataGeoJSON:', error);
      }
    }

    // --- SEARCH REKOMENDASI ---
    const inputCari = document.getElementById('input-cari');
    const boxRekomendasi = document.getElementById('rekomendasi-list');

    if (inputCari && boxRekomendasi) {
      inputCari.addEventListener('input', (e) => {
        const kataKunci = e.target.value.trim().toLowerCase();
        boxRekomendasi.innerHTML = '';

        if (kataKunci === '') {
          boxRekomendasi.style.display = 'none';
          return;
        }

        const hasilFilter = dataSekolahList.filter(item => 
          item.nama.toLowerCase().includes(kataKunci)
        );

        if (hasilFilter.length === 0) {
          boxRekomendasi.innerHTML = '<div class="rekomendasi-item" style="cursor:default; color:#94a3b8;">Sekolah tidak ditemukan</div>';
          boxRekomendasi.style.display = 'block';
          return;
        }

        hasilFilter.forEach(item => {
          const div = document.createElement('div');
          div.className = 'rekomendasi-item';
          div.innerHTML = `
            <span>${esc(item.nama)}</span>
            <span class="tag-info">${esc(item.jenjang)} - ${esc(item.status)}</span>
          `;

          div.addEventListener('click', () => {
            inputCari.value = item.nama;
            boxRekomendasi.style.display = 'none';

            markersCluster.zoomToShowLayer(item.layer, () => {
              item.layer.openPopup();
            });
          });

          boxRekomendasi.appendChild(div);
        });

        boxRekomendasi.style.display = 'block';
      });

      document.addEventListener('click', (e) => {
        if (!e.target.closest('.search-box')) {
          boxRekomendasi.style.display = 'none';
        }
      });
    }

    document.getElementById('filter-jenjang')?.addEventListener('change', muatData);
    document.getElementById('filter-status')?.addEventListener('change', muatData);

    muatData();
  </script>
</body>
</html>