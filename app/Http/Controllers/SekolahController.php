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
            'sekolah' => new Sekolah(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'npsn'      => ['required', 'digits:8', 'unique:sekolahs,npsn'],
            'nama'      => ['required', 'string', 'max:255'],
            'jenjang'   => ['required', Rule::in(['SD', 'SMP', 'SMA', 'SMK'])],
            'status'    => ['required', Rule::in(['Negeri', 'Swasta'])],
            'alamat'    => ['required', 'string'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'telepon'   => ['nullable', 'string', 'max:20'],
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
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

        $rules = [
            'nama'      => ['required', 'string', 'max:255'],
            'jenjang'   => ['required', Rule::in(['SD', 'SMP', 'SMA', 'SMK'])],
            'status'    => ['required', Rule::in(['Negeri', 'Swasta'])],
            'alamat'    => ['required', 'string'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'telepon'   => ['nullable', 'string', 'max:20'],
            'latitude'  => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
        ];

        if (! $request->user()->isSekolah()) {
            $rules['npsn'] = [
                'required',
                'digits:8',
                Rule::unique('sekolahs', 'npsn')->ignore($sekolah->id),
            ];
        }

        $data = $request->validate($rules);

        if ($request->user()->isSekolah()) {
            unset($data['npsn']);
        }

        $sekolah->update($data);

        return back()->with('success', 'Data sekolah berhasil diperbarui.');
    }

    public function destroy(Sekolah $sekolah)
    {
        $sekolah->delete();

        return redirect()->route('dinas.sekolah.index')
            ->with('success', 'Data sekolah berhasil dihapus.');
    }
}
