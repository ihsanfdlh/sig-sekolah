<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Vite;
use Tests\TestCase;

class TrustedProxyHttpsUrlTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_form_generates_https_asset_urls_behind_vercel_proxy(): void
    {
        Vite::useHotFile(storage_path('framework/testing/vite-hot'));

        $response = $this->actingAs(User::factory()->create([
            'role' => UserRole::DINAS,
        ]))->withHeaders([
            'X-Forwarded-Proto' => 'https',
            'X-Forwarded-Host' => 'sig-sekolah-public.vercel.app',
        ])->get('/dinas/sekolah/create');

        $response->assertOk();
        $response->assertSee('https://sig-sekolah-public.vercel.app/build/assets/');
    }

    public function test_map_generates_https_data_endpoint_url_behind_vercel_proxy(): void
    {
        $response = $this->withHeaders([
            'X-Forwarded-Proto' => 'https',
            'X-Forwarded-Host' => 'sig-sekolah-public.vercel.app',
        ])->get('/');

        $response->assertOk();
        $response->assertSee('https://sig-sekolah-public.vercel.app/data/sekolah');
    }
}
