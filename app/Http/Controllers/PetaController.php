<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;

class PetaController extends Controller
{
    public function index()
    {
        $kecamatan = Sekolah::select('kecamatan')
            ->whereNotNull('kecamatan')
            ->where('kecamatan', '!=', '')
            ->distinct()
            ->orderBy('kecamatan', 'asc')
            ->get();

        return view('peta', compact('kecamatan'));
    }

    public function geojson(Request $request)
    {
        $features = Sekolah::with('profil')
            ->when($request->jenjang, fn ($q, $j) => $q->where('jenjang', $j))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->kecamatan, fn ($q, $k) => $q->where('kecamatan', $k))
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(fn ($s) => [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        (float) $s->longitude,
                        (float) $s->latitude
                    ],
                ],
                'properties' => [
                    'npsn' => $s->npsn,
                    'nama' => $s->nama,
                    'jenjang' => $s->jenjang,
                    'status' => $s->status,
                    'alamat' => $s->alamat,
                    'kecamatan' => $s->kecamatan,
                    'telepon' => $s->telepon,
                    'kepala_sekolah' => $s->profil?->kepala_sekolah,
                    'akreditasi' => $s->profil?->akreditasi,
                    'jumlah_siswa' => $s->profil?->jumlah_siswa,
                    'jumlah_guru' => $s->profil?->jumlah_guru,
                ],
            ]);

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features->values(),
        ]);
    }
}
