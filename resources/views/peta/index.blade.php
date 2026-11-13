
@extends('layouts.public')
@section('title', 'Peta Sekolah')
@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">
@endpush
@section('content')
<div class="toolbar">
    <label>Jenjang
        <select id="filter-jenjang">
            <option value="">Semua jenjang</option>
            <option value="SD">SD</option>
            <option value="SMP">SMP</option>
            <option value="MTS">MTS</option>
            <option value="SMA">SMA</option>
            <option value="SMK">SMK</option>
        </select>
    </label>
    <label>Status
        <select id="filter-status">
            <option value="">Semua status</option>
            <option value="Negeri">Negeri</option>
            <option value="Swasta">Swasta</option>
        </select>
    </label>
    <span id="jumlah" aria-live="polite"></span>
    <button type="button" id="btn-unduh-geojson" class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800 disabled:cursor-wait disabled:opacity-60">
        <i class="bi bi-download"></i>
        Unduh GeoJSON
    </button>
</div>
<div id="public-map-frame" class="public-map-frame is-loading" aria-busy="true">
    <div id="peta" class="peta"></div>
    <div id="peta-loading" class="public-map-loading" role="status" aria-live="polite">
        <span class="public-map-loading-spinner" aria-hidden="true"></span>
        <span>Memuat data sekolah...</span>
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
const filterJenjang = document.getElementById('filter-jenjang');
const filterStatus = document.getElementById('filter-status');
const jumlah = document.getElementById('jumlah');
const btnUnduh = document.getElementById('btn-unduh-geojson');
let controllerAktif = null;
let requestTerakhir = 0;
let timerPeta = null;
let pemuatanAwal = true;
let geojsonTerakhir = {
    type: 'FeatureCollection',
    features: []
};
function parameterPeta() {
    const bounds = map.getBounds();
    return new URLSearchParams({
        south: bounds.getSouth().toString(),
        north: bounds.getNorth().toString(),
        west: bounds.getWest().toString(),
        east: bounds.getEast().toString(),
        jenjang: filterJenjang.value,
        status: filterStatus.value
    });
}
async function muat() {
    const requestId = ++requestTerakhir;
    if (controllerAktif) {
        controllerAktif.abort();
    }
    const controller = new AbortController();
    controllerAktif = controller;
    if (pemuatanAwal) {
        window.aturLoadingPetaPublik(true);
        jumlah.textContent = 'Memuat data...';
    }
    try {
        const response = await fetch('/data/sekolah?' + parameterPeta().toString(), {
            signal: controller.signal,
            headers: {
                'Accept': 'application/json'
            }
        });
        if (!response.ok) {
            throw new Error('HTTP ' + response.status);
        }
        const data = await response.json();
        if (data.type !== 'FeatureCollection' || !Array.isArray(data.features)) {
            throw new Error('Format GeoJSON tidak valid.');
        }
        if (requestId !== requestTerakhir) return;
        geojsonTerakhir = data;
        layerMarker.clearLayers();
        layerKlaster.clearLayers();
        layerPanas.setLatLngs([]);
        const markers = [];
        const titikPanas = [];
        let jumlahValid = 0;
        data.features.forEach(function(f) {
            try {
                const koordinat = L.latLng(Peta.latlng(f));
                if (!Number.isFinite(koordinat.lat) || !Number.isFinite(koordinat.lng) || Math.abs(koordinat.lat) > 90 || Math.abs(koordinat.lng) > 180) {
                    return;
                }
                const popup = Peta.popup(f);
                L.marker(koordinat).bindPopup(popup).addTo(layerMarker);
                markers.push(L.marker(koordinat).bindPopup(popup));
                titikPanas.push([koordinat.lat, koordinat.lng, 1]);
                jumlahValid++;
            } catch (error) {
                console.warn('Titik sekolah tidak valid:', error);
            }
        });
        layerKlaster.addLayers(markers);
        layerPanas.setLatLngs(titikPanas);
        jumlah.textContent = jumlahValid.toLocaleString('id-ID') + ' sekolah dalam peta';
    } catch (error) {
        if (error.name === 'AbortError') return;
        if (requestId !== requestTerakhir) return;
        console.error('Gagal memuat data sekolah:', error);
        jumlah.textContent = 'Gagal memuat data';
    } finally {
        if (controllerAktif === controller) {
            controllerAktif = null;
            if (pemuatanAwal) {
                pemuatanAwal = false;
                window.aturLoadingPetaPublik(false);
            }
        }
    }
}
function jadwalkanMuat() {
    clearTimeout(timerPeta);
    timerPeta = setTimeout(muat, 350);
}
map.on('moveend', jadwalkanMuat);
filterJenjang.addEventListener('change', jadwalkanMuat);
filterStatus.addEventListener('change', jadwalkanMuat);
Object.entries(baseLayers).forEach(function([nama, layer]) {
    const pantau = function(tileLayer) {
        tileLayer.on('tileerror', function(error) {
            console.error('Gagal memuat tile ' + nama + ':', error);
        });
    };
    if (layer instanceof L.TileLayer) {
        pantau(layer);
    } else if (layer instanceof L.LayerGroup) {
        layer.eachLayer(function(subLayer) {
            if (subLayer instanceof L.TileLayer) {
                pantau(subLayer);
            }
        });
    }
});
map.on('baselayerchange', function(event) {
    console.log('Layer aktif:', event.name);
});
btnUnduh.addEventListener('click', function() {
    if (!geojsonTerakhir.features.length) {
        alert('Tidak ada data sekolah dalam area peta.');
        return;
    }
    const blob = new Blob([JSON.stringify(geojsonTerakhir, null, 2)], {
        type: 'application/geo+json'
    });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = 'sekolah-viewport.geojson';
    document.body.appendChild(link);
    link.click();
    link.remove();
    setTimeout(function() {
        URL.revokeObjectURL(url);
    }, 1000);
});
muat();
</script>
@endpush
