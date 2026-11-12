@extends('layouts.public')
@section('title', 'Klaster')
 
@push('styles')
<link rel="stylesheet"
      href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css">
<link rel="stylesheet"
      href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css">
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
    const map = Peta.buat('peta');
 
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