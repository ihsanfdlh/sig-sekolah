@extends('layouts.public')
@section('title', 'Peta Panas')
 
@section('content')
<div class="toolbar">
    <label>Bobot
        <select id="mode">
            <option value="sekolah">Jumlah sekolah</option>
            <option value="siswa">Jumlah siswa</option>
        </select>
    </label>
    <label>Jenjang
        <select id="jenjang">
            <option value="">Semua jenjang</option>
            <option value="SD">SD</option>
            <option value="SMP">SMP</option>
            <option value="SMA">SMA</option>
            <option value="SMK">SMK</option>
        </select>
    </label>
    <label>Radius
        <input type="range" id="radius" min="10" max="60" value="30">
    </label>
    <span id="jumlah"></span>
</div>
 
<div id="peta" class="peta"></div>
 
<div class="panel" style="left: 12px; bottom: 24px; width: 180px;">
    <div style="height: 10px; border-radius: 5px;
        background: linear-gradient(to right, #2563eb, #06b6d4, #84cc16,
                                    #facc15, #dc2626);"></div>
    <div style="display: flex; justify-content: space-between;
                font-size: 12px; margin-top: 4px;">
        <span>Rendah</span><span>Tinggi</span>
    </div>
</div>
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
    'Satelit (Esri)': L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: atribusiEsri,
        maxZoom: 18
    }),
    'Satelit + Nama Lokasi': L.layerGroup([
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: atribusiEsri,
            maxZoom: 18
        }),
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            attribution: atribusiEsri,
            maxZoom: 18
        })
    ]),
    'Topografi (Esri)': L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
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
const overlays = {
    'Marker Sekolah': layerMarker,
    'Klaster Sekolah': layerKlaster,
    'Peta Panas': layerPanas
};
L.control.layers(baseLayers, overlays, {
    collapsed: true,
    position: 'topright',
    autoZIndex: true
}).addTo(map);
    let heat = null;
    let data = [];
 
    function gambar() {
        const mode = document.getElementById('mode').value;
        const radius = Number(document.getElementById('radius').value);
        const maks = Math.max(1, ...data.map((f) => f.properties.jumlah_siswa ?? 0));
 
        const titik = data.map((f) => {
            const siswa = f.properties.jumlah_siswa ?? 0;
            const bobot = mode === 'siswa' ? Math.max(siswa / maks, 0.1) : 0.35;
 
            return [...Peta.latlng(f), bobot];
        });
 
        if (heat) {
            map.removeLayer(heat);
        }
 
        heat = L.heatLayer(titik, {
            radius,
            blur: 25,
            maxZoom: 17,
            max: 1.0,
            gradient: {
                0.2: '#2563eb',
                0.4: '#06b6d4',
                0.6: '#84cc16',
                0.8: '#facc15',
                1.0: '#dc2626',
            },
        }).addTo(map);
 
        document.getElementById('jumlah').textContent =
            data.length + ' sekolah';
    }
 
    async function muat() {
        data = await Peta.ambilSekolah(document.getElementById('jenjang').value);
        gambar();
 
        if (data.length > 0) {
            map.fitBounds(L.latLngBounds(data.map(Peta.latlng)).pad(0.1));
        }
    }
 
    document.getElementById('jenjang').addEventListener('change', muat);
    document.getElementById('mode').addEventListener('change', gambar);
    document.getElementById('radius').addEventListener('input', gambar);
 
    muat();
</script>
@endpush
