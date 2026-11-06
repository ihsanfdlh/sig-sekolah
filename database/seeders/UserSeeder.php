<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $sekolahs = Sekolah::orderBy('id')->take(2)->get();
        $akun = [
            ['Admin Sistem', 'admin@sig.test', UserRole::ADMIN, null],
            ['Dinas Pendidikan', 'dinas@sig.test', UserRole::DINAS, null],
            ['Operator Sekolah 1', 'sekolah1@sig.test', UserRole::SEKOLAH, $sekolahs->get(0)?->id],
            ['Operator Sekolah 2', 'sekolah2@sig.test', UserRole::SEKOLAH, $sekolahs->get(1)?->id],
        ];
        foreach ($akun as [$nama, $email, $role, $sekolahId]) {
            User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $nama,
                    'password' => 'password123',
                    'role' => $role,
                    'sekolah_id' => $sekolahId,
                ]
            );
        }
    }
}
