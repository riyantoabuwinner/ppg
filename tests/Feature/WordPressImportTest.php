<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\Gallery;
use App\Services\WordPressImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;

class WordPressImportTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_wp_test',
        ]);
    }

    protected function getSampleWordPressXml(): string
    {
        return <<<XML
<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0"
    xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:wp="http://wordpress.org/export/1.2/"
>
<channel>
    <title>Portal PPG UIN SSC</title>
    <link>https://ppg.uinssc.ac.id</link>

    <!-- Attachment / Media item -->
    <item>
        <title>Foto Gedung PPG Siber</title>
        <link>https://ppg.uinssc.ac.id/?attachment_id=101</link>
        <dc:creator><![CDATA[admin]]></dc:creator>
        <wp:post_id>101</wp:post_id>
        <wp:post_date><![CDATA[2026-09-20 08:00:00]]></wp:post_date>
        <wp:status><![CDATA[inherit]]></wp:status>
        <wp:post_type><![CDATA[attachment]]></wp:post_type>
        <wp:attachment_url><![CDATA[https://ppg.uinssc.ac.id/wp-content/uploads/2026/09/gedung-ppg.jpg]]></wp:attachment_url>
    </item>

    <!-- Post 1: with featured thumbnail -->
    <item>
        <title><![CDATA[Pemberitahuan Lapor Diri Mahasiswa PPG Batch 2]]></title>
        <link>https://ppg.uinssc.ac.id/pemberitahuan-lapor-diri-batch-2/</link>
        <dc:creator><![CDATA[admin]]></dc:creator>
        <content:encoded><![CDATA[<p>Berikut adalah pengumuman penting mengenai pelaksanaan lapor diri mahasiswa PPG Batch 2.</p>]]></content:encoded>
        <excerpt:encoded><![CDATA[Ringkasan pengumuman lapor diri PPG Batch 2.]]></excerpt:encoded>
        <wp:post_id>201</wp:post_id>
        <wp:post_date><![CDATA[2026-09-21 09:00:00]]></wp:post_date>
        <wp:status><![CDATA[publish]]></wp:status>
        <wp:post_type><![CDATA[post]]></wp:post_type>
        <category domain="category" nicename="pengumuman"><![CDATA[Pengumuman]]></category>
        <wp:postmeta>
            <wp:meta_key><![CDATA[_thumbnail_id]]></wp:meta_key>
            <wp:meta_value><![CDATA[101]]></wp:meta_value>
        </wp:postmeta>
    </item>

    <!-- Post 2: with inline image in content -->
    <item>
        <title><![CDATA[Alur Pendaftaran dan Verifikasi Berkas]]></title>
        <link>https://ppg.uinssc.ac.id/alur-pendaftaran/</link>
        <dc:creator><![CDATA[admin]]></dc:creator>
        <content:encoded><![CDATA[<p>Alur verifikasi berkas:</p><p><img src="https://ppg.uinssc.ac.id/wp-content/uploads/2026/09/diagram-alur.png" alt="Diagram Alur"/></p>]]></content:encoded>
        <wp:post_id>202</wp:post_id>
        <wp:post_date><![CDATA[2026-09-22 10:30:00]]></wp:post_date>
        <wp:status><![CDATA[publish]]></wp:status>
        <wp:post_type><![CDATA[post]]></wp:post_type>
        <category domain="category" nicename="akademik"><![CDATA[Akademik]]></category>
    </item>
</channel>
</rss>
XML;
    }

    public function test_wordpress_import_service_extracts_articles_and_saves_images_to_gallery()
    {
        $service = new WordPressImportService();
        $xml = $this->getSampleWordPressXml();

        $result = $service->import($xml, $this->admin->id, false);

        // Verify summary count
        $this->assertEquals(2, $result['articles_count']);
        $this->assertEquals(2, $result['galleries_count']);

        // Verify Articles created
        $this->assertDatabaseHas('articles', [
            'judul'    => 'Pemberitahuan Lapor Diri Mahasiswa PPG Batch 2',
            'kategori' => 'Pengumuman',
            'status'   => 'published',
        ]);

        $this->assertDatabaseHas('articles', [
            'judul'    => 'Alur Pendaftaran dan Verifikasi Berkas',
            'kategori' => 'Akademik',
            'status'   => 'published',
        ]);

        // Verify Galleries created from attachments & inline images
        $this->assertDatabaseHas('galleries', [
            'file_url' => 'https://ppg.uinssc.ac.id/wp-content/uploads/2026/09/gedung-ppg.jpg',
            'sumber'   => 'wordpress_import',
        ]);

        $this->assertDatabaseHas('galleries', [
            'file_url' => 'https://ppg.uinssc.ac.id/wp-content/uploads/2026/09/diagram-alur.png',
            'sumber'   => 'wordpress_import',
        ]);

        // Verify article thumbnail is mapped to local gallery file
        $article1 = Article::where('judul', 'Pemberitahuan Lapor Diri Mahasiswa PPG Batch 2')->first();
        $this->assertNotNull($article1->gambar);
        $this->assertFalse(str_starts_with($article1->gambar, 'http'));
        $this->assertStringContainsString('storage/gallery/', $article1->gambar_url);
    }

    public function test_admin_can_access_galeri_page()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.pengaturan.galeri'));
        $response->assertStatus(200);
        $response->assertSee('Galeri Media');
    }

    public function test_galeri_shows_wordpress_imported_images()
    {
        Gallery::create([
            'judul'     => 'Foto Ujian PPG 2026',
            'file_path' => 'https://example.com/ujian.jpg',
            'file_url'  => 'https://example.com/ujian.jpg',
            'kategori'  => 'WordPress Import',
            'sumber'    => 'wordpress_import',
        ]);

        $response = $this->actingAs($this->admin)->get(route('admin.pengaturan.galeri'));
        $response->assertStatus(200);
        $response->assertSee('Foto Ujian PPG 2026');
        $response->assertSee('WP Import');
    }

    public function test_sidebar_contains_galeri_media_link()
    {
        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Galeri Media');
    }

    public function test_manajemen_artikel_livewire_can_import_xml()
    {
        $xmlContent = $this->getSampleWordPressXml();
        $tempFile = UploadedFile::fake()->createWithContent('export.xml', $xmlContent);

        Livewire::actingAs($this->admin)
            ->test(\App\Livewire\Admin\ManajemenArtikel::class)
            ->set('xml_file', $tempFile)
            ->set('download_images', false)
            ->call('processImport', new WordPressImportService())
            ->assertHasNoErrors()
            ->assertSee('Import selesai');

        $this->assertEquals(2, Article::count());
        $this->assertEquals(2, Gallery::count());
    }

    public function test_importing_same_xml_twice_does_not_create_duplicates()
    {
        $service = new WordPressImportService();
        $xml = $this->getSampleWordPressXml();

        // 1st import
        $res1 = $service->import($xml, $this->admin->id, false);
        $this->assertEquals(2, $res1['articles_count']);
        $this->assertEquals(2, $res1['galleries_count']);
        $this->assertEquals(2, Article::count());
        $this->assertEquals(2, Gallery::count());

        // 2nd import (identical file)
        $res2 = $service->import($xml, $this->admin->id, false);
        // Articles updated instead of created new duplicates
        $this->assertEquals(0, $res2['articles_count']);
        $this->assertEquals(2, $res2['articles_updated']);
        $this->assertEquals(0, $res2['galleries_count']);
        $this->assertEquals(2, $res2['galleries_existing']);

        // TOTAL ARTICLES AND GALLERIES MUST STILL BE EXACTLY 2!
        $this->assertEquals(2, Article::count(), 'Articles count must not increase on duplicate import');
        $this->assertEquals(2, Gallery::count(), 'Galleries count must not increase on duplicate import');
    }

    public function test_clean_duplicates_artisan_command()
    {
        $this->artisan('ppg:clean-duplicates')
            ->expectsOutputToContain('Memulai pembersihan duplikasi')
            ->assertExitCode(0);
    }
}
