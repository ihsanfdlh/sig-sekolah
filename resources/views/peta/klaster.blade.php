@extends('layouts.public')
@section('title', 'Klaster')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css">
@endpush

@section('content')
    <div class="toolbar">
        <label>Jenjang
            <select id="filter-jenjang">
                <option value="">Semua jenjang</option>
                <option value="SD">SD</option>
                <option value="SMP">SMP</option>
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
        <span id="jumlah"></span>
        <span style="color: #6b7280;">
            Klik angka pada klaster untuk memperbesar peta.
        </span>
    </div>

    <div id="peta" class="peta"></div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>
    <script>
        < script src = "https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js" >
    </script>
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
            position: 'topright',
            autoZIndex: true
        }).addTo(map);


        const klaster = L.markerClusterGroup({
            showCoverageOnHover: false,
            maxClusterRadius: 60,
            spiderfyOnMaxZoom: true,
            disableClusteringAtZoom: 17,
            chunkedLoading: true,
        });
        map.addLayer(klaster);

        const filterJenjang = document.getElementById('filter-jenjang');
        const filterStatus = document.getElementById('filter-status');
        const jumlah = document.getElementById('jumlah');

        let requestTerakhir = 0;

        async function muat(jenjang = '', status = '') {
            const requestId = ++requestTerakhir;

            try {
                jumlah.textContent = 'Memuat data...';

                const fitur = await Peta.ambilSekolah(jenjang, status);

                if (requestId !== requestTerakhir) return;

                klaster.clearLayers();

                const markers = [];

                fitur.forEach((f) => {
                    try {
                        const koordinat = Peta.latlng(f);

                        if (!koordinat) return;

                        const latlng = L.latLng(koordinat);

                        if (
                            !Number.isFinite(latlng.lat) ||
                            !Number.isFinite(latlng.lng) ||
                            Math.abs(latlng.lat) > 90 ||
                            Math.abs(latlng.lng) > 180
                        ) {
                            return;
                        }

                        const marker = L.marker(latlng)
                            .bindPopup(Peta.popup(f));

                        markers.push(marker);

                    } catch (error) {
                        console.warn('Koordinat sekolah tidak valid:', error);
                    }
                });

                klaster.addLayers(markers);

                jumlah.textContent = markers.length + ' sekolah';

                if (markers.length > 0) {
                    const bounds = L.latLngBounds(
                        markers.map(marker => marker.getLatLng())
                    );

                    if (bounds.isValid()) {
                        map.fitBounds(bounds.pad(0.1));
                    }
                }

            } catch (error) {
                if (requestId !== requestTerakhir) return;

                console.error('Gagal memuat sekolah:', error);
                jumlah.textContent = 'Gagal memuat data';
            }
        }

        function terapkanFilter() {
            muat(filterJenjang.value, filterStatus.value);
        }

        filterJenjang.addEventListener('change', terapkanFilter);
        filterStatus.addEventListener('change', terapkanFilter);

        terapkanFilter();
    </script>
@endpush
