@extends('layouts.public')
@section('title', 'Wilayah')

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
        <span id="jumlah"></span>
    </div>

    <div id="peta" class="peta"></div>

    <div class="panel" style="right: 12px; bottom: 24px; font-size: 13px;">
        <strong>Jumlah sekolah</strong>
        <div><span style="color: #dc2626;">&#9632;</span> Terbanyak</div>
        <div><span style="color: #ea580c;">&#9632;</span> Banyak</div>
        <div><span style="color: #f59e0b;">&#9632;</span> Sedang</div>
        <div><span style="color: #3b82f6;">&#9632;</span> Sedikit</div>
        <small style="color: #6b7280;">Relatif terhadap kecamatan terbanyak</small>
    </div>
@endsection

@push('scripts')
    <script>
        const map = Peta.buat('peta');
        let lapisan = null;

        function warna(rasio) {
            if (rasio >= 0.8) return '#dc2626';
            if (rasio >= 0.6) return '#ea580c';
            if (rasio >= 0.4) return '#f59e0b';
            return '#3b82f6';
        }

        function normalisasi(nama) {
            return String(nama || '')
                .toUpperCase()
                .replace(/^KECAMATAN\s+/, '')
                .replace(/\s+/g, ' ')
                .trim();
        }

        function namaKecamatan(properties) {
            return properties.WADMKC ||
                properties.NAMOBJ ||
                properties.kecamatan ||
                properties.KECAMATAN ||
                '';
        }


        async function muat(jenjang = '') {
            try {
                // 1. Mengambil batas kecamatan resmi
                const respon = await fetch('/geojson/batas_kecamatan.geojson');

                if (!respon.ok) {
                    throw new Error('Gagal memuat GeoJSON: HTTP ' + respon.status);
                }

                const data = await respon.json();

                // 2. Mengambil data sekolah sesuai filter jenjang
                const sekolah = await Peta.ambilSekolah(jenjang);
                console.log('Data sekolah:', sekolah);
                console.log('Jumlah sekolah:', sekolah.length);
                console.log('Contoh properti:', sekolah[0]?.properties);

                // 3. Menghitung jumlah sekolah per kecamatan
                const statistik = {};

                sekolah.forEach((f) => {
                    const p = f.properties || {};
                    const nama = normalisasi(p.kecamatan);

                    if (!nama) return;

                    if (!statistik[nama]) {
                        statistik[nama] = {
                            jumlah_sekolah: 0,
                            jumlah_siswa: 0
                        };
                    }

                    statistik[nama].jumlah_sekolah++;
                    statistik[nama].jumlah_siswa += Number(p.jumlah_siswa) || 0;
                });

                // 4. Menggabungkan statistik dengan GeoJSON resmi
                data.features.forEach((f) => {
                    const namaAsli = namaKecamatan(f.properties || {});
                    const nama = normalisasi(namaAsli);

                    const hasil = statistik[nama] || {
                        jumlah_sekolah: 0,
                        jumlah_siswa: 0
                    };

                    f.properties = {
                        ...f.properties,
                        kecamatan: namaAsli,
                        jumlah_sekolah: hasil.jumlah_sekolah,
                        jumlah_siswa: hasil.jumlah_siswa
                    };
                });

                // 5. Menghapus layer wilayah sebelumnya
                if (lapisan) {
                    map.removeLayer(lapisan);
                }

                // 6. Mencari jumlah sekolah terbanyak
                const maks = Math.max(
                    1,
                    ...data.features.map(
                        (f) => f.properties.jumlah_sekolah
                    )
                );

                // 7. Menampilkan poligon resmi
                lapisan = L.geoJSON(data, {
                    style: (f) => {
                        const rasio = f.properties.jumlah_sekolah / maks;
                        const w = warna(rasio);

                        return {
                            color: w,
                            fillColor: w,
                            fillOpacity: 0.3,
                            weight: 3
                        };
                    },

                    onEachFeature: (f, layer) => {
                        const p = f.properties;

                        layer.bindTooltip(
                            Peta.esc(p.kecamatan), {
                                sticky: true
                            }
                        );

                        layer.bindPopup(`
                    <strong>${Peta.esc(p.kecamatan)}</strong><br>
                    Jumlah sekolah: ${p.jumlah_sekolah}<br>
                    Jumlah siswa: ${p.jumlah_siswa}
                `);
                    }
                }).addTo(map);

                // 8. Menampilkan jumlah wilayah
                document.getElementById('jumlah').textContent =
                    data.features.length + ' wilayah';

                // 9. Menyesuaikan posisi peta
                if (lapisan.getLayers().length > 0) {
                    map.fitBounds(lapisan.getBounds().pad(0.1));
                }

            } catch (error) {
                console.error('Gagal memuat wilayah:', error);

                document.getElementById('jumlah').textContent =
                    'Gagal memuat data wilayah';
            }
        }


        document.getElementById('filter-jenjang')
            .addEventListener('change', (e) => muat(e.target.value));

        muat();
    </script>
@endpush
