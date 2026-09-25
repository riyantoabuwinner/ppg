<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Gallery;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleXMLElement;

class WordPressImportService
{
    protected int $downloadedCount = 0;
    protected int $maxDownloads = 20;

    /**
     * Import WordPress export XML string or file path without any duplicates
     *
     * @param string $xmlContentOrPath XML string or file path
     * @param int $authorId Admin User ID
     * @param bool $downloadImages Whether to download remote images to local storage
     * @return array Import summary stats
     */
    public function import(string $xmlContentOrPath, int $authorId, bool $downloadImages = false): array
    {
        // Increase execution time and memory limits for large XML exports
        if (function_exists('set_time_limit')) {
            @set_time_limit(300);
        }
        @ini_set('max_execution_time', '300');
        @ini_set('memory_limit', '512M');

        $this->downloadedCount = 0;

        $xmlContent = file_exists($xmlContentOrPath) 
            ? file_get_contents($xmlContentOrPath) 
            : $xmlContentOrPath;

        // Clean XML if there's any invalid character
        $xmlContent = trim($xmlContent);

        // Suppress libxml errors and handle cleanly
        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($xmlContent, 'SimpleXMLElement', LIBXML_NOCDATA);

        if (!$xml) {
            $errors = libxml_get_errors();
            libxml_clear_errors();
            $msg = !empty($errors) ? $errors[0]->message : 'Format XML WordPress tidak valid.';
            throw new \Exception('Gagal memproses file XML: ' . trim($msg));
        }

        $namespaces = $xml->getNamespaces(true);
        $wpNs = $namespaces['wp'] ?? 'http://wordpress.org/export/1.2/';
        $contentNs = $namespaces['content'] ?? 'http://purl.org/rss/1.0/modules/content/';
        $excerptNs = $namespaces['excerpt'] ?? 'http://wordpress.org/export/1.2/excerpt/';

        $channel = $xml->channel;
        if (!$channel) {
            throw new \Exception('Tag <channel> tidak ditemukan pada file XML.');
        }

        $items = $channel->item ?? [];

        $attachmentsMap = []; // wp_post_id => Gallery model
        $importedGalleriesCount = 0;
        $existingGalleriesCount = 0;
        $importedArticlesCount = 0;
        $updatedArticlesCount = 0;
        $categoriesFound = [];

        // Ensure gallery directory exists in storage
        if ($downloadImages && !Storage::disk('public')->exists('gallery')) {
            Storage::disk('public')->makeDirectory('gallery');
        }

        // --- STEP 1: Process all media attachments first ---
        foreach ($items as $item) {
            $wp = $item->children($wpNs);
            $postType = (string)($wp->post_type ?? 'post');

            if ($postType === 'attachment') {
                $postId = (string)($wp->post_id ?? '');
                $attachmentUrl = (string)($wp->attachment_url ?? '');
                if (empty($attachmentUrl)) {
                    $attachmentUrl = (string)($item->guid ?? '');
                }

                if (!empty($attachmentUrl)) {
                    $title = (string)($item->title ?? pathinfo($attachmentUrl, PATHINFO_FILENAME));
                    $isNew = false;
                    $gallery = $this->saveImageToGallery(
                        $attachmentUrl,
                        $title,
                        'WordPress Import',
                        $downloadImages,
                        $isNew
                    );

                    if ($gallery) {
                        $attachmentsMap[$postId] = $gallery;
                        if ($isNew) {
                            $importedGalleriesCount++;
                        } else {
                            $existingGalleriesCount++;
                        }
                    }
                }
            }
        }

        // --- STEP 2: Process all posts (articles) ---
        foreach ($items as $item) {
            $wp = $item->children($wpNs);
            $postType = (string)($wp->post_type ?? 'post');

            // Only process standard posts or articles
            if ($postType !== 'post') {
                continue;
            }

            $title = trim((string)($item->title ?? ''));
            if (empty($title)) {
                continue;
            }

            $contentElem = $item->children($contentNs);
            $content = (string)($contentElem->encoded ?? '');

            $excerptElem = $item->children($excerptNs);
            $excerpt = (string)($excerptElem->encoded ?? '');
            if (empty($excerpt)) {
                $excerpt = Str::limit(strip_tags($content), 150);
            }

            $postStatus = (string)($wp->status ?? 'publish');
            $status = in_array(strtolower($postStatus), ['publish', 'published']) ? 'published' : 'draft';

            $postDate = (string)($wp->post_date ?? '');
            $publishedAt = null;
            if (!empty($postDate) && $postDate !== '0000-00-00 00:00:00') {
                try {
                    $publishedAt = Carbon::parse($postDate);
                } catch (\Exception $e) {
                    $publishedAt = Carbon::now();
                }
            } else {
                $publishedAt = Carbon::now();
            }

            // Extract category
            $category = 'Berita';
            if (isset($item->category)) {
                foreach ($item->category as $cat) {
                    $domain = (string)$cat['domain'];
                    if ($domain === 'category') {
                        $catName = (string)$cat;
                        if (!empty($catName) && strtolower($catName) !== 'uncategorized' && strtolower($catName) !== 'tak berkategori') {
                            $category = $catName;
                            $categoriesFound[$category] = true;
                            break;
                        }
                    }
                }
            }

            // Find thumbnail from _thumbnail_id postmeta
            $thumbnailGallery = null;
            if (isset($wp->postmeta)) {
                foreach ($wp->postmeta as $meta) {
                    $key = (string)($meta->meta_key ?? '');
                    if ($key === '_thumbnail_id') {
                        $thumbId = (string)($meta->meta_value ?? '');
                        if (isset($attachmentsMap[$thumbId])) {
                            $thumbnailGallery = $attachmentsMap[$thumbId];
                            break;
                        }
                    }
                }
            }

            // Scan content for inline <img> tags to save into Gallery if not already saved
            $contentImages = $this->extractImagesFromHtml($content);
            $linkedGalleries = [];

            if ($thumbnailGallery) {
                $linkedGalleries[] = $thumbnailGallery;
            }

            foreach ($contentImages as $imgUrl) {
                $isNewImg = false;
                $imgGallery = $this->saveImageToGallery(
                    $imgUrl,
                    $title . ' - Gambar Konten',
                    'WordPress Import',
                    $downloadImages,
                    $isNewImg
                );

                if ($imgGallery) {
                    $linkedGalleries[] = $imgGallery;
                    if ($isNewImg) {
                        $importedGalleriesCount++;
                    } else {
                        $existingGalleriesCount++;
                    }

                    // If no thumbnail yet, use the first inline image as thumbnail!
                    if (!$thumbnailGallery) {
                        $thumbnailGallery = $imgGallery;
                    }
                }
            }

            // Replace all remote image URLs in content with the local gallery asset URLs
            foreach ($linkedGalleries as $gal) {
                if ($gal->file_url && $gal->file_path) {
                    $localUrl = asset('storage/' . $gal->file_path);
                    $content = str_replace($gal->file_url, $localUrl, $content);
                    $cleanUrl = strtok($gal->file_url, '?');
                    $content = str_replace($cleanUrl, $localUrl, $content);
                }
            }

            // Determine article thumbnail image string (local gallery path)
            $articleGambar = $thumbnailGallery ? $thumbnailGallery->file_path : null;

            // --- STRICT ANTI-DUPLICATION FOR ARTICLES ---
            $cleanTitle = trim(preg_replace('/\s+/', ' ', $title));
            $baseSlug = Str::slug($cleanTitle);

            // Check if an article with identical title or slug already exists
            $existingArticle = Article::whereRaw('LOWER(TRIM(judul)) = ?', [strtolower($cleanTitle)])
                ->orWhere('slug', $baseSlug)
                ->first();

            if ($existingArticle) {
                // Update existing article without creating duplicates
                $updateData = [
                    'excerpt'      => $excerpt,
                    'konten'       => $content,
                    'kategori'     => $category,
                    'status'       => $status,
                ];

                // If article doesn't have an image but we found one now, or had a remote image, update to local gallery
                if ($articleGambar) {
                    $updateData['gambar'] = $articleGambar;
                }

                if (!$existingArticle->published_at && $status === 'published') {
                    $updateData['published_at'] = $publishedAt;
                }

                $existingArticle->update($updateData);
                $article = $existingArticle;
                $updatedArticlesCount++;
            } else {
                // Generate unique slug only for genuinely new article
                $slug = $baseSlug;
                $counter = 1;
                while (Article::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }

                $article = Article::create([
                    'author_id'    => $authorId,
                    'judul'        => $cleanTitle,
                    'slug'         => $slug,
                    'excerpt'      => $excerpt,
                    'konten'       => $content,
                    'gambar'       => $articleGambar,
                    'kategori'     => $category,
                    'status'       => $status,
                    'published_at' => $status === 'published' ? $publishedAt : null,
                ]);
                $importedArticlesCount++;
            }

            // Link galleries to this article (if not already linked)
            foreach ($linkedGalleries as $gal) {
                if (!$gal->article_id) {
                    $gal->update(['article_id' => $article->id]);
                }
            }
        }

        return [
            'articles_count'    => $importedArticlesCount,
            'articles_updated'  => $updatedArticlesCount,
            'galleries_count'   => $importedGalleriesCount,
            'galleries_existing'=> $existingGalleriesCount,
            'categories'        => array_keys($categoriesFound),
        ];
    }

    /**
     * Download or save image entry into galleries table with strict duplicate detection
     */
    protected function saveImageToGallery(string $url, string $title, string $kategori, bool $downloadImages, bool &$isNew = false): ?Gallery
    {
        $isNew = false;
        $url = trim($url);
        if (empty($url) || !filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        // --- STRICT ANTI-DUPLICATION FOR GALLERIES ---
        // 1. Check exact URL or file_path match
        $existing = Gallery::where('file_url', $url)->orWhere('file_path', $url)->first();
        if ($existing) {
            return $existing;
        }

        // 2. Check by filename & base filename (handles domain changes & WP dimension suffixes)
        $cleanUrl = strtok($url, '?');
        $pathOnly = parse_url($cleanUrl, PHP_URL_PATH) ?: $cleanUrl;
        $filename = basename($pathOnly);
        $baseFilename = preg_replace('/-\d+x\d+(\.[a-zA-Z0-9]+)$/', '$1', $filename);

        if (!empty($filename)) {
            $existing = Gallery::where('file_url', 'like', '%' . $filename)
                ->orWhere('file_path', 'like', '%' . $filename)
                ->orWhere('file_url', 'like', '%' . $baseFilename)
                ->orWhere('file_path', 'like', '%' . $baseFilename)
                ->first();

            if ($existing) {
                return $existing;
            }
        }

        // Image is genuinely new, proceed to create
        $isNew = true;
        $cleanPath = parse_url($cleanUrl, PHP_URL_PATH) ?: $cleanUrl;
        $rawFilename = pathinfo($cleanPath, PATHINFO_FILENAME) ?: 'wp_img';
        $cleanFilename = Str::slug(substr($rawFilename, 0, 40));
        $ext = strtolower(pathinfo($cleanPath, PATHINFO_EXTENSION)) ?: 'jpg';
        if (strlen($ext) > 4) {
            $ext = 'jpg';
        }

        $fileName = 'gallery/wp_' . Str::random(8) . '_' . $cleanFilename . '.' . $ext;
        $storedPath = $fileName;
        $fileSize = 'N/A';
        $mimeType = 'image/' . ($ext === 'jpg' ? 'jpeg' : $ext);

        $downloadSucceeded = false;
        try {
            $response = Http::withoutVerifying()
                ->timeout(5)
                ->get($url);

            if ($response->successful()) {
                $content = $response->body();
                Storage::disk('public')->put($fileName, $content);
                $bytes = strlen($content);
                $fileSize = $this->formatBytes($bytes);
                $mimeType = $response->header('Content-Type') ?: 'image/' . $ext;
                $this->downloadedCount++;
                $downloadSucceeded = true;
            }
        } catch (\Throwable $e) {
            // failed to download remote image
        }

        if (!$downloadSucceeded) {
            // Generate fallback SVG so the file is 100% local in storage
            $svgPath = 'gallery/wp_' . Str::random(8) . '_' . $cleanFilename . '.svg';
            $this->generateFallbackSvg($title, $svgPath);
            $storedPath = $svgPath;
            $fileSize = '2 KB';
            $mimeType = 'image/svg+xml';
        }

        return Gallery::create([
            'judul'      => Str::limit($title, 150),
            'file_path'  => $storedPath,
            'file_url'   => $url,
            'kategori'   => $kategori,
            'sumber'     => 'wordpress_import',
            'ukuran'     => $fileSize,
            'mime_type'  => $mimeType,
        ]);
    }

    protected function generateFallbackSvg(string $title, string $localPath): void
    {
        $cleanTitle = htmlspecialchars(Str::limit($title, 40), ENT_QUOTES, 'UTF-8');
        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 450" width="800" height="450">
  <defs>
    <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:#4c1d95;stop-opacity:1" />
      <stop offset="50%" style="stop-color:#6d28d9;stop-opacity:1" />
      <stop offset="100%" style="stop-color:#312e81;stop-opacity:1" />
    </linearGradient>
  </defs>
  <rect width="100%" height="100%" fill="url(#grad)" />
  <circle cx="400" cy="180" r="45" fill="rgba(255,255,255,0.15)" />
  <path d="M380 195 L400 165 L420 195 Z M375 200 L425 200" stroke="white" stroke-width="4" fill="none" stroke-linejoin="round" />
  <circle cx="390" cy="170" r="4" fill="white" />
  <text x="50%" y="265" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="22" font-weight="bold" fill="#ffffff" text-anchor="middle">{$cleanTitle}</text>
  <text x="50%" y="300" font-family="-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif" font-size="14" fill="#ddd6fe" text-anchor="middle">Portal Pendidikan Profesi Guru (PPG)</text>
</svg>
SVG;
        Storage::disk('public')->put($localPath, $svg);
    }

    /**
     * Extract image URLs from HTML content
     */
    protected function extractImagesFromHtml(string $html): array
    {
        $images = [];
        if (preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $html, $matches)) {
            foreach ($matches[1] as $src) {
                if (filter_var($src, FILTER_VALIDATE_URL)) {
                    $images[] = $src;
                }
            }
        }
        return array_unique($images);
    }

    /**
     * Human readable byte formatting
     */
    protected function formatBytes(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1) . ' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }
}
