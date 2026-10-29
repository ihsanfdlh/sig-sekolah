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

  <!-- Kolom Kanan: Map Picker & Koordinat -->
  <div class="space-y-4">
    <p class="font-medium">
      Klik peta atau geser marker untuk menentukan lokasi sekolah
    </p>
    
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

    // Cek apakah data koordinat sudah ada (misal: mode edit)
    const sudahAda = latInput.value !== '' && lngInput.value !== '';
    
    // Pusat default: Banjarmasin
    const pusat = sudahAda 
      ? [parseFloat(latInput.value), parseFloat(lngInput.value)]
      : [-3.3194, 114.5908];

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

    // Isi koordinat awal jika form tambah baru
    if (!sudahAda) {
      isiKoordinat(pusat[0], pusat[1]);
    }

    // Event saat peta diklik
    map.on('click', (e) => {
      marker.setLatLng(e.latlng);
      isiKoordinat(e.latlng.lat, e.latlng.lng);
    });

    // Event saat marker digeser (drag)
    marker.on('dragend', () => {
      const p = marker.getLatLng();
      isiKoordinat(p.lat, p.lng);
    });

    // Perbarui marker ketika angka di input diubah secara manual
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