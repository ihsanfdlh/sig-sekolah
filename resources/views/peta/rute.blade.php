@extends('layouts.public')
@section('title', 'Rute')
@section('page-style-class', 'route-page')


@section('content')
<div id="public-map-frame" class="public-map-frame is-loading" aria-busy="true">
    <div id="peta" class="peta"></div>
    <div id="peta-loading" class="public-map-loading" role="status" aria-live="polite">
        <span class="public-map-loading-spinner" aria-hidden="true"></span>
        <span>Memuat data sekolah...</span>
    </div>
</div>

<button id="buka-panel-rute" class="route-panel-trigger" type="button"
        aria-controls="panel-rute" aria-expanded="true" hidden>
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
        <path d="M4 6h16M4 12h16M4 18h16"/>
    </svg>
    <span>Rute kunjungan</span>
</button>
<div id="panel-rute" class="panel route-panel" aria-hidden="false">
    <div class="route-panel-header">
        <strong>Rute kunjungan sekolah</strong>
        <button id="tutup-panel-rute" class="route-panel-close" type="button"
                aria-label="Tutup panel rute" title="Tutup panel rute">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true">
                <path d="M18 6 6 18M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <p class="route-description">
        Pilih 2 sampai 10 sekolah. Urutan rute mengikuti urutan Anda mencentang.
    </p>
    <input type="search" id="kata" placeholder="Cari sekolah"
           class="route-search">
    <ul id="daftar" class="daftar"></ul>
    <div class="route-actions">
        <button id="buat" class="btn" type="button">Buat Rute</button>
        <button id="reset" class="btn btn-abu" type="button">Reset</button>
    </div>
    <p id="urutan" class="route-order"></p>
</div>

<div id="info" class="panel route-info" hidden></div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet-polylinedecorator@1.6.0/dist/leaflet.polylineDecorator.js"></script>
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

L.control.layers(baseLayers, null, {
    collapsed: true,
    position: 'topright',
    autoZIndex: true
}).addTo(map);

    const MAKS = 10;
    const lapisanRute = L.layerGroup().addTo(map);
    const semua = new Map();   // npsn => fitur
    let terpilih = [];         // urutan npsn yang dicentang
    const panelRute = document.getElementById('panel-rute');
    const tombolBukaPanelRute = document.getElementById('buka-panel-rute');
    const tombolTutupPanelRute = document.getElementById('tutup-panel-rute');

    function aturPanelRute(terbuka) {
        panelRute.hidden = !terbuka;
        panelRute.setAttribute('aria-hidden', String(!terbuka));
        tombolBukaPanelRute.hidden = terbuka;
        tombolBukaPanelRute.setAttribute('aria-expanded', String(terbuka));
        (terbuka ? tombolTutupPanelRute : tombolBukaPanelRute).focus();
    }

    tombolBukaPanelRute.addEventListener('click', () => aturPanelRute(true));
    tombolTutupPanelRute.addEventListener('click', () => aturPanelRute(false));

    function gambarDaftar() {
        const kata = document.getElementById('kata').value.trim().toLowerCase();
        const ul = document.getElementById('daftar');
        ul.innerHTML = '';

        [...semua.values()]
            .filter((f) => f.properties.nama.toLowerCase().includes(kata))
            .forEach((f) => {
                const npsn = f.properties.npsn;
                const cek = document.createElement('input');
                cek.type = 'checkbox';
                cek.checked = terpilih.includes(npsn);
                cek.addEventListener('change', () => ubahPilihan(npsn, cek));

                const label = document.createElement('label');
                label.append(cek, ` ${f.properties.nama}`);

                const li = document.createElement('li');
                li.appendChild(label);
                ul.appendChild(li);
            });
    }

    function ubahPilihan(npsn, cek) {
        if (cek.checked) {
            if (terpilih.length >= MAKS) {
                cek.checked = false;
                alert(`Maksimal ${MAKS} sekolah.`);
                return;
            }
            terpilih.push(npsn);
        } else {
            terpilih = terpilih.filter((n) => n !== npsn);
        }
        tampilUrutan();
    }

    function tampilUrutan() {
        document.getElementById('urutan').textContent = terpilih
            .map((n, i) => `${i + 1}. ${semua.get(n).properties.nama}`)
            .join(' \u2192 ');
    }

    // titik: [[lat, lng], ...]
    async function ambilRute(titik) {
        const koordinat = titik.map(([lat, lng]) => `${lng},${lat}`).join(';');

        try {
            const respon = await fetch(
                `https://router.project-osrm.org/route/v1/driving/${koordinat}?overview=full&geometries=geojson`
            );
            if (!respon.ok) throw new Error('HTTP ' + respon.status);

            const rute = (await respon.json()).routes?.[0];
            if (!rute) throw new Error('Rute tidak ditemukan');

            return {
                sumber: 'OSRM',
                garis: rute.geometry.coordinates.map(([lng, lat]) => [lat, lng]),
                jarak: rute.distance / 1000,
                durasi: rute.duration / 60,
                legs: rute.legs.map((l) => ({
                    jarak: l.distance / 1000,
                    durasi: l.duration / 60,
                })),
            };
        } catch (e) {
            console.error(e);

            // Cadangan: garis lurus dan jarak Haversine
            const legs = titik.slice(1).map((t, i) => ({
                jarak: Peta.jarakKm(titik[i], t),
                durasi: null,
            }));

            return {
                sumber: 'Garis lurus',
                garis: titik,
                jarak: legs.reduce((a, l) => a + l.jarak, 0),
                durasi: null,
                legs,
            };
        }
    }

    async function buatRute() {
        if (terpilih.length < 2) {
            alert('Pilih minimal 2 sekolah.');
            return;
        }

        const fitur = terpilih.map((n) => semua.get(n));
        const titik = fitur.map(Peta.latlng);

        const tombol = document.getElementById('buat');
        tombol.disabled = true;
        window.aturLoadingPetaPublik(true);
        try {
        const rute = await ambilRute(titik);

        lapisanRute.clearLayers();

        const garis = L.polyline(rute.garis, {
            color: '#3b82f6', weight: 4, opacity: 0.8,
        }).addTo(lapisanRute);

        L.polylineDecorator(garis, {
            patterns: [{
                offset: '5%',
                repeat: 100,
                symbol: L.Symbol.arrowHead({
                    pixelSize: 12,
                    polygon: false,
                    pathOptions: { stroke: true, weight: 2, color: '#1e40af' },
                }),
            }],
        }).addTo(lapisanRute);

        fitur.forEach((f, i) => {
            const warna = i === 0 ? '#16a34a'
                : i === fitur.length - 1 ? '#dc2626' : '#2563eb';
            const leg = i > 0 ? rute.legs[i - 1] : null;
            const tambahan = leg
                ? `<br>Dari titik sebelumnya: ${leg.jarak.toFixed(2)} km`
                    + (leg.durasi ? ` (${Math.round(leg.durasi)} menit)` : '')
                : '';

            L.marker(titik[i], { icon: Peta.ikonAngka(i + 1, warna) })
                .bindPopup(`
                    <strong>${i + 1}. ${Peta.esc(f.properties.nama)}</strong><br>
                    ${Peta.esc(f.properties.alamat)}${tambahan}
                `)
                .addTo(lapisanRute);
        });

        const info = document.getElementById('info');
        info.hidden = false;
        info.innerHTML = `
            <strong>Informasi rute</strong><br>
            Jumlah titik: ${fitur.length}<br>
            Total jarak: ${rute.jarak.toFixed(2)} km<br>
            ${rute.durasi ? `Perkiraan waktu: ${Math.round(rute.durasi)} menit<br>` : ''}
            <small>Sumber: ${rute.sumber === 'OSRM'
                ? 'mengikuti jalan (OSRM)'
                : 'garis lurus (layanan rute tidak tersedia)'}</small>
        `;

        map.fitBounds(garis.getBounds().pad(0.1));
        } finally {
            tombol.disabled = false;
            window.aturLoadingPetaPublik(false);
        }
    }

    function reset() {
        terpilih = [];
        lapisanRute.clearLayers();
        document.getElementById('info').hidden = true;
        tampilUrutan();
        gambarDaftar();
    }

    document.getElementById('kata').addEventListener('input', gambarDaftar);
    document.getElementById('buat').addEventListener('click', buatRute);
    document.getElementById('reset').addEventListener('click', reset);

    Peta.ambilSekolah().then((fitur) => {
        fitur.forEach((f) => {
            semua.set(f.properties.npsn, f);

            // Titik abu-abu sebagai konteks letak seluruh sekolah
            L.circleMarker(Peta.latlng(f), {
                radius: 5, color: '#6b7280', fillOpacity: 0.6,
            }).bindTooltip(Peta.esc(f.properties.nama)).addTo(map);
        });

        gambarDaftar();
        map.fitBounds(L.latLngBounds(fitur.map(Peta.latlng)).pad(0.1));
    }).catch((error) => {
        console.error('Gagal memuat data sekolah:', error);
        const pesan = document.createElement('li');
        pesan.textContent = 'Gagal memuat data sekolah.';
        document.getElementById('daftar').appendChild(pesan);
    }).finally(() => {
        window.aturLoadingPetaPublik(false);
    });
</script>
@endpush
