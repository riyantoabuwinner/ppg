<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PengaturanSistemTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_test',
        ]);
    }

    public function test_admin_can_access_identitas_aplikasi()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.pengaturan.identitas'));
        $response->assertStatus(200);
        $response->assertSee('Identitas Aplikasi');
    }

    public function test_admin_can_access_halaman_statis()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.pengaturan.page'));
        $response->assertStatus(200);
        $response->assertSee('Halaman Statis (Pages)');
    }

    public function test_admin_can_access_manajemen_artikel()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.pengaturan.artikel'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Berita & Artikel');
    }

    public function test_admin_can_access_manajemen_pengumuman()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.pengaturan.pengumuman'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Pengumuman');
    }

    public function test_admin_can_access_manajemen_slider()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.pengaturan.slider'));
        $response->assertStatus(200);
        $response->assertSee('Manajemen Slider');
    }

    public function test_cms_routes_redirect_to_pengaturan_sistem()
    {
        $this->actingAs($this->admin)->get(route('admin.cms.slider'))
            ->assertRedirect(route('admin.pengaturan.slider'));

        $this->actingAs($this->admin)->get(route('admin.cms.pengumuman'))
            ->assertRedirect(route('admin.pengaturan.pengumuman'));

        $this->actingAs($this->admin)->get(route('admin.cms.artikel'))
            ->assertRedirect(route('admin.pengaturan.artikel'));
    }

    public function test_sidebar_contains_all_pengaturan_sistem_menus_and_no_konten_publik_header()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Pengaturan Sistem');
        $response->assertSee('Identitas Aplikasi');
        $response->assertSee('Halaman Statis (Pages)');
        $response->assertSee('Berita & Artikel', false);
        $response->assertSee('Pengumuman');
        $response->assertSee('Manajemen Slider');
        $response->assertDontSee('sidebar-section-label mb-1">Konten Publik<', false);
    }
}
