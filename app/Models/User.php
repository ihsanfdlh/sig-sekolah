<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_DINAS = 'dinas';
    public const ROLE_SEKOLAH = 'sekolah';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'sekolah_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relasi User dengan Sekolah
    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class);
    }

    // Mengecek apakah user Admin
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    // Mengecek apakah user Dinas
    public function isDinas(): bool
    {
        return $this->role === self::ROLE_DINAS;
    }

    // Mengecek apakah user Sekolah
    public function isSekolah(): bool
    {
        return $this->role === self::ROLE_SEKOLAH;
    }

    // Menampilkan nama role
    public function roleLabel(): string
    {
        return match ($this->role) {
            self::ROLE_ADMIN => 'Admin',
            self::ROLE_DINAS => 'Dinas Pendidikan',
            self::ROLE_SEKOLAH => 'Sekolah',
            default => 'Tidak diketahui',
        };
    }
    public function isActive(): bool
    {
        return $this->is_active;
    }
}
