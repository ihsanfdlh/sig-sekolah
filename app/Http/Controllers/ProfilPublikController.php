<?php
namespace App\Http\Controllers;
use App\Models\Sekolah;
use Illuminate\Http\JsonResponse;
class ProfilPublikController extends Controller
{
    public function show(Sekolah $sekolah): JsonResponse
    {
        $sekolah->load('profil');
        return response()->json([
            'status' => 'berhasil',
            'sekolah' => $sekolah->nama,
            'profil' => $sekolah->profil,
        ]);
    }
}
