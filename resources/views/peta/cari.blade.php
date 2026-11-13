
@extends('layouts.public')
@section('title', 'Pencarian')
@section('page-style-class', 'search-page')
@section('content')
<div class="toolbar">
    <input type="search" id="kata" size="32" placeholder="Cari nama, NPSN, atau kecamatan">
    <select id="jenjang">
        <option value="">Semua jenjang</option>
        <option value="SD">SD</option>
        <option value="SMP">SMP</option>
        <option value="MTS">MTS</option>
        <option value="SMA">SMA</option>
        <option value="SMK">SMK</option>
    </select>
    <button id="hapus" type="button">Hapus</button>
    <button id="buka-hasil" type="button" aria-controls="panel-hasil" aria-expanded="false" hidden>
        <i class="bi bi-list-ul"></i>
        Hasil Pencarian
    </button>
    <span id="jumlah" aria-live="polite"></span>
</div>
<div id="peta" class="peta"></div>
<div id="panel-hasil" class="panel">
    <div class="panel-hasil-header">
        <strong>Hasil Pencarian</strong>
        <button type="button" id="tutup-hasil" title="Tutup hasil pencarian" aria-label="Tutup hasil pencarian">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <ul id="hasil"></ul>
</div>
@endsection
@push('scripts')
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
const baseLayers = {
    'Jalanan (OpenStreetMap)': L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }),
    'Satelit (Esri)': L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: '&copy; Esri',
        maxZoom: 19
    }),
    'Satelit + Nama Lokasi': L.layerGroup([
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; Esri',
            maxZoom: 19
        }),
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; Esri',
            maxZoom: 19
        })
    ]),
    'Topografi (Esri)': L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
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
const inputKata = document.getElementById('kata');
const filterJenjang = document.getElementById('jenjang');
const tombolHapus = document.getElementById('hapus');
const jumlah = document.getElementById('jumlah');
const panelHasil = document.getElementById('panel-hasil');
const tombolTutup = document.getElementById('tutup-hasil');
const tombolBuka = document.getElementById('buka-hasil');
const daftarHasil = document.getElementById('hasil');
let semua = [];
let tunda = null;
function aturPanelHasil(terbuka) {
    panelHasil.hidden = !terbuka;
    tombolBuka.hidden = terbuka;
    tombolBuka.setAttribute('aria-expanded', String(terbuka));
}
tombolTutup.addEventListener('click', function() {
    aturPanelHasil(false);
});
tombolBuka.addEventListener('click', function() {
    aturPanelHasil(true);
});
function tampil(daftar, sesuaikanPeta = false) {
    lapisan.clearLayers();
    daftarHasil.innerHTML = '';
    daftar.forEach(function(f) {
        try {
            const koordinat = L.latLng(Peta.latlng(f));
            if (!Number.isFinite(koordinat.lat) || !Number.isFinite(koordinat.lng) || Math.abs(koordinat.lat) > 90 || Math.abs(koordinat.lng) > 180) {
                return;
            }
            const marker = L.marker(koordinat).bindPopup(Peta.popup(f)).addTo(lapisan);
            const properti = f.properties || {};
            const li = document.createElement('li');
            li.textContent = `${properti.nama || 'Sekolah tanpa nama'} (${properti.kecamatan || '-'})`;
            li.addEventListener('click', function() {
                if (!map.hasLayer(lapisan)) {
                    map.addLayer(lapisan);
                }
                map.flyTo(koordinat, 17);
                marker.openPopup();
            });
            daftarHasil.appendChild(li);
        } catch (error) {
            console.warn('Data sekolah tidak valid:', error);
        }
    });
    const jumlahTampil = daftarHasil.children.length;
    jumlah.textContent = `${jumlahTampil} dari ${semua.length} sekolah`;
    if (jumlahTampil === 0) {
        const li = document.createElement('li');
        li.textContent = 'Tidak ada sekolah yang ditemukan.';
        li.className = 'empty-result';
        daftarHasil.appendChild(li);
    }
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
    const kata = inputKata.value.trim().toLowerCase();
    const jenjang = filterJenjang.value;
    const hasil = semua.filter(function(f) {
        const p = f.properties || {};
        const cocokJenjang = !jenjang || p.jenjang === jenjang;
        const cocokKata = !kata || [p.nama, p.npsn, p.kecamatan].some(function(t) {
            return String(t ?? '').toLowerCase().includes(kata);
        });
        return cocokJenjang && cocokKata;
    });
    tampil(hasil, kata !== '' || jenjang !== '');
}
inputKata.addEventListener('input', function() {
    clearTimeout(tunda);
    tunda = setTimeout(cari, 300);
});
filterJenjang.addEventListener('change', cari);
tombolHapus.addEventListener('click', function() {
    clearTimeout(tunda);
    inputKata.value = '';
    filterJenjang.value = '';
    tampil(semua);
    map.setView(pusatJaro, zoomAwal);
});
Peta.ambilSekolah().then(function(fitur) {
    semua = Array.isArray(fitur) ? fitur : [];
    cari();
}).catch(function(error) {
    console.error('Gagal memuat data sekolah:', error);
    jumlah.textContent = 'Gagal memuat data sekolah';
    daftarHasil.innerHTML = '';
    const li = document.createElement('li');
    li.textContent = 'Data sekolah tidak dapat dimuat.';
    daftarHasil.appendChild(li);
});
</script>
@endpush
