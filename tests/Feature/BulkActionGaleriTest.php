<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Gallery;
use App\Livewire\Admin\ManajemenGaleri;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

class BulkActionGaleriTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_galeri_bulk_test',
        ]);

        Storage::fake('public');
    }

    public function test_bulk_action_ui_elements_exist_on_galeri_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.pengaturan.galeri'));
        $response->assertStatus(200);
        $response->assertSee('Aksi Masal');
        $response->assertSee('Hapus Masal');
    }

    public function test_admin_can_bulk_delete_galleries()
    {
        $items = [];
        for ($i = 1; $i <= 5; $i++) {
            $path = "gallery/test_{$i}.jpg";
            Storage::disk('public')->put($path, 'dummy image content');

            $items[] = Gallery::create([
                'judul'     => "Foto Galeri Ke-{$i}",
                'file_path' => $path,
                'file_url'  => asset("storage/{$path}"),
                'kategori'  => 'Umum',
                'sumber'    => 'manual',
            ]);
        }

        $this->assertEquals(5, Gallery::count());

        $deleteIds = [(string) $items[0]->id, (string) $items[1]->id, (string) $items[2]->id];

        Livewire::actingAs($this->admin)
            ->test(ManajemenGaleri::class)
            ->set('selectedGalleries', $deleteIds)
            ->call('bulkDelete')
            ->assertSee('3 foto berhasil dihapus secara masal dari Galeri.');

        $this->assertEquals(2, Gallery::count());
        $this->assertDatabaseMissing('galleries', ['id' => $items[0]->id]);
        $this->assertDatabaseMissing('galleries', ['id' => $items[1]->id]);
        $this->assertDatabaseMissing('galleries', ['id' => $items[2]->id]);
        $this->assertDatabaseHas('galleries', ['id' => $items[3]->id]);
        $this->assertDatabaseHas('galleries', ['id' => $items[4]->id]);

        // Verify files on storage were deleted
        Storage::disk('public')->assertMissing($items[0]->file_path);
        Storage::disk('public')->assertMissing($items[1]->file_path);
        Storage::disk('public')->assertMissing($items[2]->file_path);
        Storage::disk('public')->assertExists($items[3]->file_path);
        Storage::disk('public')->assertExists($items[4]->file_path);
    }

    public function test_select_all_toggles_all_visible_galleries()
    {
        for ($i = 1; $i <= 4; $i++) {
            Gallery::create([
                'judul'     => "Foto Batch {$i}",
                'file_path' => "gallery/batch_{$i}.jpg",
                'kategori'  => 'Kegiatan',
                'sumber'    => 'manual',
            ]);
        }

        $component = Livewire::actingAs($this->admin)
            ->test(ManajemenGaleri::class)
            ->set('selectAll', true);

        $this->assertCount(4, $component->get('selectedGalleries'));

        $component->set('selectAll', false);
        $this->assertCount(0, $component->get('selectedGalleries'));
    }

    public function test_execute_bulk_action_dropdown_delete_on_galeri()
    {
        $g1 = Gallery::create([
            'judul'     => 'Foto Dropdown 1',
            'file_path' => 'gallery/dd1.jpg',
            'kategori'  => 'Umum',
            'sumber'    => 'manual',
        ]);

        $g2 = Gallery::create([
            'judul'     => 'Foto Dropdown 2',
            'file_path' => 'gallery/dd2.jpg',
            'kategori'  => 'Umum',
            'sumber'    => 'manual',
        ]);

        Livewire::actingAs($this->admin)
            ->test(ManajemenGaleri::class)
            ->set('selectedGalleries', [(string) $g1->id, (string) $g2->id])
            ->set('bulkAction', 'delete')
            ->call('executeBulkAction')
            ->assertSee('2 foto berhasil dihapus secara masal dari Galeri.');

        $this->assertEquals(0, Gallery::count());
    }

    public function test_bulk_actions_validation_when_empty_selection_on_galeri()
    {
        Livewire::actingAs($this->admin)
            ->test(ManajemenGaleri::class)
            ->set('selectedGalleries', [])
            ->call('bulkDelete')
            ->assertSee('Tidak ada foto/media yang dipilih untuk dihapus.');
    }
}
