<?php

namespace App\Support;

class Geometri
{
    /**
     * Menghitung Convex Hull menggunakan
     * algoritma Monotone Chain.
     */
    public static function convexHull(array $titik): array
    {
        // Validasi koordinat
        $titik = array_values(array_filter(
            $titik,
            fn ($p) =>
                is_array($p) &&
                count($p) >= 2 &&
                is_numeric($p[0]) &&
                is_numeric($p[1]) &&
                is_finite((float) $p[0]) &&
                is_finite((float) $p[1])
        ));

        // Hilangkan koordinat duplikat
        $unik = [];

        foreach ($titik as $p) {
            $x = (float) $p[0];
            $y = (float) $p[1];

            $unik[json_encode([$x, $y])] = [$x, $y];
        }

        $titik = array_values($unik);

        if (count($titik) < 3) {
            return [];
        }

        // Urutkan berdasarkan longitude dan latitude
        usort($titik, function ($a, $b) {
            return ($a[0] <=> $b[0])
                ?: ($a[1] <=> $b[1]);
        });

        // Menghitung cross product
        $cross = function ($o, $a, $b) {
            return ($a[0] - $o[0]) * ($b[1] - $o[1])
                 - ($a[1] - $o[1]) * ($b[0] - $o[0]);
        };

        // Membentuk hull bagian bawah
        $lower = [];

        foreach ($titik as $p) {
            while (
                count($lower) >= 2 &&
                $cross(
                    $lower[count($lower) - 2],
                    $lower[count($lower) - 1],
                    $p
                ) <= 0
            ) {
                array_pop($lower);
            }

            $lower[] = $p;
        }

        // Membentuk hull bagian atas
        $upper = [];

        foreach (array_reverse($titik) as $p) {
            while (
                count($upper) >= 2 &&
                $cross(
                    $upper[count($upper) - 2],
                    $upper[count($upper) - 1],
                    $p
                ) <= 0
            ) {
                array_pop($upper);
            }

            $upper[] = $p;
        }

        // Gabungkan kedua bagian
        array_pop($lower);
        array_pop($upper);

        $hasil = array_merge($lower, $upper);

        return count($hasil) >= 3 ? $hasil : [];
    }
}
