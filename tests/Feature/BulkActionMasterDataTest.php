<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\StudentProfile;
use App\Livewire\Admin\MonitoringPeserta;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class BulkActionMasterDataTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role'     => 'admin',
            'username' => 'admin_bulk_master_test',
        ]);
    }

    public function test_bulk_action_ui_elements_exist_on_master_data_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.master-data'));
        $response->assertStatus(200);
        $response->assertSee('Aksi Masal');
        $response->assertSee('Hapus Masal');
        $response->assertSee('Verifikasi Masal');
        $response->assertSee('Jadikan Draft Masal');
    }

    public function test_admin_can_bulk_delete_mahasiswa()
    {
        $students = [];
        for ($i = 1; $i <= 5; $i++) {
            $user = User::create([
                'username'          => "250911990{$i}",
                'name'              => "Mahasiswa Bulk {$i}",
                'email'             => "mhs{$i}@example.com",
                'password'          => bcrypt('password'),
                'role'              => 'mahasiswa',
                'status_lapor_diri' => 'submitted',
            ]);

            StudentProfile::create([
                'user_id' => $user->id,
                'nim'     => "250911990{$i}",
                'nama'    => "Mahasiswa Bulk {$i}",
            ]);

            $students[] = $user;
        }

        $this->assertEquals(5, User::where('role', 'mahasiswa')->count());

        $deleteIds = [(string) $students[0]->id, (string) $students[1]->id, (string) $students[2]->id];

        Livewire::actingAs($this->admin)
            ->test(MonitoringPeserta::class)
            ->set('selectedPesertas', $deleteIds)
            ->call('bulkDelete')
            ->assertSee('3 data mahasiswa lapor diri berhasil dihapus secara masal.');

        $this->assertEquals(2, User::where('role', 'mahasiswa')->count());
        $this->assertDatabaseMissing('users', ['id' => $students[0]->id]);
        $this->assertDatabaseMissing('users', ['id' => $students[1]->id]);
        $this->assertDatabaseMissing('users', ['id' => $students[2]->id]);
        $this->assertDatabaseHas('users', ['id' => $students[3]->id]);
        $this->assertDatabaseHas('users', ['id' => $students[4]->id]);
    }

    public function test_admin_can_bulk_verify_and_bulk_draft_mahasiswa()
    {
        $students = [];
        for ($i = 1; $i <= 3; $i++) {
            $user = User::create([
                'username'          => "250911880{$i}",
                'name'              => "Mahasiswa Status {$i}",
                'password'          => bcrypt('password'),
                'role'              => 'mahasiswa',
                'status_lapor_diri' => 'draft',
            ]);

            StudentProfile::create([
                'user_id' => $user->id,
                'nim'     => "250911880{$i}",
                'nama'    => "Mahasiswa Status {$i}",
            ]);

            $students[] = $user;
        }

        $ids = [(string) $students[0]->id, (string) $students[1]->id];

        // Bulk verify
        Livewire::actingAs($this->admin)
            ->test(MonitoringPeserta::class)
            ->set('selectedPesertas', $ids)
            ->call('bulkVerify')
            ->assertSee('2 data mahasiswa berhasil diverifikasi secara masal.');

        $this->assertEquals('verified', $students[0]->fresh()->status_lapor_diri);
        $this->assertEquals('verified', $students[1]->fresh()->status_lapor_diri);
        $this->assertEquals('draft', $students[2]->fresh()->status_lapor_diri);

        // Bulk draft
        Livewire::actingAs($this->admin)
            ->test(MonitoringPeserta::class)
            ->set('selectedPesertas', [(string) $students[0]->id])
            ->call('bulkDraft')
            ->assertSee('1 data mahasiswa berhasil diubah ke status draft secara masal.');

        $this->assertEquals('draft', $students[0]->fresh()->status_lapor_diri);
    }

    public function test_select_all_toggles_all_visible_mahasiswa()
    {
        for ($i = 1; $i <= 4; $i++) {
            $user = User::create([
                'username'          => "250911770{$i}",
                'name'              => "Mahasiswa Toggle {$i}",
                'password'          => bcrypt('password'),
                'role'              => 'mahasiswa',
                'status_lapor_diri' => 'submitted',
            ]);

            StudentProfile::create([
                'user_id' => $user->id,
                'nim'     => "250911770{$i}",
                'nama'    => "Mahasiswa Toggle {$i}",
            ]);
        }

        Livewire::actingAs($this->admin)
            ->test(MonitoringPeserta::class)
            ->set('selectAll', true)
            ->assertCount('selectedPesertas', 4)
            ->set('selectAll', false)
            ->assertCount('selectedPesertas', 0);
    }

    public function test_execute_bulk_action_dropdown_delete_on_mahasiswa()
    {
        $user = User::create([
            'username'          => '2509116601',
            'name'              => 'Mahasiswa Dropdown',
            'password'          => bcrypt('password'),
            'role'              => 'mahasiswa',
            'status_lapor_diri' => 'submitted',
        ]);

        StudentProfile::create([
            'user_id' => $user->id,
            'nim'     => '2509116601',
            'nama'    => 'Mahasiswa Dropdown',
        ]);

        Livewire::actingAs($this->admin)
            ->test(MonitoringPeserta::class)
            ->set('selectedPesertas', [(string) $user->id])
            ->set('bulkAction', 'delete')
            ->call('executeBulkAction')
            ->assertSee('1 data mahasiswa lapor diri berhasil dihapus secara masal.');

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_bulk_actions_validation_when_empty_selection_on_mahasiswa()
    {
        Livewire::actingAs($this->admin)
            ->test(MonitoringPeserta::class)
            ->set('selectedPesertas', [])
            ->call('bulkDelete')
            ->assertSee('Tidak ada data mahasiswa yang dipilih untuk dihapus.')
            ->set('bulkAction', '')
            ->call('executeBulkAction')
            ->assertSee('Pilih minimal satu mahasiswa terlebih dahulu.');
    }
}
