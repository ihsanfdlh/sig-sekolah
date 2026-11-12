<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use App\Support\Geometri;
use Illuminate\Http\Request;

class PetaController extends Controller
{
    public function index()
    {
        return view('peta.index');
    }

    public function cari()
    {
        return view('peta.cari');
    }

    public function klaster()
    {
        return view('peta.klaster');
    }

    public function panas()
    {
        return view('peta.panas');
    }

    public function geojson(Request $request)
    {
        $validated = $request->validate([
            'south' => [
                'nullable',
                'required_with:north,west,east',
                'numeric',
                'between:-90,90',
            ],
            'north' => [
                'nullable',
                'required_with:south,west,east',
                'numeric',
                'between:-90,90',
            ],
            'west' => [
                'nullable',
                'required_with:south,north,east',
                'numeric',
                'between:-180,180',
            ],
            'east' => [
                'nullable',
                'required_with:south,north,west',
                'numeric',
                'between:-180,180',
            ],
            'jenjang' => ['nullable', 'string', 'max:20'],
            'status' => ['nullable', 'string', 'max:20'],
        ]);

        $query = Sekolah::with('profil')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->when(
                $request->filled('jenjang'),
                fn ($q) => $q->where('jenjang', $request->jenjang)
            )
            ->when(
                $request->filled('status'),
                fn ($q) => $q->where('status', $request->status)
            );

        if (
            $request->filled('south') &&
            $request->filled('north') &&
            $request->filled('west') &&
            $request->filled('east')
        ) {
            $south = (float) $validated['south'];
            $north = (float) $validated['north'];
            $west = (float) $validated['west'];
            $east = (float) $validated['east'];

            if ($south > $north) {
                return response()->json([
                    'message' => 'Batas latitude tidak valid.',
                ], 422);
            }

            $query->whereBetween('latitude', [$south, $north]);

            if ($west <= $east) {
                $query->whereBetween('longitude', [$west, $east]);
            } else {
                $query->where(function ($q) use ($west, $east) {
                    $q->where('longitude', '>=', $west)
                        ->orWhere('longitude', '<=', $east);
                });
            }
        }

        $features = $query
            ->select([
                'id',
                'npsn',
                'nama',
                'jenjang',
                'status',
                'alamat',
                'kecamatan',
                'latitude',
                'longitude',
            ])
            ->get()
            ->map(fn ($s) => [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [
                        (float) $s->longitude,
                        (float) $s->latitude,
                    ],
                ],
                'properties' => [
                    'id' => $s->id,
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
                    'logo' => $s->profil?->logo
                        ? asset('storage/'.$s->profil->logo)
                        : null,
                    'url_profil' => route('sekolah.publik', $s),
                ],
            ])
            ->values();

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    public function rute()
    {
        return view('peta.rute');
    }

    public function alamat()
    {
        return view('peta.alamat');
    }

    public function wilayah()
    {
        return view('peta.wilayah');
    }

    public function geojsonWilayah(Request $request)
    {
        $features = Sekolah::with('profil')
            ->when($request->jenjang, fn ($q, $j) => $q->where('jenjang', $j))
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->get()
            ->groupBy('kecamatan')
            ->map(function ($kelompok, $kecamatan) {
                $titik = $kelompok
                    ->map(fn ($s) => [$s->longitude, $s->latitude])
                    ->all();

                $hull = Geometri::convexHull($titik);

                // Poligon memerlukan minimal 3 titik yang tidak segaris
                if (count($hull) < 3) {
                    return null;
                }

                $hull[] = $hull[0]; // GeoJSON: cincin poligon harus tertutup

                return [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Polygon',
                        'coordinates' => [$hull],
                    ],
                    'properties' => [
                        'kecamatan' => $kecamatan,
                        'jumlah_sekolah' => $kelompok->count(),
                        'jumlah_siswa' => (int) $kelompok->sum(
                            fn ($s) => $s->profil?->jumlah_siswa ?? 0
                        ),
                    ],
                ];
            })
            ->filter()
            ->values();

        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }
}
