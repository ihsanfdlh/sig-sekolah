<?php

namespace App\Http\Controllers;

use App\Models\ProfilSekolah;
use App\Models\Sekolah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Throwable;

class ProfilSekolahController extends Controller
{
    public function edit(Sekolah $sekolah)
    {
        Gate::authorize('update', $sekolah);

        $profil = $sekolah->profil ?? new ProfilSekolah();

        return view('admin.sekolah.profil', compact('sekolah', 'profil'));
    }

    public function update(Request $request, Sekolah $sekolah)
    {
        Gate::authorize('update', $sekolah);
        $data = $request->validate([
            'kepala_sekolah' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'tahun_berdiri' => [
                'nullable',
                'integer',
                'min:1800',
                'max:' . date('Y'),
            ],
            'jumlah_siswa' => ['nullable', 'integer', 'min:0'],
            'jumlah_guru' => ['nullable', 'integer', 'min:0'],
            'akreditasi' => ['nullable', 'string', 'max:20'],
            'deskripsi' => ['nullable', 'string'],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        $profil = $sekolah->profil()->firstOrNew([]);

        $logoLama = $profil->logo;
        $logoBaru = null;

        unset($data['logo']);

        if ($request->hasFile('logo')) {
            try {
                $logoBaru = $request->file('logo')
                    ->store('logo-sekolah', 'public');
            } catch (Throwable $e) {
                report($e);

                throw ValidationException::withMessages([
                    'logo' => 'Gagal mengunggah logo sekolah. Periksa penyimpanan server.',
                ]);
            }

            if (!$logoBaru) {
                throw ValidationException::withMessages([
                    'logo' => 'File logo gagal disimpan ke Storage.',
                ]);
            }

            $data['logo'] = $logoBaru;
        }

        try {
            $profil->fill($data);
            $profil->save();
        } catch (Throwable $e) {
            if ($logoBaru) {
                Storage::disk('public')->delete($logoBaru);
            }

            throw $e;
        }

        if ($logoBaru && $logoLama && $logoLama !== $logoBaru) {
            try {
                Storage::disk('public')->delete($logoLama);
            } catch (Throwable $e) {
                report($e);
            }
        }

        return back()->with(
            'success',
            'Profil sekolah berhasil diperbarui.'
        );
    }
}
