const Peta = {
    // Pusat Kota Banjarmasin, urutan [latitude, longitude]
    pusat: [-3.3194, 114.5908],
 
    buat(idElemen, zoom = 12) {
        const map = L.map(idElemen).setView(Peta.pusat, zoom);
 
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        }).addTo(map);
 
        return map;
    },
 
    // Mencegah teks dari database dibaca sebagai HTML (XSS)
    esc(teks) {
        const d = document.createElement('div');
        d.textContent = teks ?? '';
        return d.innerHTML;
    },
 
    // Mengambil seluruh fitur (Feature) sekolah dari endpoint GeoJSON
    async ambilSekolah(jenjang = '', status = '') {
        const params = new URLSearchParams({ jenjang });

        if (status) {
            params.set('status', status);
        }

        const url = `${window.PETA_URL.data}?${params}`;
        const respon = await fetch(url);
 
        if (!respon.ok) {
            throw new Error('Gagal memuat data sekolah');
        }
 
        const data = await respon.json();
        return data.features;
    },
 
    // GeoJSON [lng, lat] menjadi Leaflet [lat, lng]
    latlng(fitur) {
        const [lng, lat] = fitur.geometry.coordinates;
        return [lat, lng];
    },
 
    popup(fitur) {
        const p = fitur.properties;
        const [lat, lng] = Peta.latlng(fitur);
        const logo = Peta.esc(p.logo || window.PETA_URL.logoFallback);
        const profil = p.url_profil
            ? `<a class="school-popup-link" href="${Peta.esc(p.url_profil)}">Lihat profil sekolah</a>`
            : '';
        const formatAngka = (nilai) => {
            if (nilai === null || nilai === undefined || nilai === '') {
                return '-';
            }

            const angka = Number(nilai);
            return Number.isFinite(angka)
                ? angka.toLocaleString('id-ID')
                : '-';
        };
 
        return `
            <div class="school-popup">
                <div class="school-popup-heading">
                    <img
                        src="${logo}"
                        alt="Logo ${Peta.esc(p.nama || 'Sekolah')}"
                        class="school-popup-logo"
                        onerror="this.style.display='none';this.nextElementSibling.hidden=false"
                    >
                    <div class="school-popup-logo-fallback" hidden aria-hidden="true">🏫</div>
                    <div class="school-popup-title">
                        <strong>${Peta.esc(p.nama || 'Tanpa Nama')}</strong>
                        <div class="school-popup-badges">
                            <span class="school-popup-badge">${Peta.esc(p.jenjang || '-')}</span>
                            <span class="school-popup-badge school-popup-badge-status">${Peta.esc(p.status || '-')}</span>
                        </div>
                    </div>
                </div>
                <div class="school-popup-details">
                    <div><strong>NPSN:</strong> ${Peta.esc(p.npsn || '-')}</div>
                    <div><strong>Alamat:</strong> ${Peta.esc(p.alamat || '-')}</div>
                    <div><strong>Kecamatan:</strong> ${Peta.esc(p.kecamatan || '-')}</div>
                    <div><strong>Telepon:</strong> ${Peta.esc(p.telepon || '-')}</div>
                    <div><strong>Kepala sekolah:</strong> ${Peta.esc(p.kepala_sekolah || '-')}</div>
                    <div><strong>Akreditasi:</strong> ${Peta.esc(p.akreditasi || '-')}</div>
                    <div><strong>Siswa:</strong> ${formatAngka(p.jumlah_siswa)} &nbsp; <strong>Guru:</strong> ${formatAngka(p.jumlah_guru)}</div>
                    <small>Koordinat: ${lat}, ${lng}</small>
                </div>
                ${profil}
            </div>
        `;
    },
 
    // Jarak garis lurus (km) antara dua titik [lat, lng], rumus Haversine
    jarakKm(a, b) {
        const R = 6371;
        const rad = (x) => (x * Math.PI) / 180;
        const dLat = rad(b[0] - a[0]);
        const dLng = rad(b[1] - a[1]);
        const h = Math.sin(dLat / 2) ** 2 +
            Math.cos(rad(a[0])) * Math.cos(rad(b[0])) * Math.sin(dLng / 2) ** 2;
 
        return 2 * R * Math.asin(Math.sqrt(h));
    },
 
    // Penanda bulat berisi angka (dipakai pada menu Rute)
    ikonAngka(angka, warna) {
        return L.divIcon({
            className: '',
            iconSize: [30, 30],
            iconAnchor: [15, 15],
            html: `<div style="background:${warna};width:30px;height:30px;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 4px rgba(0,0,0,.4);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:bold;font-size:13px">${angka}</div>`,
        });
    },
};
