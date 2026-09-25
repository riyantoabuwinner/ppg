<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\StudentProfile;
use App\Models\StudentEducation;
use App\Models\StudentFamily;
use App\Services\LaporDiriImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use App\Livewire\Admin\MonitoringPeserta;

class ImportLaporDiriTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $mahasiswa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role'     => 'admin',
            'username' => 'admin_test',
        ]);

        $this->mahasiswa = User::factory()->create([
            'role'     => 'mahasiswa',
            'username' => 'mhs_test',
        ]);
    }

    public function test_admin_can_download_import_template()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.master-data.template-import'));
        $response->assertStatus(200);
        $response->assertHeader('content-disposition');
    }

    public function test_mahasiswa_cannot_download_import_template()
    {
        $response = $this->actingAs($this->mahasiswa)->get(route('admin.master-data.template-import'));
        $response->assertRedirect(route('login'));
    }

    public function test_lapor_diri_import_service_imports_csv_correctly()
    {
        Storage::fake('local');

        $csvContent = implode("\n", [
            'NIM,Nama Lengkap,NIK,No Tes,Tempat Lahir,Tanggal Lahir,Jenis Kelamin,Agama,Alamat,No HP,Email,Asal Perguruan Tinggi,Asal Program Studi,Jalur Pendaftaran,Gelombang,Tahun Masuk,Status Lapor Diri,Nama Ayah,Pekerjaan Ayah,Nama Ibu,Pekerjaan Ibu',
            '2509110001,Ahmad Fauzi,3209112233440001,TEST-001,Cirebon,1997-04-12,Laki-laki,Islam,Jl. Kartini No 10,081234567891,fauzi@example.com,UIN Syekh Nurjati,Pendidikan Agama Islam,Reguler,Gelombang 1,2025,verified,Fauzan,PNS,Fatimah,Guru',
            '2509110002,Siti Rahmawati,3209112233440002,TEST-002,Indramayu,1998-09-25,Perempuan,Islam,Jl. Sudirman No 20,081234567892,siti@example.com,Universitas Wiralodra,Pendidikan Bahasa Inggris,Daljab,Gelombang 1,2025,submitted,Rahman,Petani,Rohimah,Wiraswasta',
        ]);

        $tempPath = tempnam(sys_get_temp_dir(), 'test_csv_') . '.csv';
        file_put_contents($tempPath, $csvContent);

        $service = new LaporDiriImportService();
        $result = $service->import($tempPath, [
            'duplicate_action' => 'update',
            'status_default'   => 'submitted',
            'password_scheme'  => 'dob',
        ]);

        $this->assertEquals(2, $result['total_rows']);
        $this->assertEquals(2, $result['imported']);
        $this->assertEquals(0, $result['updated']);
        $this->assertEquals(0, $result['skipped']);
        $this->assertEmpty($result['errors']);

        // Verify Ahmad Fauzi
        $user1 = User::where('username', '2509110001')->first();
        $this->assertNotNull($user1);
        $this->assertEquals('Ahmad Fauzi', $user1->name);
        $this->assertEquals('fauzi@example.com', $user1->email);
        $this->assertEquals('verified', $user1->status_lapor_diri);

        $profile1 = StudentProfile::where('user_id', $user1->id)->first();
        $this->assertNotNull($profile1);
        $this->assertEquals('3209112233440001', $profile1->nik);
        $this->assertEquals('Cirebon', $profile1->nama_tempat_lahir);
        $this->assertEquals('Laki-laki', $profile1->jenis_kelamin);

        $edu1 = StudentEducation::where('student_profile_id', $profile1->id)->first();
        $this->assertNotNull($edu1);
        $this->assertEquals('UIN Syekh Nurjati', $edu1->asal_perguruan_tinggi);
        $this->assertEquals('Pendidikan Agama Islam', $edu1->asal_program_studi);

        $ayah1 = StudentFamily::where('student_profile_id', $profile1->id)->where('tipe', 'ayah')->first();
        $this->assertNotNull($ayah1);
        $this->assertEquals('Fauzan', $ayah1->nama);
        $this->assertEquals('PNS', $ayah1->pekerjaan);

        // Verify Siti Rahmawati
        $user2 = User::where('username', '2509110002')->first();
        $this->assertNotNull($user2);
        $this->assertEquals('submitted', $user2->status_lapor_diri);

        @unlink($tempPath);
    }

    public function test_lapor_diri_import_service_handles_duplicate_with_update_mode()
    {
        // Existing user
        $user = User::create([
            'username'          => '2509110003',
            'name'              => 'Budi Lama',
            'email'             => 'budi_lama@example.com',
            'password'          => bcrypt('password'),
            'role'              => 'mahasiswa',
            'status_lapor_diri' => 'draft',
        ]);

        $profile = StudentProfile::create([
            'user_id' => $user->id,
            'nim'     => '2509110003',
            'nama'    => 'Budi Lama',
            'nik'     => '3209110003330001',
        ]);

        $csvContent = implode("\n", [
            'NIM,Nama Lengkap,NIK,Alamat,Status Lapor Diri',
            '2509110003,Budi Telah Diperbarui,3209110003330001,Jl. Baru No 99,verified',
        ]);

        $tempPath = tempnam(sys_get_temp_dir(), 'test_csv_') . '.csv';
        file_put_contents($tempPath, $csvContent);

        $service = new LaporDiriImportService();
        $result = $service->import($tempPath, [
            'duplicate_action' => 'update',
        ]);

        $this->assertEquals(1, $result['total_rows']);
        $this->assertEquals(0, $result['imported']);
        $this->assertEquals(1, $result['updated']);
        $this->assertEquals(0, $result['skipped']);

        $user->refresh();
        $profile->refresh();

        $this->assertEquals('Budi Telah Diperbarui', $user->name);
        $this->assertEquals('verified', $user->status_lapor_diri);
        $this->assertEquals('Jl. Baru No 99', $profile->alamat);

        @unlink($tempPath);
    }

    public function test_lapor_diri_import_service_handles_duplicate_with_skip_mode()
    {
        // Existing user
        $user = User::create([
            'username'          => '2509110004',
            'name'              => 'Budi Asli',
            'email'             => 'budi_asli@example.com',
            'password'          => bcrypt('password'),
            'role'              => 'mahasiswa',
            'status_lapor_diri' => 'draft',
        ]);

        $csvContent = implode("\n", [
            'NIM,Nama Lengkap,NIK,Alamat',
            '2509110004,Budi Yang Mau Ditolak,3209110004440001,Jl. Palsu',
        ]);

        $tempPath = tempnam(sys_get_temp_dir(), 'test_csv_') . '.csv';
        file_put_contents($tempPath, $csvContent);

        $service = new LaporDiriImportService();
        $result = $service->import($tempPath, [
            'duplicate_action' => 'skip',
        ]);

        $this->assertEquals(1, $result['total_rows']);
        $this->assertEquals(0, $result['imported']);
        $this->assertEquals(0, $result['updated']);
        $this->assertEquals(1, $result['skipped']);

        $user->refresh();
        $this->assertEquals('Budi Asli', $user->name);

        @unlink($tempPath);
    }

    public function test_artisan_command_ppg_import_lapor_diri()
    {
        $csvContent = implode("\n", [
            'NIM,Nama Lengkap,NIK,Tempat Lahir,Tanggal Lahir,Asal Perguruan Tinggi',
            '2509110005,Artisan Test User,3209110005550001,Cirebon,1999-01-01,UIN SSC',
        ]);

        $tempPath = tempnam(sys_get_temp_dir(), 'test_csv_') . '.csv';
        file_put_contents($tempPath, $csvContent);

        $this->artisan('ppg:import-lapor-diri', [
            'file' => $tempPath,
            '--status' => 'submitted',
        ])
        ->expectsOutputToContain('Hasil Impor Data Lapor Diri')
        ->expectsOutputToContain('Data Baru Diimpor: 1')
        ->assertExitCode(0);

        $this->assertDatabaseHas('users', [
            'username' => '2509110005',
            'name'     => 'Artisan Test User',
        ]);

        @unlink($tempPath);
    }

    public function test_livewire_monitoring_peserta_import_flow()
    {
        $csvContent = implode("\n", [
            'NIM,Nama Lengkap,NIK,Asal Program Studi',
            '2509110006,Livewire Mahasiswa Test,3209110006660001,Pendidikan Agama Islam',
        ]);

        $file = UploadedFile::fake()->createWithContent('lapor_diri.csv', $csvContent);

        Livewire::actingAs($this->admin)
            ->test(MonitoringPeserta::class)
            ->assertSee('Import Data Lapor Diri')
            ->call('openImportModal')
            ->assertSet('showImportModal', true)
            ->set('fileImport', $file)
            ->call('importLaporDiri')
            ->assertHasNoErrors()
            ->assertSee('Impor berhasil')
            ->call('closeImportModal')
            ->assertSet('showImportModal', false);

        $this->assertDatabaseHas('users', [
            'username' => '2509110006',
            'name'     => 'Livewire Mahasiswa Test',
        ]);
    }
}
