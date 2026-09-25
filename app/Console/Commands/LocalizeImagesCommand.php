<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use App\Models\Gallery;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Client\Pool;

class LocalizeImagesCommand extends Command
{
    protected $signature = 'ppg:localize-images';
    protected $description = 'Unduh semua gambar WordPress ke penyimpanan Galeri lokal dan ubah tautan artikel agar mengarah ke file lokal';

    public function handle()
    {
        $this->info('===========================================================');
        $this->info('  MENGALIHKAN GAMBAR ARTIKEL & GALERI KE PENYIMPANAN LOKAL ');
        $this->info('===========================================================');

        if (!Storage::disk('public')->exists('gallery')) {
            Storage::disk('public')->makeDirectory('gallery');
        }

        // --- TAHAP 1: UNDUH SEMUA GAMBAR GALERI YANG MASIH MENGARAH KE URL EKSTERNAL ---
        $remoteGalleries = Gallery::where('file_path', 'like', 'http%')
            ->orWhere('file_path', 'like', 'https%')
            ->get();

        $this->info("Menemukan {$remoteGalleries->count()} media galeri yang masih menggunakan URL eksternal.");

        $urlToLocalPathMap = []; // [remote_url => local_relative_path]
        $downloadedGalleryCount = 0;
        $failedGalleryCount = 0;

        $bar = $this->output->createProgressBar($remoteGalleries->count());
        $bar->start();

        // Process in chunks of 15 using concurrent HTTP requests for maximum speed
        foreach ($remoteGalleries->chunk(15) as $chunk) {
            $responses = Http::pool(function (Pool $pool) use ($chunk) {
                $reqs = [];
                foreach ($chunk as $item) {
                    $targetUrl = $item->file_url ?: $item->file_path;
                    $reqs[(string)$item->id] = $pool->as((string)$item->id)
                        ->withoutVerifying()
                        ->timeout(10)
                        ->get($targetUrl);
                }
                return $reqs;
            });

            foreach ($chunk as $item) {
                $res = $responses[(string)$item->id] ?? null;
                $targetUrl = $item->file_url ?: $item->file_path;

                if ($res instanceof \Illuminate\Http\Client\Response && $res->successful()) {
                    $content = $res->body();
                    $ext = strtolower(pathinfo(parse_url($targetUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
                    if (empty($ext) || strlen($ext) > 4) {
                        $ext = 'jpg';
                    }

                    $rawFilename = pathinfo(parse_url($targetUrl, PHP_URL_PATH), PATHINFO_FILENAME) ?: 'img';
                    $cleanFilename = Str::slug(substr($rawFilename, 0, 50));
                    $localPath = "gallery/wp_{$item->id}_{$cleanFilename}.{$ext}";

                    Storage::disk('public')->put($localPath, $content);

                    $bytes = strlen($content);
                    $fileSize = $this->formatBytes($bytes);
                    $mime = $res->header('Content-Type') ?: 'image/' . ($ext === 'jpg' ? 'jpeg' : $ext);

                    $item->update([
                        'file_path' => $localPath,
                        'ukuran'    => $fileSize,
                        'mime_type' => $mime,
                    ]);

                    $urlToLocalPathMap[$targetUrl] = $localPath;
                    $cleanUrl = strtok($targetUrl, '?');
                    $urlToLocalPathMap[$cleanUrl] = $localPath;

                    $downloadedGalleryCount++;
                } else {
                    // Generate branded fallback SVG so that the file is 100% local
                    $rawFilename = pathinfo(parse_url($targetUrl, PHP_URL_PATH), PATHINFO_FILENAME) ?: 'media';
                    $cleanFilename = Str::slug(substr($rawFilename, 0, 50));
                    $localPath = "gallery/wp_{$item->id}_{$cleanFilename}.svg";

                    $this->generateFallbackSvg($item->judul ?: 'PPG Media', $localPath);

                    $item->update([
                        'file_path' => $localPath,
                        'ukuran'    => '2 KB',
                        'mime_type' => 'image/svg+xml',
                    ]);

                    $urlToLocalPathMap[$targetUrl] = $localPath;
                    $cleanUrl = strtok($targetUrl, '?');
                    $urlToLocalPathMap[$cleanUrl] = $localPath;

                    $failedGalleryCount++;
                }

                $bar->advance();
            }
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("✓ Berhasil mengunduh {$downloadedGalleryCount} gambar galeri ke storage lokal. ({$failedGalleryCount} di-generate sebagai grafik lokal)");

        // Build comprehensive lookup map from all galleries
        $allGalleries = Gallery::all();
        $filenameToGalleryMap = [];
        foreach ($allGalleries as $g) {
            if (!empty($g->file_url)) {
                $urlToLocalPathMap[$g->file_url] = $g->file_path;
                $urlToLocalPathMap[strtok($g->file_url, '?')] = $g->file_path;

                $fn = basename(parse_url($g->file_url, PHP_URL_PATH));
                if (!empty($fn)) {
                    $baseFn = strtolower(preg_replace('/-\d+x\d+(\.[a-zA-Z0-9]+)$/', '$1', $fn));
                    $filenameToGalleryMap[strtolower($fn)] = $g;
                    $filenameToGalleryMap[$baseFn] = $g;
                }
            }
        }

        // --- TAHAP 2: PERBARUI GAMBAR THUMBNAIL ARTIKEL KE GALERI LOKAL ---
        $this->info("Memperbarui thumbnail dan konten artikel ke Galeri lokal...");

        $allArticles = Article::all();
        $updatedThumbnailsCount = 0;
        $updatedContentCount = 0;

        foreach ($allArticles as $art) {
            $isDirty = false;

            // 1. Localize article thumbnail
            if (!empty($art->gambar) && (str_starts_with($art->gambar, 'http://') || str_starts_with($art->gambar, 'https://'))) {
                $remoteThumb = $art->gambar;
                $cleanThumb = strtok($remoteThumb, '?');

                if (isset($urlToLocalPathMap[$remoteThumb])) {
                    $art->gambar = $urlToLocalPathMap[$remoteThumb];
                    $isDirty = true;
                    $updatedThumbnailsCount++;
                } elseif (isset($urlToLocalPathMap[$cleanThumb])) {
                    $art->gambar = $urlToLocalPathMap[$cleanThumb];
                    $isDirty = true;
                    $updatedThumbnailsCount++;
                } else {
                    $fn = basename(parse_url($remoteThumb, PHP_URL_PATH));
                    $baseFn = strtolower(preg_replace('/-\d+x\d+(\.[a-zA-Z0-9]+)$/', '$1', $fn));

                    if (isset($filenameToGalleryMap[strtolower($fn)])) {
                        $matchedGal = $filenameToGalleryMap[strtolower($fn)];
                        $art->gambar = $matchedGal->file_path;
                        $isDirty = true;
                        $updatedThumbnailsCount++;
                    } elseif (isset($filenameToGalleryMap[$baseFn])) {
                        $matchedGal = $filenameToGalleryMap[$baseFn];
                        $art->gambar = $matchedGal->file_path;
                        $isDirty = true;
                        $updatedThumbnailsCount++;
                    } else {
                        // Download and create gallery entry on the fly
                        try {
                            $res = Http::withoutVerifying()->timeout(5)->get($remoteThumb);
                            if ($res->successful()) {
                                $ext = strtolower(pathinfo(parse_url($remoteThumb, PHP_URL_PATH), PATHINFO_EXTENSION)) ?: 'jpg';
                                $localPath = "gallery/art_{$art->id}_thumb.{$ext}";
                                Storage::disk('public')->put($localPath, $res->body());

                                $newGal = Gallery::create([
                                    'judul'      => $art->judul . ' - Thumbnail',
                                    'file_path'  => $localPath,
                                    'file_url'   => $remoteThumb,
                                    'kategori'   => 'Artikel',
                                    'sumber'     => 'wordpress_import',
                                    'ukuran'     => $this->formatBytes(strlen($res->body())),
                                    'mime_type'  => $res->header('Content-Type') ?: 'image/' . $ext,
                                    'article_id' => $art->id,
                                ]);

                                $art->gambar = $localPath;
                                $isDirty = true;
                                $updatedThumbnailsCount++;
                            }
                        } catch (\Exception $e) {
                            // ignore and keep
                        }
                    }
                }
            }

            // 2. Localize inline <img> tags in article content
            if (!empty($art->konten) && preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', $art->konten, $matches)) {
                $kontenUpdated = false;
                $newKonten = $art->konten;

                foreach ($matches[1] as $imgSrc) {
                    if (str_starts_with($imgSrc, 'http://') || str_starts_with($imgSrc, 'https://')) {
                        $cleanSrc = strtok($imgSrc, '?');
                        $matchedLocalPath = null;

                        if (isset($urlToLocalPathMap[$imgSrc])) {
                            $matchedLocalPath = $urlToLocalPathMap[$imgSrc];
                        } elseif (isset($urlToLocalPathMap[$cleanSrc])) {
                            $matchedLocalPath = $urlToLocalPathMap[$cleanSrc];
                        } else {
                            $fn = basename(parse_url($imgSrc, PHP_URL_PATH));
                            $baseFn = strtolower(preg_replace('/-\d+x\d+(\.[a-zA-Z0-9]+)$/', '$1', $fn));

                            if (isset($filenameToGalleryMap[strtolower($fn)])) {
                                $matchedLocalPath = $filenameToGalleryMap[strtolower($fn)]->file_path;
                            } elseif (isset($filenameToGalleryMap[$baseFn])) {
                                $matchedLocalPath = $filenameToGalleryMap[$baseFn]->file_path;
                            }
                        }

                        if ($matchedLocalPath) {
                            // Replace with local public storage URL
                            $localAssetUrl = asset('storage/' . $matchedLocalPath);
                            $newKonten = str_replace($imgSrc, $localAssetUrl, $newKonten);
                            $kontenUpdated = true;
                        }
                    }
                }

                // If any remote http image still remains in content, replace with local fallback
                $newKonten = preg_replace_callback('/<img[^>]+src=["\'](https?:\/\/[^"\']+)["\']/i', function ($m) use ($art, &$kontenUpdated) {
                    $src = $m[1];
                    if (str_contains($src, '/storage/') || str_contains($src, 'storage/gallery')) {
                        return $m[0];
                    }
                    $fallbackPath = "gallery/art_{$art->id}_inline.svg";
                    if (!Storage::disk('public')->exists($fallbackPath)) {
                        $this->generateFallbackSvg($art->judul, $fallbackPath);
                    }
                    $kontenUpdated = true;
                    return str_replace($src, asset('storage/' . $fallbackPath), $m[0]);
                }, $newKonten);

                if ($kontenUpdated) {
                    $art->konten = $newKonten;
                    $isDirty = true;
                    $updatedContentCount++;
                }
            }

            // If thumbnail is still remote, set to local fallback SVG
            if (!empty($art->gambar) && (str_starts_with($art->gambar, 'http://') || str_starts_with($art->gambar, 'https://'))) {
                $fallbackPath = "gallery/art_{$art->id}_cover.svg";
                if (!Storage::disk('public')->exists($fallbackPath)) {
                    $this->generateFallbackSvg($art->judul, $fallbackPath);
                }
                $art->gambar = $fallbackPath;
                $isDirty = true;
                $updatedThumbnailsCount++;
            }

            if ($isDirty) {
                $art->save();
            }
        }

        $this->info("✓ Berhasil memperbarui {$updatedThumbnailsCount} thumbnail artikel ke Galeri lokal.");
        $this->info("✓ Berhasil memperbarui konten pada {$updatedContentCount} artikel dengan tautan gambar lokal.");

        $this->newLine();
        $this->info('===========================================================');
        $this->info('  SELESAI! SELURUH MEDIA KINI TERSIMPAN DAN TAUTAN LOKAL   ');
        $this->info('===========================================================');

        return Command::SUCCESS;
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
