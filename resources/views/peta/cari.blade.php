@extends('layouts.public')
@section('title', 'Pencarian')

@push('styles')
    <style>
        #hasil {
            list-style: none;
            margin: 8px 0 0;
            padding: 0;
        }

        #hasil li {
            padding: 8px 4px;
            border-top: 1px solid #e5e7eb;
            cursor: pointer;
        }

        #hasil li:hover {
            background: #eff6ff;
        }
    </style>
@endpush

@section('content')
    <div class="toolbar">
        <input type="search" id="kata" size="32" placeholder="Cari nama, NPSN, atau kecamatan">
        <select id="jenjang">
            <option value="">Semua jenjang</option>
            <option value="SD">SD</option>
            <option value="SMP">SMP</option>
            <option value="SMA">SMA</option>
            <option value="SMK">SMK</option>
        </select>
        <button id="hapus" type="button">Hapus</button>
        <span id="jumlah"></span>
    </div>

    <div id="peta" class="peta"></div>

    <div class="panel" style="top: 64px; left: 60px; width: 280px; max-height: 55%; overflow: auto;">
        <strong>Hasil pencarian</strong>
        <ul id="hasil"></ul>
    </div>
@endsection


@push('scripts')
    <script>
        const map = Peta.buat('peta');
        const pusatJaro = [-1.8505, 115.6350];
        map.setView(pusatJaro, 12);
        map.eachLayer(function(layer) {
            if (layer instanceof L.TileLayer) {
                map.removeLayer(layer);
            }
        });
        const baseLayers = {
            'Jalanan (OpenStreetMap)': L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19
            }),
            'Satelit (Esri)': L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    attribution: '&copy; Esri',
                    maxZoom: 19
                }),
            'Satelit + Nama Lokasi': L.layerGroup([
                L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: '&copy; Esri',
                        maxZoom: 19
                    }),
                L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                        attribution: '&copy; Esri',
                        maxZoom: 19
                    })
            ]),
            'Topografi (Esri)': L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
                    attribution: '&copy; Esri',
                    maxZoom: 19
                })
        };
        baseLayers['Jalanan (OpenStreetMap)'].addTo(map);
        const lapisan = L.layerGroup().addTo(map);

        L.control.layers(baseLayers, null, {
            collapsed: true,
            position: 'topright',
            autoZIndex: true
        }).addTo(map);

        let semua = [];
        let tunda;

        function tampil(daftar, sesuaikanPeta = false) {
            lapisan.clearLayers();
            const ul = document.getElementById('hasil');
            ul.innerHTML = '';
            daftar.forEach(function(f) {
                try {
                    const koordinat = L.latLng(Peta.latlng(f));
                    if (!Number.isFinite(koordinat.lat) || !Number.isFinite(koordinat.lng)) {
                        return;
                    }
                    const marker = L.marker(koordinat).bindPopup(Peta.popup(f)).addTo(lapisan);
                    const li = document.createElement('li');
                    li.textContent = `${f.properties.nama} (${f.properties.kecamatan})`;
                    li.addEventListener('click', function() {
                        if (!map.hasLayer(lapisan)) {
                            map.addLayer(lapisan);
                        }
                        map.flyTo(koordinat, 17);
                        marker.openPopup();
                    });
                    ul.appendChild(li);
                } catch (error) {
                    console.warn('Data sekolah tidak valid:', error);
                }
            });
            const jumlahTampil = ul.children.length;
            document.getElementById('jumlah').textContent = `${jumlahTampil} dari ${semua.length} sekolah`;
            if (sesuaikanPeta && lapisan.getLayers().length > 0) {
                const bounds = L.featureGroup(lapisan.getLayers()).getBounds();
                if (bounds.isValid()) {
                    map.fitBounds(bounds.pad(0.1), {
                        maxZoom: 15
                    });
                }
            }
        }

        function cari() {
            const kata = document.getElementById('kata').value.trim().toLowerCase();
            const jenjang = document.getElementById('jenjang').value;
            const hasil = semua.filter(function(f) {
                const p = f.properties || {};
                return (!jenjang || p.jenjang === jenjang) && (!kata || [p.nama, p.npsn, p.kecamatan].some(function(
                    t) {
                    return String(t ?? '').toLowerCase().includes(kata);
                }));
            });
            tampil(hasil, kata !== '' || jenjang !== '');
        }
        document.getElementById('kata').addEventListener('input', function() {
            clearTimeout(tunda);
            tunda = setTimeout(cari, 300);
        });
        document.getElementById('jenjang').addEventListener('change', cari);
        document.getElementById('hapus').addEventListener('click', function() {
            document.getElementById('kata').value = '';
            document.getElementById('jenjang').value = '';
            tampil(semua);
            map.setView(pusatJaro, 12);
        });
        Peta.ambilSekolah().then(function(fitur) {
            semua = Array.isArray(fitur) ? fitur : [];
            tampil(semua);
        }).catch(function(error) {
            console.error('Gagal memuat data sekolah:', error);
            document.getElementById('jumlah').textContent = 'Gagal memuat data sekolah';
        });
    </script>
@endpush
