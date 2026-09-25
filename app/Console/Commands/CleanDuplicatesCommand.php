<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use App\Models\Gallery;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CleanDuplicatesCommand extends Command
{
    protected $signature = 'ppg:clean-duplicates';
    protected $description = 'Bersihkan duplikasi berita/artikel dan gambar di galeri';

    public function handle()
    {
        $this->info('Memulai pembersihan duplikasi artikel dan galeri...');

        DB::beginTransaction();

        try {
            // 1. Articles Deduplication
            $allArticles = Article::orderBy('id', 'desc')->get();
            $articleKeepMap = [];
            $articleRedirectMap = [];
            $articleIdsToDelete = [];

            foreach ($allArticles as $art) {
                $norm = strtolower(trim(preg_replace('/\s+/', ' ', $art->judul)));
                if (!isset($articleKeepMap[$norm])) {
                    $articleKeepMap[$norm] = $art;
                } else {
                    $kept = $articleKeepMap[$norm];
                    if (!empty($art->gambar) && empty($kept->gambar)) {
                        $kept->update(['gambar' => $art->gambar]);
                    }
                    $articleRedirectMap[$art->id] = $kept->id;
                    $articleIdsToDelete[] = $art->id;
                }
            }

            // 2. Galleries Deduplication
            $allGalleries = Gallery::orderBy('id', 'asc')->get();
            $galleryKeepMap = [];
            $galleryIdsToDelete = [];

            foreach ($allGalleries as $gal) {
                $path = parse_url($gal->file_url ?: $gal->file_path, PHP_URL_PATH) ?: ($gal->file_url ?: $gal->file_path);
                $fn = basename($path);
                $clean = strtolower(preg_replace('/-\d+x\d+(\.[a-zA-Z0-9]+)$/', '$1', $fn));

                if (!isset($galleryKeepMap[$clean])) {
                    $galleryKeepMap[$clean] = $gal;
                } else {
                    $kept = $galleryKeepMap[$clean];
                    if (str_contains($gal->file_url, 'ppg.uinssc.ac.id') && !str_contains($kept->file_url, 'ppg.uinssc.ac.id')) {
                        $kept->update([
                            'file_url'  => $gal->file_url,
                            'file_path' => $gal->file_path,
                        ]);
                    }
                    if ($gal->article_id && !$kept->article_id) {
                        $kept->update(['article_id' => $gal->article_id]);
                    }
                    $galleryIdsToDelete[] = $gal->id;
                }
            }

            // Re-link galleries to kept articles
            foreach ($articleRedirectMap as $delArtId => $keptArtId) {
                Gallery::where('article_id', $delArtId)->update(['article_id' => $keptArtId]);
            }

            if (!empty($articleIdsToDelete)) {
                Article::whereIn('id', $articleIdsToDelete)->delete();
            }

            if (!empty($galleryIdsToDelete)) {
                Gallery::whereIn('id', $galleryIdsToDelete)->delete();
            }

            // Clean numbered slugs
            foreach (Article::all() as $art) {
                $baseSlug = Str::slug($art->judul);
                if ($art->slug !== $baseSlug) {
                    $exists = Article::where('slug', $baseSlug)->where('id', '!=', $art->id)->exists();
                    if (!$exists) {
                        $art->update(['slug' => $baseSlug]);
                    }
                }
            }

            DB::commit();

            $this->info("Berhasil! " . count($articleIdsToDelete) . " artikel duplikat dan " . count($galleryIdsToDelete) . " gambar duplikat telah dibersihkan.");
            $this->info("Total Artikel Unik: " . Article::count());
            $this->info("Total Gambar Galeri Unik: " . Gallery::count());

            return Command::SUCCESS;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Gagal: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
