<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Livewire\Admin\ManajemenArtikel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

class BulkActionArtikelTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_bulk_test',
        ]);
    }

    public function test_bulk_action_ui_elements_exist_on_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.pengaturan.artikel'));
        $response->assertStatus(200);
        $response->assertSee('Aksi Masal');
        $response->assertSee('Hapus Masal');
        $response->assertSee('Publikasikan Masal');
        $response->assertSee('Jadikan Draft Masal');
    }

    public function test_admin_can_bulk_delete_articles()
    {
        $articles = [];
        for ($i = 1; $i <= 5; $i++) {
            $articles[] = Article::create([
                'author_id' => $this->admin->id,
                'judul' => "Artikel Berita Ke-{$i}",
                'konten' => "Konten isi berita ke-{$i}",
                'kategori' => 'Berita',
                'status' => 'draft',
            ]);
        }

        $this->assertEquals(5, Article::count());

        $deleteIds = [(string) $articles[0]->id, (string) $articles[1]->id, (string) $articles[2]->id];

        Livewire::actingAs($this->admin)
            ->test(ManajemenArtikel::class)
            ->set('selectedArticles', $deleteIds)
            ->call('bulkDelete')
            ->assertSee('3 artikel berhasil dihapus secara masal.');

        $this->assertEquals(2, Article::count());
        $this->assertDatabaseMissing('articles', ['id' => $articles[0]->id]);
        $this->assertDatabaseMissing('articles', ['id' => $articles[1]->id]);
        $this->assertDatabaseMissing('articles', ['id' => $articles[2]->id]);
        $this->assertDatabaseHas('articles', ['id' => $articles[3]->id]);
        $this->assertDatabaseHas('articles', ['id' => $articles[4]->id]);
    }

    public function test_admin_can_bulk_publish_and_bulk_draft_articles()
    {
        $article1 = Article::create([
            'author_id' => $this->admin->id,
            'judul' => 'Artikel Uji Publikasi 1',
            'konten' => 'Konten pengujian 1',
            'status' => 'draft',
        ]);

        $article2 = Article::create([
            'author_id' => $this->admin->id,
            'judul' => 'Artikel Uji Publikasi 2',
            'konten' => 'Konten pengujian 2',
            'status' => 'draft',
        ]);

        // Bulk Publish
        Livewire::actingAs($this->admin)
            ->test(ManajemenArtikel::class)
            ->set('selectedArticles', [(string) $article1->id, (string) $article2->id])
            ->call('bulkPublish')
            ->assertSee('2 artikel berhasil dipublikasikan secara masal.');

        $this->assertEquals('published', $article1->fresh()->status);
        $this->assertNotNull($article1->fresh()->published_at);
        $this->assertEquals('published', $article2->fresh()->status);

        // Bulk Draft
        Livewire::actingAs($this->admin)
            ->test(ManajemenArtikel::class)
            ->set('selectedArticles', [(string) $article1->id, (string) $article2->id])
            ->call('bulkDraft')
            ->assertSee('2 artikel berhasil diubah statusnya menjadi draft.');

        $this->assertEquals('draft', $article1->fresh()->status);
        $this->assertEquals('draft', $article2->fresh()->status);
    }

    public function test_select_all_toggles_all_visible_articles()
    {
        for ($i = 1; $i <= 4; $i++) {
            Article::create([
                'author_id' => $this->admin->id,
                'judul' => "Artikel Batch {$i}",
                'konten' => "Konten {$i}",
                'status' => 'draft',
            ]);
        }

        $component = Livewire::actingAs($this->admin)
            ->test(ManajemenArtikel::class)
            ->set('selectAll', true);

        $this->assertCount(4, $component->get('selectedArticles'));

        $component->set('selectAll', false);
        $this->assertCount(0, $component->get('selectedArticles'));
    }

    public function test_execute_bulk_action_dropdown_delete()
    {
        $a1 = Article::create([
            'author_id' => $this->admin->id,
            'judul' => 'Artikel Dropdown 1',
            'konten' => 'Konten dropdown 1',
            'status' => 'draft',
        ]);

        $a2 = Article::create([
            'author_id' => $this->admin->id,
            'judul' => 'Artikel Dropdown 2',
            'konten' => 'Konten dropdown 2',
            'status' => 'draft',
        ]);

        Livewire::actingAs($this->admin)
            ->test(ManajemenArtikel::class)
            ->set('selectedArticles', [(string) $a1->id, (string) $a2->id])
            ->set('bulkAction', 'delete')
            ->call('executeBulkAction')
            ->assertSee('2 artikel berhasil dihapus secara masal.');

        $this->assertEquals(0, Article::count());
    }

    public function test_bulk_actions_validation_when_empty_selection()
    {
        Livewire::actingAs($this->admin)
            ->test(ManajemenArtikel::class)
            ->set('selectedArticles', [])
            ->call('bulkDelete')
            ->assertSee('Tidak ada artikel yang dipilih untuk dihapus.');

        Livewire::actingAs($this->admin)
            ->test(ManajemenArtikel::class)
            ->set('selectedArticles', [])
            ->call('bulkPublish')
            ->assertSee('Tidak ada artikel yang dipilih untuk dipublikasikan.');
    }
}
