<?php

namespace Tests\Feature;

use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicSchoolProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_profile_url_from_geojson_opens_school_profile(): void
    {
        $this->withoutVite();

        $sekolah = Sekolah::create([
            'npsn' => '12345678',
            'nama' => 'Sekolah Uji',
            'jenjang' => 'SD',
            'status' => 'Negeri',
            'alamat' => 'Jl. Uji',
            'kecamatan' => 'Uji',
            'latitude' => -3.3,
            'longitude' => 114.59,
        ]);

        $geojsonResponse = $this->getJson(route('peta.data'));
        $profileUrl = route('sekolah.publik', $sekolah);

        $geojsonResponse
            ->assertOk()
            ->assertJsonPath('features.0.properties.url_profil', $profileUrl);

        $this->get($profileUrl)
            ->assertOk()
            ->assertSee('Sekolah Uji');

        $this->get('/')
            ->assertOk()
            ->assertSee('WebGIS Peta Sekolah')
            ->assertDontSee('aria-controls="mobile-menu"', false)
            ->assertSee('const urlProfil = p.url_profil', false);
    }

    public function test_authenticated_map_keeps_its_existing_dashboard_and_logout_links(): void
    {
        $this->withoutVite();

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get('/')
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertDontSee('Pengguna')
            ->assertSee('Keluar');
    }

    public function test_public_header_shows_tut_wuri_watermark_and_mobile_navigation(): void
    {
        $this->withoutVite();

        $this->get('/')
            ->assertOk()
            ->assertSee('SIG Sekolah')
            ->assertSee('storage/Assets/tut-wuri-handayani.png')
            ->assertSee('id="public-menu-button"', false)
            ->assertSee('id="public-mobile-menu"', false);
    }
}
