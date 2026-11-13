<?php

namespace Tests\Feature;

use App\Models\ProfilSekolah;
use App\Models\Sekolah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SecurityRegressionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_state_changing_forms_render_csrf_tokens(): void
    {
        $sekolah = $this->createSchool('11111111');
        $dinas = $this->createUser('dinas');

        $this->get('/login')
            ->assertOk()
            ->assertSee('name="_token"', false);

        $this->actingAs($dinas)
            ->get(route('sekolah.profil.edit', $sekolah))
            ->assertOk()
            ->assertSee('name="_token"', false);

        $this->get(route('password.edit'))
            ->assertOk()
            ->assertSee('name="_token"', false);

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('name="_token"', false);
    }

    public function test_inactive_accounts_cannot_authenticate_or_keep_an_existing_session(): void
    {
        $user = User::factory()->create([
            'role' => 'dinas',
            'is_active' => false,
        ]);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertSessionHasErrors('email');

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertRedirect(route('login'))
            ->assertSessionHas('error');

        $this->assertGuest();
    }

    public function test_login_blocks_attempts_after_the_route_limit(): void
    {
        for ($attempt = 0; $attempt < 5; $attempt++) {
            $this->post('/login', [
                'email' => 'unknown@uji.test',
                'password' => 'wrong-password',
            ])->assertRedirect();
        }

        $this->post('/login', [
            'email' => 'unknown@uji.test',
            'password' => 'wrong-password',
        ])->assertTooManyRequests();
    }

    public function test_school_users_cannot_update_another_schools_data_or_profile(): void
    {
        $ownedSchool = $this->createSchool('11111111');
        $otherSchool = $this->createSchool('22222222');
        $operator = $this->createUser('sekolah', $ownedSchool);

        $this->actingAs($operator)
            ->put(route('sekolah.update', $otherSchool), [
                'npsn' => $otherSchool->npsn,
                'nama' => 'Nama Diubah Tanpa Izin',
                'jenjang' => 'SD',
                'status' => 'Negeri',
                'alamat' => 'Alamat baru',
                'kecamatan' => 'Uji',
                'latitude' => -3.3,
                'longitude' => 114.59,
            ])
            ->assertForbidden();

        $this->put(route('sekolah.profil.update', $otherSchool), [
            'kepala_sekolah' => 'Perubahan Tanpa Izin',
        ])->assertForbidden();

        $this->assertSame("Sekolah {$otherSchool->npsn}", $otherSchool->fresh()->nama);
        $this->assertDatabaseMissing('profil_sekolahs', [
            'sekolah_id' => $otherSchool->id,
        ]);
    }

    public function test_school_users_cannot_move_their_school_beyond_the_allowed_distance(): void
    {
        $sekolah = $this->createSchool('33333333');
        $operator = $this->createUser('sekolah', $sekolah);

        $this->actingAs($operator)
            ->put(route('sekolah.update', $sekolah), [
                'npsn' => $sekolah->npsn,
                'nama' => $sekolah->nama,
                'jenjang' => 'SD',
                'status' => 'Negeri',
                'alamat' => $sekolah->alamat,
                'kecamatan' => $sekolah->kecamatan,
                'latitude' => -3.31,
                'longitude' => 114.59,
            ])
            ->assertSessionHasErrors('latitude');

        $this->assertSame(-3.3, (float) $sekolah->fresh()->latitude);
    }

    public function test_non_image_logo_upload_is_rejected_without_storing_a_file(): void
    {
        Storage::fake('public');

        $sekolah = $this->createSchool('44444444');
        $operator = $this->createUser('sekolah', $sekolah);

        $this->actingAs($operator)
            ->put(route('sekolah.profil.update', $sekolah), [
                'logo' => UploadedFile::fake()->createWithContent(
                    'payload.php',
                    '<?php echo "not an image";'
                ),
            ])
            ->assertSessionHasErrors('logo');

        $this->assertSame([], Storage::disk('public')->allFiles('logo-sekolah'));
        $this->assertDatabaseMissing('profil_sekolahs', [
            'sekolah_id' => $sekolah->id,
        ]);
    }

    public function test_public_profile_escapes_untrusted_school_content(): void
    {
        $sekolah = $this->createSchool('55555555', [
            'nama' => '<script>alert(1)</script>',
        ]);
        ProfilSekolah::create([
            'sekolah_id' => $sekolah->id,
            'deskripsi' => '<img src=x onerror=alert(1)>',
        ]);

        $this->get(route('sekolah.publik', $sekolah))
            ->assertOk()
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false)
            ->assertSee('&lt;img src=x onerror=alert(1)&gt;', false)
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertDontSee('<img src=x onerror=alert(1)>', false);
    }

    public function test_public_profile_does_not_render_a_javascript_website_link(): void
    {
        $sekolah = $this->createSchool('66666666');
        ProfilSekolah::create([
            'sekolah_id' => $sekolah->id,
            'website' => 'javascript:alert(1)',
        ]);

        $this->get(route('sekolah.publik', $sekolah))
            ->assertOk()
            ->assertDontSee('href="javascript:alert(1)"', false);
    }

    public function test_school_search_treats_sql_syntax_as_plain_input(): void
    {
        $this->createSchool('77777777', [
            'nama' => 'Sekolah Rahasia',
        ]);
        $dinas = $this->createUser('dinas');

        $this->actingAs($dinas)
            ->get('/dinas/sekolah?q='.urlencode("' OR 1=1 --"))
            ->assertOk()
            ->assertDontSee('Sekolah Rahasia');
    }

    public function test_admin_cannot_set_an_unvalidated_account_status_when_creating_a_user(): void
    {
        $sekolah = $this->createSchool('88888888');
        $admin = $this->createUser('admin');

        $this->actingAs($admin)
            ->post('/admin/users', [
                'name' => 'Operator Uji',
                'email' => 'operator@uji.test',
                'role' => 'sekolah',
                'sekolah_id' => $sekolah->id,
                'password' => 'password123',
                'password_confirmation' => 'password123',
                'is_active' => false,
            ])
            ->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', [
            'email' => 'operator@uji.test',
            'role' => 'sekolah',
            'is_active' => true,
        ]);
    }

    public function test_password_is_unchanged_when_the_current_password_is_wrong(): void
    {
        $user = User::factory()->create([
            'password' => 'CurrentPassword123',
        ]);
        $storedPassword = $user->password;

        $this->actingAs($user)
            ->put(route('password.update'), [
                'password_lama' => 'WrongPassword123',
                'password' => 'NewPassword123',
                'password_confirmation' => 'NewPassword123',
            ])
            ->assertSessionHasErrors('password_lama');

        $this->assertSame($storedPassword, $user->fresh()->password);
    }

    public function test_password_change_requires_matching_confirmation(): void
    {
        $user = User::factory()->create([
            'password' => 'CurrentPassword123',
        ]);
        $storedPassword = $user->password;

        $this->actingAs($user)
            ->put(route('password.update'), [
                'password_lama' => 'CurrentPassword123',
                'password' => 'NewPassword123',
                'password_confirmation' => 'DifferentPassword123',
            ])
            ->assertSessionHasErrors('password');

        $this->assertSame($storedPassword, $user->fresh()->password);
    }

    public function test_invalid_school_input_does_not_change_persisted_data(): void
    {
        $sekolah = $this->createSchool('99999999');
        $operator = $this->createUser('sekolah', $sekolah);

        $this->actingAs($operator)
            ->put(route('sekolah.update', $sekolah), [
                'npsn' => $sekolah->npsn,
                'nama' => 'Nama Valid',
                'jenjang' => 'INVALID',
                'status' => 'Negeri',
                'alamat' => $sekolah->alamat,
                'kecamatan' => $sekolah->kecamatan,
                'latitude' => -3.3,
                'longitude' => 114.59,
            ])
            ->assertSessionHasErrors('jenjang');

        $this->assertSame("Sekolah {$sekolah->npsn}", $sekolah->fresh()->nama);
    }

    private function createSchool(string $npsn, array $attributes = []): Sekolah
    {
        return Sekolah::create(array_merge([
            'npsn' => $npsn,
            'nama' => "Sekolah {$npsn}",
            'jenjang' => 'SD',
            'status' => 'Negeri',
            'alamat' => 'Jl. Uji',
            'kecamatan' => 'Uji',
            'latitude' => -3.3,
            'longitude' => 114.59,
        ], $attributes));
    }

    private function createUser(string $role, ?Sekolah $sekolah = null): User
    {
        return User::factory()->create([
            'role' => $role,
            'sekolah_id' => $sekolah?->id,
        ]);
    }
}
