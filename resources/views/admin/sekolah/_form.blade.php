@php
  $teks = [
    'npsn' => 'NPSN (8 digit)',
    'nama' => 'Nama Sekolah',
    'kecamatan' => 'Kecamatan',
    'telepon' => 'Telepon',
  ];
  $pilihan = [
    'jenjang' => ['SD', 'SMP', 'MTS', 'SMA', 'SMK'],
    'status' => ['Negeri', 'Swasta'],
  ];
  $cls = 'form-control';
@endphp

<div class="form-card">
  <div class="form-columns">
    <div class="form-section">
    @foreach ($teks as $name => $label)
      <div class="form-field">
        <label for="{{ $name }}" class="block font-medium">
          {{ $label }}
        </label>
        <input type="text" id="{{ $name }}" name="{{ $name }}"
          value="{{ old($name, $sekolah->$name ?? '') }}"
          class="{{ $cls }}">
        @error($name)
          <p class="field-error">{{ $message }}</p>
        @enderror
      </div>
    @endforeach

    @foreach ($pilihan as $name => $opsi)
      <div class="form-field">
        <label for="{{ $name }}" class="block font-medium">
          {{ ucfirst($name) }}
        </label>
        <select id="{{ $name }}" name="{{ $name }}" class="{{ $cls }}">
          <option value="">-- Pilih --</option>
          @foreach ($opsi as $o)
            <option value="{{ $o }}" @selected(old($name, $sekolah->$name ?? '') === $o)>
              {{ $o }}
            </option>
          @endforeach
        </select>
        @error($name)
          <p class="field-error">{{ $message }}</p>
        @enderror
      </div>
    @endforeach

    <div class="form-field">
      <label for="alamat" class="block font-medium">Alamat</label>
      <textarea id="alamat" name="alamat" rows="3" class="{{ $cls }}">{{ old('alamat', $sekolah->alamat ?? '') }}</textarea>
      @error('alamat')
        <p class="field-error">{{ $message }}</p>
      @enderror
    </div>
  </div>
  <div class="form-section">
    <div class="location-heading">
      <p>Klik peta, geser marker, atau gunakan lokasi saat ini.</p>
      <button type="button" id="btn-lokasi-saya" class="button location-button">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
      </svg>
        Gunakan Lokasi Saya
      </button>
    </div>

    <div id="map-picker" class="map-picker"></div>

    <div class="coordinate-fields">
      <div class="form-field">
        <label for="latitude">Latitude</label>
        <input type="number" step="any" id="latitude" name="latitude"
          value="{{ old('latitude', $sekolah->latitude ?? '') }}"
          class="{{ $cls }}">
        @error('latitude')
          <p class="field-error">{{ $message }}</p>
        @enderror
      </div>
      <div class="form-field">
        <label for="longitude">Longitude</label>
        <input type="number" step="any" id="longitude" name="longitude"
          value="{{ old('longitude', $sekolah->longitude ?? '') }}"
          class="{{ $cls }}">
        @error('longitude')
          <p class="field-error">{{ $message }}</p>
        @enderror
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="button">
        Simpan
      </button>
      <a href="{{ route('admin.sekolah.index') }}" class="button button-secondary">
        Batal
      </a>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const latInput = document.getElementById('latitude');
    const lngInput = document.getElementById('longitude');

    const sudahAda = latInput.value !== '' && lngInput.value !== '';
    const pusat = sudahAda 
      ? [parseFloat(latInput.value), parseFloat(lngInput.value)]
      : [-1.8374614675638135, 115.63343545771596];

    const map = L.map('map-picker').setView(pusat, sudahAda ? 16 : 13);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 19,
      attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);

    const marker = L.marker(pusat, { draggable: true }).addTo(map);

    function isiKoordinat(lat, lng) {
      latInput.value = lat.toFixed(7);
      lngInput.value = lng.toFixed(7);
    }

    if (!sudahAda) {
      isiKoordinat(pusat[0], pusat[1]);
    }

    // --- FITUR GEOLOCATION (LOKASI SAYA) ---
    const btnLokasi = document.getElementById('btn-lokasi-saya');

    btnLokasi.addEventListener('click', () => {
      if (!navigator.geolocation) {
        alert('Browser Anda tidak mendukung fitur Geolocation.');
        return;
      }

      // Ubah status tombol saat sedang mencari lokasi
      const teksAwal = btnLokasi.innerHTML;
      btnLokasi.disabled = true;
      btnLokasi.innerHTML = `
        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Mencari Lokasi...
      `;

      navigator.geolocation.getCurrentPosition(
        (pos) => {
          const lat = pos.coords.latitude;
          const lng = pos.coords.longitude;

          // Pindahkan marker dan fokus peta ke lokasi GPS pengguna
          marker.setLatLng([lat, lng]);
          map.flyTo([lat, lng], 17, { animate: true, duration: 1.5 });
          
          // Isikan hasil ke input form
          isiKoordinat(lat, lng);

          // Kembalikan tombol ke keadaan semula
          btnLokasi.disabled = false;
          btnLokasi.innerHTML = teksAwal;
        },
        (error) => {
          btnLokasi.disabled = false;
          btnLokasi.innerHTML = teksAwal;

          switch (error.code) {
            case error.PERMISSION_DENIED:
              alert('Akses lokasi ditolak oleh pengguna. Mohon izinkan akses lokasi di browser Anda.');
              break;
            case error.POSITION_UNAVAILABLE:
              alert('Informasi lokasi tidak tersedia.');
              break;
            case error.TIMEOUT:
              alert('Waktu permintaan lokasi habis.');
              break;
            default:
              alert('Gagal mengambil lokasi.');
              break;
          }
        },
        {
          enableHighAccuracy: true, // Menggunakan GPS akurasi tinggi jika tersedia
          timeout: 10000,
          maximumAge: 0
        }
      );
    });

    // Event saat peta diklik
    map.on('click', (e) => {
      marker.setLatLng(e.latlng);
      isiKoordinat(e.latlng.lat, e.latlng.lng);
    });

    // Event saat marker digeser
    marker.on('dragend', () => {
      const p = marker.getLatLng();
      isiKoordinat(p.lat, p.lng);
    });

    // Event saat angka diketik manual pada field input
    [latInput, lngInput].forEach((el) => {
      el.addEventListener('input', () => {
        const lat = parseFloat(latInput.value);
        const lng = parseFloat(lngInput.value);
        if (!isNaN(lat) && !isNaN(lng)) {
          marker.setLatLng([lat, lng]);
          map.panTo([lat, lng]);
        }
      });
    });
  });
</script>
@endpush