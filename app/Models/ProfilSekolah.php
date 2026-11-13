<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ProfilSekolah extends Model
{
    protected $table = 'profil_sekolahs';
    protected $fillable = [
        'sekolah_id',
        'kepala_sekolah',
        'email',
        'website',
        'tahun_berdiri',
        'jumlah_siswa',
        'jumlah_guru',
        'akreditasi',
        'deskripsi',
        'logo',
    ];
    protected function casts(): array
    {
        return [
            'tahun_berdiri' => 'integer',
            'jumlah_siswa' => 'integer',
            'jumlah_guru' => 'integer',
        ];
    }
    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class, 'sekolah_id', 'id');
    }
}
