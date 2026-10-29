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
  $cls = 'w-full border rounded px-3 py-2';
@endphp

<div class="bg-white rounded shadow p-6 grid md:grid-cols-2 gap-6">
  <!-- Kolom Kiri: Form Input Teks & Pilihan -->
  <div class="space-y-4">
    @foreach ($teks as $name => $label)
      <div>
        <label for="{{ $name }}" class="block font-medium">
          {{ $label }}
        </label>
        <input type="text" id="{{ $name }}" name="{{ $name }}"
          value="{{ old($name, $sekolah->$name ?? '') }}"
          class="{{ $cls }}">
        @error($name)
          <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
      </div>
    @endforeach

    @foreach ($pilihan as $name => $opsi)
      <div>
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
          <p class="text-red-600 text-sm">{{ $message }}</p>
        @enderror
      </div>
    @endforeach

    <div>
      <label for="alamat" class="block font-medium">Alamat</label>
      <textarea id="alamat" name="alamat" rows="3" class="{{ $cls }}">{{ old('alamat', $sekolah->alamat ?? '') }}</textarea>
      @error('alamat')
        <p class="text-red-600 text-sm">{{ $message }}</p>
      @enderror
    </div>
  </div>
  <div class="space-y-4">
  <div class="flex justify-between items-center">
    <p class="font-medium text-sm">
      Klik peta, geser marker, atau gunakan lokasi saat ini
    </p>
    <!-- Tombol Gunakan Lokasi Saya -->
    <button 
      type="button" 
      id="btn-lokasi-saya" 
      class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded shadow-sm flex items-center gap-1.5 transition-colors"
    >
      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
      </svg>
      Gunakan Lokasi Saya
    </button>
  </div>

  <div id="map-picker" class="h-80 rounded border"></div>

  <div class="grid grid-cols-2 gap-4">
    <div>
      <label for="latitude" class="block font-medium">Latitude</label>
      <input type="number" step="any" id="latitude" name="latitude"
        value="{{ old('latitude', $sekolah->latitude ?? '') }}"
        class="{{ $cls }}">
      @error('latitude')
        <p class="text-red-600 text-sm">{{ $message }}</p>
      @enderror
    </div>
    <div>
      <label for="longitude" class="block font-medium">Longitude</label>
      <input type="number" step="any" id="longitude" name="longitude"
        value="{{ old('longitude', $sekolah->longitude ?? '') }}"
        class="{{ $cls }}">
      @error('longitude')
        <p class="text-red-600 text-sm">{{ $message }}</p>
      @enderror
    </div>
  </div>
    <div class="flex gap-3 pt-2">
      <button type="submit" class="px-5 py-2 bg-blue-700 text-white rounded hover:bg-blue-800">
        Simpan
      </button>
      <a href="{{ route('admin.sekolah.index') }}" class="px-5 py-2 bg-gray-300 rounded hover:bg-gray-400">
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
      : [-3.3194, 114.5908]; // Pusat default: Banjarmasin

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