<?php

namespace Database\Seeders;

use App\Models\Sekolah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TesRibuanSekolah extends Seeder
{
    public function run(): void
    {
        // Ubah jumlah data sesuai kebutuhan
        $totalSekolah = 10000;
        $batchSize = 500;

        mt_srand(2026);

        // Pusat koordinat kecamatan Banjarmasin
        $kecamatan = [
            'Banjarmasin Utara' => [-3.2900, 114.5950],
            'Banjarmasin Timur' => [-3.3150, 114.6150],
            'Banjarmasin Tengah' => [-3.3250, 114.5900],
            'Banjarmasin Selatan' => [-3.3550, 114.5900],
            'Banjarmasin Barat' => [-3.3200, 114.5650],
        ];

        $jenjang = ['SD', 'SD', 'SD', 'SMP', 'SMP', 'SMA', 'SMK'];
        $status = ['Negeri', 'Swasta'];
        $akreditasi = ['A', 'B', 'C'];

        $daftarKecamatan = array_keys($kecamatan);

        $now = now();

        $this->command->info(
            "Membuat {$totalSekolah} data sekolah demo..."
        );

        for ($start = 1; $start <= $totalSekolah; $start += $batchSize) {

            $dataSekolah = [];

            $akhir = min(
                $start + $batchSize - 1,
                $totalSekolah
            );

            for ($i = $start; $i <= $akhir; $i++) {

                $namaKecamatan = $daftarKecamatan[
                    ($i - 1) % count($daftarKecamatan)
                ];

                [$lat, $lng] = $kecamatan[$namaKecamatan];

                $j = $jenjang[array_rand($jenjang)];

                // Koordinat acak sekitar pusat kecamatan
                $latitude = $lat + mt_rand(-100, 100) / 10000;
                $longitude = $lng + mt_rand(-100, 100) / 10000;

                $dataSekolah[] = [
                    'npsn' => sprintf('9%07d', $i),
                    'nama' => "{$j} Demo {$i}",
                    'jenjang' => $j,
                    'status' => $status[array_rand($status)],
                    'alamat' => "Jl. Demo No. {$i}, {$namaKecamatan}",
                    'kecamatan' => $namaKecamatan,
                    'latitude' => round($latitude, 7),
                    'longitude' => round($longitude, 7),
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            DB::table('sekolahs')->upsert(
                $dataSekolah,
                ['npsn'],
                [
                    'nama',
                    'jenjang',
                    'status',
                    'alamat',
                    'kecamatan',
                    'latitude',
                    'longitude',
                    'updated_at'
                ]
            );

            $this->command->info(
                "Sekolah {$start} - {$akhir} berhasil diproses."
            );
        }

        $this->command->info(
            "Selesai membuat {$totalSekolah} data sekolah demo."
        );
    }
}
