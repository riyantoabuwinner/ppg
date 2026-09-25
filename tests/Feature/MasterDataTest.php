<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MasterDataTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $mahasiswa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_test',
        ]);

        $this->mahasiswa = User::factory()->create([
            'role' => 'mahasiswa',
            'username' => 'mhs_test',
        ]);
    }

    public function test_admin_can_access_master_data_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.master-data'));
        $response->assertStatus(200);
        $response->assertSee('Master Data Mahasiswa');
        $response->assertSee('Tabel Data Mahasiswa Lapor Diri');
    }

    public function test_master_data_mahasiswa_redirects_to_master_data()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.master-data.mahasiswa'));
        $response->assertRedirect(route('admin.master-data'));
    }

    public function test_sidebar_has_master_data_menu()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Master Data');
        $response->assertSee('Mahasiswa Lapor Diri');
    }

    public function test_dashboard_does_not_contain_the_monitoring_peserta_table_anymore()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertDontSee('Tabel Data Mahasiswa Lapor Diri');
        $response->assertSee('Buka Master Data');
    }

    public function test_mahasiswa_cannot_access_master_data()
    {
        $response = $this->actingAs($this->mahasiswa)->get(route('admin.master-data'));
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    public function test_guest_cannot_access_master_data()
    {
        $response = $this->get(route('admin.master-data'));
        $response->assertRedirect(route('login'));
    }
}
