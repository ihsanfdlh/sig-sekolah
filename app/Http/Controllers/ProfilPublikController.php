<?php
namespace App\Http\Controllers;
use App\Models\Sekolah;
use Illuminate\View\View;
class ProfilPublikController extends Controller
{
    public function show(Sekolah $sekolah): View
    {
        $sekolah->load('profil');
        return view('public.profil-sekolah', compact('sekolah'));
    }
}
