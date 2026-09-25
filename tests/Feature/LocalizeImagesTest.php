<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Article;
use App\Models\Gallery;
use App\Services\WordPressImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class LocalizeImagesTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'username' => 'admin_localize_test',
        ]);

        Storage::fake('public');
    }

    public function test_localize_images_artisan_command_converts_remote_to_local()
    {
        Http::fake([
            'http://example.com/wp-content/uploads/photo1.jpg' => Http::response('fake image 1 content', 200, ['Content-Type' => 'image/jpeg']),
            'http://example.com/wp-content/uploads/photo2.jpg' => Http::response('fake image 2 content', 200, ['Content-Type' => 'image/jpeg']),
            '*' => Http::response('', 404),
        ]);

        // 1. Create a remote gallery entry
        $gallery = Gallery::create([
            'judul'     => 'Foto Kegiatan Uji',
            'file_path' => 'http://example.com/wp-content/uploads/photo1.jpg',
            'file_url'  => 'http://example.com/wp-content/uploads/photo1.jpg',
            'sumber'    => 'wordpress_import',
        ]);

        // 2. Create an article with remote thumbnail and inline content image
        $article = Article::create([
            'author_id' => $this->admin->id,
            'judul'     => 'Berita Uji Tautan Lokal',
            'slug'      => 'berita-uji-tautan-lokal',
            'gambar'    => 'http://example.com/wp-content/uploads/photo1.jpg',
            'konten'    => '<p>Dokumentasi: <img src="http://example.com/wp-content/uploads/photo1.jpg" alt="test"></p>',
            'status'    => 'published',
        ]);

        $this->artisan('ppg:localize-images')
            ->assertExitCode(0);

        // Verify Gallery is now local
        $gallery->refresh();
        $this->assertFalse(str_starts_with($gallery->file_path, 'http'));
        $this->assertTrue(str_starts_with($gallery->file_path, 'gallery/wp_'));
        Storage::disk('public')->assertExists($gallery->file_path);

        // Verify Article thumbnail is now local
        $article->refresh();
        $this->assertFalse(str_starts_with($article->gambar, 'http'));
        $this->assertEquals($gallery->file_path, $article->gambar);
        $this->assertStringContainsString('storage/' . $gallery->file_path, $article->gambar_url);

        // Verify Article content inline image is now local asset URL
        $this->assertStringNotContainsString('http://example.com', $article->konten);
        $this->assertStringContainsString('storage/' . $gallery->file_path, $article->konten);
    }

    public function test_wordpress_import_stores_images_locally_and_replaces_content_links()
    {
        Http::fake([
            'https://ppg.uinssc.ac.id/wp-content/uploads/gedung.jpg' => Http::response('fake gedung image', 200, ['Content-Type' => 'image/jpeg']),
            'https://ppg.uinssc.ac.id/wp-content/uploads/kegiatan.jpg' => Http::response('fake kegiatan image', 200, ['Content-Type' => 'image/jpeg']),
        ]);

        $xml = <<<XML
<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0"
    xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:wp="http://wordpress.org/export/1.2/"
>
<channel>
    <title>Portal PPG</title>
    <link>https://ppg.uinssc.ac.id</link>

    <item>
        <title>Foto Gedung</title>
        <dc:creator>admin</dc:creator>
        <wp:post_id>55</wp:post_id>
        <wp:post_type>attachment</wp:post_type>
        <wp:attachment_url>https://ppg.uinssc.ac.id/wp-content/uploads/gedung.jpg</wp:attachment_url>
    </item>

    <item>
        <title>Berita Peresmian Gedung Baru</title>
        <dc:creator>admin</dc:creator>
        <wp:post_type>post</wp:post_type>
        <wp:status>publish</wp:status>
        <wp:postmeta>
            <wp:meta_key>_thumbnail_id</wp:meta_key>
            <wp:meta_value>55</wp:meta_value>
        </wp:postmeta>
        <content:encoded><![CDATA[<p>Berikut suasana: <img src="https://ppg.uinssc.ac.id/wp-content/uploads/kegiatan.jpg" alt="kegiatan"></p>]]></content:encoded>
    </item>
</channel>
</rss>
XML;

        $service = new WordPressImportService();
        $result = $service->import($xml, $this->admin->id, true);

        $this->assertEquals(1, $result['articles_count']);

        $article = Article::where('judul', 'Berita Peresmian Gedung Baru')->first();
        $this->assertNotNull($article);

        // Article thumbnail must be local storage path (NOT remote https://)
        $this->assertFalse(str_starts_with($article->gambar, 'http'));
        $this->assertTrue(str_starts_with($article->gambar, 'gallery/'));
        Storage::disk('public')->assertExists($article->gambar);

        // Article content must not have remote URL
        $this->assertStringNotContainsString('https://ppg.uinssc.ac.id/wp-content/uploads/kegiatan.jpg', $article->konten);
        $this->assertStringContainsString('storage/gallery/', $article->konten);

        // Galleries must have local paths
        $galleries = Gallery::all();
        foreach ($galleries as $gal) {
            $this->assertFalse(str_starts_with($gal->file_path, 'http'));
            $this->assertTrue(str_starts_with($gal->file_path, 'gallery/'));
            Storage::disk('public')->assertExists($gal->file_path);
        }
    }
}
