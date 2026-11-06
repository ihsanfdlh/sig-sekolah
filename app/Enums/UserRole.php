<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case DINAS = 'dinas';
    case SEKOLAH = 'sekolah';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrator',
            self::DINAS => 'Dinas Pendidikan',
            self::SEKOLAH => 'Operator Sekolah',
        };
    }
}
