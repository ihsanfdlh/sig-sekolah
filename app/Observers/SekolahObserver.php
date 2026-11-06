<?php

namespace App\Observers;

use App\Models\LogAktivitas;
use App\Models\Sekolah;
use Illuminate\Support\Facades\Auth;

class SekolahObserver
{
    private function catat(Sekolah $sekolah, string $aktivitas, ?array $sebelum, ?array $sesudah): void
    {
        $user = Auth::user();

        LogAktivitas::create([
            'user_id' => $user?->id,
            'sekolah_id' => $sekolah->id,
            'nama_pengguna' => $user?->name ?? 'Sistem',
            'nama_sekolah' => $sekolah->nama,
            'aktivitas' => $aktivitas,
            'data_sebelum' => $sebelum,
            'data_sesudah' => $sesudah,
        ]);
    }

    public function created(Sekolah $sekolah): void
    {
        $this->catat($sekolah, 'tambah', null, $sekolah->getAttributes());
    }

    public function updated(Sekolah $sekolah): void
    {
        $perubahan = $sekolah->getChanges();
        unset($perubahan['updated_at']);

        if (empty($perubahan)) {
            return;
        }

        $sebelum = [];
        foreach ($perubahan as $kolom => $nilai) {
            $sebelum[$kolom] = $sekolah->getRawOriginal($kolom);
        }

        $this->catat($sekolah, 'ubah', $sebelum, $perubahan);
    }

    public function deleted(Sekolah $sekolah): void
    {
        $this->catat($sekolah, 'hapus', $sekolah->getAttributes(), null);
    }
}
