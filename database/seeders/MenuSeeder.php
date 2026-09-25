<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Category;
use App\Models\Page;
use App\Models\User;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan kategori dasar tersedia
        $catBerita = Category::firstOrCreate(
            ['slug' => 'berita'],
            ['name' => 'Berita', 'description' => 'Kategori berita umum seputar kegiatan PPG']
        );

        $catAkademik = Category::firstOrCreate(
            ['slug' => 'akademik'],
            ['name' => 'Akademik', 'description' => 'Kategori informasi kurikulum, pembelajaran, dan kelulusan']
        );

        // 2. Pastikan minimal ada 1 Page statis contoh
        $admin = User::where('role', 'admin')->first();
        $authorId = $admin ? $admin->id : 1;

        $page = Page::firstOrCreate(
            ['slug' => 'profil-ppg'],
            [
                'author_id'        => $authorId,
                'judul'            => 'Profil Program Studi PPG',
                'konten'           => '<h3>Profil Program Studi PPG UIN Siber Syekh Nurjati Cirebon</h3><p>Program Pendidikan Profesi Guru (PPG) UIN Siber Syekh Nurjati Cirebon menyelenggarakan layanan pendidikan profesi guru yang profesional, inklusif, fleksibel, dan berkualitas berbasis teknologi siber terintegrasi.</p>',
                'status'           => 'published',
                'meta_description' => 'Profil resmi program studi PPG UIN Siber Syekh Nurjati Cirebon',
                'tampil_di_menu'   => true,
            ]
        );

        // 3. MENU UTAMA (MAIN NAVBAR)
        $mainMenu = Menu::firstOrCreate(
            ['location' => 'main'],
            ['name' => 'Menu Header Utama']
        );

        if ($mainMenu->items()->count() === 0) {
            // Beranda
            MenuItem::create([
                'menu_id'      => $mainMenu->id,
                'title'        => 'Beranda',
                'type'         => 'module',
                'url'          => '/',
                'target'       => '_self',
                'icon'         => 'fas fa-house',
                'order_column' => 0,
                'is_active'    => true,
            ]);

            // Profil & Panduan (Parent dengan Sub-Menu)
            $profilMenu = MenuItem::create([
                'menu_id'      => $mainMenu->id,
                'title'        => 'Profil & Informasi',
                'type'         => 'custom_link',
                'url'          => '#',
                'target'       => '_self',
                'icon'         => 'fas fa-info-circle',
                'order_column' => 1,
                'is_active'    => true,
            ]);

            // Sub-item 1: Halaman Profil PPG
            MenuItem::create([
                'menu_id'      => $mainMenu->id,
                'parent_id'    => $profilMenu->id,
                'title'        => 'Profil Program PPG',
                'type'         => 'page',
                'reference_id' => $page->id,
                'url'          => '/halaman/' . $page->slug,
                'target'       => '_self',
                'icon'         => 'fas fa-graduation-cap',
                'order_column' => 0,
                'is_active'    => true,
            ]);

            // Sub-item 2: Galeri Dokumentasi
            MenuItem::create([
                'menu_id'      => $mainMenu->id,
                'parent_id'    => $profilMenu->id,
                'title'        => 'Galeri Kegiatan',
                'type'         => 'module',
                'url'          => '/admin/galeri',
                'target'       => '_self',
                'icon'         => 'fas fa-images',
                'order_column' => 1,
                'is_active'    => true,
            ]);

            // Berita & Kegiatan (Parent dengan Sub-Menu)
            $beritaMenu = MenuItem::create([
                'menu_id'      => $mainMenu->id,
                'title'        => 'Berita & Kegiatan',
                'type'         => 'module',
                'url'          => '/berita',
                'target'       => '_self',
                'icon'         => 'fas fa-newspaper',
                'order_column' => 2,
                'is_active'    => true,
            ]);

            // Sub-item Berita Terkini
            MenuItem::create([
                'menu_id'      => $mainMenu->id,
                'parent_id'    => $beritaMenu->id,
                'title'        => 'Berita Terkini',
                'type'         => 'category',
                'reference_id' => $catBerita->id,
                'url'          => '/berita?kategori=Berita',
                'target'       => '_self',
                'icon'         => 'fas fa-newspaper',
                'order_column' => 0,
                'is_active'    => true,
            ]);

            // Sub-item Informasi Akademik
            MenuItem::create([
                'menu_id'      => $mainMenu->id,
                'parent_id'    => $beritaMenu->id,
                'title'        => 'Info Akademik',
                'type'         => 'category',
                'reference_id' => $catAkademik->id,
                'url'          => '/berita?kategori=Akademik',
                'target'       => '_self',
                'icon'         => 'fas fa-book',
                'order_column' => 1,
                'is_active'    => true,
            ]);

            // Helpdesk & Bantuan
            MenuItem::create([
                'menu_id'      => $mainMenu->id,
                'title'        => 'Helpdesk',
                'type'         => 'module',
                'url'          => '/mahasiswa/helpdesk',
                'target'       => '_self',
                'icon'         => 'fas fa-headset',
                'order_column' => 3,
                'is_active'    => true,
            ]);
        }

        // 4. MENU ATAS (TOP BAR)
        $topMenu = Menu::firstOrCreate(
            ['location' => 'top'],
            ['name' => 'Menu Atas (Top Utility Bar)']
        );

        if ($topMenu->items()->count() === 0) {
            MenuItem::create([
                'menu_id'      => $topMenu->id,
                'title'        => 'UINSSC',
                'type'         => 'custom_link',
                'url'          => 'https://uinssc.ac.id',
                'target'       => '_blank',
                'icon'         => 'fas fa-building-columns',
                'order_column' => 0,
                'is_active'    => true,
            ]);

            MenuItem::create([
                'menu_id'      => $topMenu->id,
                'title'        => 'PPID UINSSC',
                'type'         => 'custom_link',
                'url'          => 'https://ppid.uinssc.ac.id',
                'target'       => '_blank',
                'icon'         => 'fas fa-shield-alt',
                'order_column' => 1,
                'is_active'    => true,
            ]);
        }

        // 5. MENU FOOTER (FOOTER BAR)
        $footerMenu = Menu::firstOrCreate(
            ['location' => 'footer'],
            ['name' => 'Menu Footer (Portal Utama)']
        );

        if ($footerMenu->items()->count() === 0) {
            $footerLinks = [
                ['title' => 'Beranda Utama', 'type' => 'module', 'url' => '/', 'target' => '_self'],
                ['title' => 'Berita & Kegiatan', 'type' => 'module', 'url' => '/berita', 'target' => '_self'],
                ['title' => 'Portal Lapor Diri Mahasiswa', 'type' => 'module', 'url' => '/login', 'target' => '_self'],
                ['title' => 'Website Resmi UINSSC ↗', 'type' => 'custom_link', 'url' => 'https://uinssc.ac.id', 'target' => '_blank'],
                ['title' => 'PPID UINSSC ↗', 'type' => 'custom_link', 'url' => 'https://ppid.uinssc.ac.id', 'target' => '_blank'],
            ];

            foreach ($footerLinks as $idx => $link) {
                MenuItem::create([
                    'menu_id'      => $footerMenu->id,
                    'title'        => $link['title'],
                    'type'         => $link['type'],
                    'url'          => $link['url'],
                    'target'       => $link['target'],
                    'order_column' => $idx,
                    'is_active'    => true,
                ]);
            }
        }
    }
}
