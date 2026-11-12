<?php

namespace Tests\Feature;

use App\Models\Sekolah;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_map_geojson_filters_schools_by_status(): void
    {
        Sekolah::create([
            'npsn' => '12345678',
            'nama' => 'Sekolah Negeri',
            'jenjang' => 'SD',
            'status' => 'Negeri',
            'alamat' => 'Jl. Uji',
            'kecamatan' => 'Uji',
            'latitude' => -3.3,
            'longitude' => 114.59,
        ]);

        $swasta = Sekolah::create([
            'npsn' => '87654321',
            'nama' => 'Sekolah Swasta',
            'jenjang' => 'SMP',
            'status' => 'Swasta',
            'alamat' => 'Jl. Uji',
            'kecamatan' => 'Uji',
            'latitude' => -3.31,
            'longitude' => 114.6,
        ]);

        $response = $this->getJson(route('peta.data', ['status' => 'Swasta']));

        $response
            ->assertOk()
            ->assertJsonCount(1, 'features')
            ->assertJsonPath('features.0.properties.npsn', $swasta->npsn)
            ->assertJsonPath('features.0.properties.status', 'Swasta');
    }
}
