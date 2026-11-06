<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
            'role' => UserRole::class,
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
        return $this->role === UserRole::ADMIN;
    }

    // Mengecek apakah user Dinas
    public function isDinas(): bool
    {
        return $this->role === UserRole::DINAS;
    }

    // Mengecek apakah user Sekolah
    public function isSekolah(): bool
    {
        return $this->role === UserRole::SEKOLAH;
    }

    // Menampilkan nama role
    public function roleLabel(): string
    {
        return $this->role?->label() ?? 'Tidak diketahui';
    }

    // Mengecek status aktif pengguna
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }
}
