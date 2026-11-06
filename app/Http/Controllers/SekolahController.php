<?php

namespace App\Http\Controllers;

use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class SekolahController extends Controller
{
    public function index(Request $request)
    {
        $sekolahs = Sekolah::query()
            ->when($request->q, function ($query, $q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('nama', 'like', "%{$q}%")
                        ->orWhere('npsn', 'like', "%{$q}%")
                        ->orWhere('kecamatan', 'like', "%{$q}%");
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin.sekolah.index', compact('sekolahs'));
    }

    public function create()
    {
        return view('admin.sekolah.create', [
            'sekolah' => new Sekolah,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'npsn' => ['required', 'digits:8', 'unique:sekolahs,npsn'],
            'nama' => ['required', 'string', 'max:255'],
            'jenjang' => ['required', Rule::in(['SD', 'SMP', 'MTS', 'SMA', 'SMK'])],
            'status' => ['required', Rule::in(['Negeri', 'Swasta'])],
            'alamat' => ['required', 'string'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        Sekolah::create($data);

        return redirect()->route('dinas.sekolah.index')
            ->with('success', 'Data sekolah berhasil ditambahkan.');
    }

    public function edit(Sekolah $sekolah)
    {
        Gate::authorize('update', $sekolah);

        return view('admin.sekolah.edit', compact('sekolah'));
    }

    public function update(Request $request, Sekolah $sekolah)
    {
        Gate::authorize('update', $sekolah);

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'npsn' => [
                'required',
                'digits:8',
                Rule::unique('sekolahs', 'npsn')->ignore($sekolah->id),
            ],
            'jenjang' => ['required', Rule::in(['SD', 'SMP', 'MTS', 'SMA', 'SMK'])],
            'status' => ['required', Rule::in(['Negeri', 'Swasta'])],
            'alamat' => ['nullable', 'string'],
            'kecamatan' => ['nullable', 'string'],
            'telepon' => ['nullable', 'string', 'max:30'],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ]);

        if ($request->user()->isSekolah()) {
            unset($data['npsn']);

            if ($sekolah->latitude === null || $sekolah->longitude === null) {
                return back()->withErrors([
                    'latitude' => 'Koordinat awal belum tersedia. Hubungi Admin untuk menetapkan lokasi sekolah.',
                ])->withInput();
            }

            $jarak = $this->hitungJarak(
                (float) $sekolah->latitude,
                (float) $sekolah->longitude,
                (float) $data['latitude'],
                (float) $data['longitude']
            );

            if ($jarak > 100) {
                return back()->withErrors([
                    'latitude' => 'Perubahan lokasi ditolak. Jarak perpindahan '
                        .round($jarak, 2)
                        .' meter, melebihi batas maksimal 100 meter.',
                ])->withInput();
            }
        }

        $sekolah->update($data);

        return redirect()->back()->with('success', 'Data sekolah berhasil diperbarui.');
    }

    public function destroy(Sekolah $sekolah)
    {
        $sekolah->delete();

        return redirect()->route('dinas.sekolah.index')
            ->with('success', 'Data sekolah berhasil dihapus.');
    }

    private function hitungJarak(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $radiusBumi = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(max(0, 1 - $a)));

        return $radiusBumi * $c;
    }
}
