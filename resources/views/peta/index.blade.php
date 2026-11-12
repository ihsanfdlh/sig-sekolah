@extends('layouts.public')
@section('title', 'Peta Sekolah')

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
    </div>

    <div id="peta" class="peta"></div>
@endsection


@push('scripts')
    <script>
        const map = Peta.buat('peta');
        const lapisan = L.layerGroup().addTo(map);

        const filterJenjang = document.getElementById('filter-jenjang');
        const filterStatus = document.getElementById('filter-status');
        const jumlah = document.getElementById('jumlah');

        async function muat(jenjang = '', status = '') {
            try {
                jumlah.textContent = 'Memuat data...';

                const fitur = await Peta.ambilSekolah(jenjang, status);

                lapisan.clearLayers();

                fitur.forEach((f) => {
                    const koordinat = Peta.latlng(f);

                    if (
                        !koordinat ||
                        !L.latLngBounds([koordinat]).isValid()
                    ) {
                        return;
                    }

                    L.marker(koordinat)
                        .bindPopup(Peta.popup(f))
                        .addTo(lapisan);
                });

                jumlah.textContent = fitur.length + ' sekolah';

                const batas = lapisan.getLayers()
                    .map(marker => marker.getLatLng());

                if (batas.length > 0) {
                    const bounds = L.latLngBounds(batas);

                    if (bounds.isValid()) {
                        map.fitBounds(bounds.pad(0.1));
                    }
                }

            } catch (error) {
                console.error('Gagal memuat sekolah:', error);
                jumlah.textContent = 'Gagal memuat data';
            }
        }

        function terapkanFilter() {
            const jenjang = filterJenjang.value;
            const status = filterStatus.value;

            muat(jenjang, status);
        }

        filterJenjang.addEventListener('change', terapkanFilter);

        filterStatus.addEventListener('change', terapkanFilter);

        terapkanFilter();
    </script>
@endpush