<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Announcement;
use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ===== USERS =====
        $admin = User::firstOrCreate(
            ['username' => 'admin'],
            [
                'name'             => 'Administrator',
                'email'            => 'admin@uinsiber.ac.id',
                'password'         => Hash::make('password'),
                'role'             => 'admin',
                'status_lapor_diri'=> 'draft',
            ]
        );

        $subagyo = User::firstOrCreate(
            ['username' => '2309110001'],
            [
                'name'             => 'Ahmad Subagyo',
                'email'            => 'ahmad@mhs.uinsiber.ac.id',
                'password'         => Hash::make('1234567890'),
                'role'             => 'mahasiswa',
                'status_lapor_diri'=> 'draft',
            ]
        );

        $subagyoProfile = \App\Models\StudentProfile::firstOrCreate(
            ['user_id' => $subagyo->id],
            [
                'nim'               => '2309110001',
                'nama'              => 'Ahmad Subagyo',
                'nik'               => '3209110001000001',
                'no_tes'            => 'PPG-2025-0001',
                'nama_tempat_lahir' => 'Cirebon',
                'tanggal_lahir'     => '1998-05-14',
                'jenis_kelamin'     => 'Laki-laki',
                'agama'             => 'Islam',
                'alamat'            => 'Jl. Perjuangan Sunyaragi No. 45',
                'no_hp'             => '081234567890',
                'email'             => 'ahmad@mhs.uinsiber.ac.id',
                'jalur_pendaftaran' => 'Reguler',
                'gelombang'         => 'Gelombang 1',
                'tahun_masuk'       => '2025',
            ]
        );

        \App\Models\StudentEducation::firstOrCreate(
            ['student_profile_id' => $subagyoProfile->id],
            [
                'asal_perguruan_tinggi' => 'UIN Siber Syekh Nurjati Cirebon',
                'asal_program_studi'    => 'Pendidikan Agama Islam',
            ]
        );


        // ===== ANNOUNCEMENTS =====
        if (Announcement::count() === 0) {
            Announcement::insert([
                [
                    'judul'           => 'Pendaftaran Lapor Diri PPG Gelombang 1 Tahun 2025 Dibuka',
                    'isi'             => 'Mahasiswa baru PPG wajib melakukan lapor diri secara online melalui portal ini paling lambat tanggal 15 Oktober 2025. Pastikan semua dokumen disiapkan sebelum mengisi formulir.',
                    'tipe'            => 'penting',
                    'tanggal_mulai'   => now()->format('Y-m-d'),
                    'tanggal_selesai' => now()->addDays(30)->format('Y-m-d'),
                    'is_active'       => 1,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ],
                [
                    'judul'           => 'Pastikan File Dokumen Anda Sesuai Format',
                    'isi'             => 'Dokumen yang diunggah harus berformat PDF atau JPG dengan ukuran maksimal 2MB. Scan KTP dan Ijazah harus jelas dan terbaca.',
                    'tipe'            => 'info',
                    'tanggal_mulai'   => now()->format('Y-m-d'),
                    'tanggal_selesai' => null,
                    'is_active'       => 1,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ],
            ]);
        }

        // ===== ARTICLES =====
        if (Article::count() === 0) {
            $artikels = [
                [
                    'judul'        => 'Selamat Datang Mahasiswa Baru PPG UIN Siber Syekh Nurjati Cirebon Tahun 2025',
                    'kategori'     => 'Berita',
                    'excerpt'      => 'Universitas Islam Negeri Siber Syekh Nurjati Cirebon menyambut hangat seluruh mahasiswa baru Program Pendidikan Profesi Guru (PPG) tahun akademik 2025.',
                    'konten'       => '<p>Universitas Islam Negeri Siber Syekh Nurjati Cirebon (UINSSC) dengan bangga menyambut seluruh mahasiswa baru Program Pendidikan Profesi Guru (PPG) tahun akademik 2025.</p><p>PPG merupakan program studi profesi yang bertujuan menghasilkan guru yang profesional, kompeten, dan berkarakter Islami. Sebagai calon pendidik masa depan, mahasiswa PPG diharapkan memiliki komitmen tinggi dalam menjalani proses pendidikan ini.</p><p>Seluruh mahasiswa diwajibkan untuk segera melengkapi proses lapor diri secara online melalui portal ini. Proses lapor diri merupakan syarat awal yang harus diselesaikan sebelum mengikuti kegiatan akademik.</p>',
                    'status'       => 'published',
                    'published_at' => now()->subDays(2),
                ],
                [
                    'judul'        => 'Panduan Lengkap Pengisian Formulir Lapor Diri PPG Online',
                    'kategori'     => 'Akademik',
                    'excerpt'      => 'Berikut adalah panduan langkah demi langkah pengisian formulir lapor diri secara online bagi mahasiswa baru PPG UIN Siber Syekh Nurjati Cirebon.',
                    'konten'       => '<p>Pengisian formulir lapor diri PPG dapat dilakukan secara online melalui portal ini. Berikut adalah panduan lengkapnya:</p><ol><li><strong>Login</strong> menggunakan NIM dan NIK Anda</li><li>Isi <strong>Data Pribadi</strong> dengan lengkap dan benar</li><li>Lengkapi <strong>Data Domisili</strong> sesuai KTP</li><li>Isi <strong>Riwayat Pendidikan</strong> terakhir</li><li>Lengkapi <strong>Data Keluarga</strong> (ayah dan ibu)</li><li>Isi <strong>Data Ekonomi</strong> dan pembiayaan</li><li>Unggah <strong>Dokumen Pendukung</strong> yang diperlukan</li></ol><p>Pastikan semua data yang diisikan sudah benar sebelum dikunci. Data yang sudah dikunci tidak dapat diubah kecuali meminta revisi kepada panitia.</p>',
                    'status'       => 'published',
                    'published_at' => now()->subDays(5),
                ],
                [
                    'judul'        => 'Daftar Dokumen Wajib yang Harus Disiapkan untuk Lapor Diri PPG',
                    'kategori'     => 'Akademik',
                    'excerpt'      => 'Sebelum mengisi formulir lapor diri, pastikan Anda telah menyiapkan seluruh dokumen yang diperlukan dalam format digital.',
                    'konten'       => '<p>Berikut adalah daftar dokumen wajib yang harus disiapkan dalam format digital (PDF/JPG, maksimal 2MB):</p><ul><li>Scan <strong>E-KTP</strong> yang masih berlaku</li><li>Scan <strong>Ijazah S1</strong> yang telah dilegalisasi</li><li>Scan <strong>Transkrip Nilai</strong> yang telah dilegalisasi</li><li><strong>Pakta Integritas</strong> yang telah ditandatangani</li><li><strong>Surat Keterangan Sehat</strong> dari dokter</li><li><strong>Link RPL</strong> (Rekognisi Pembelajaran Lampau) jika ada</li></ul>',
                    'status'       => 'published',
                    'published_at' => now()->subDays(7),
                ],
            ];

            foreach ($artikels as $a) {
                Article::create(array_merge($a, [
                    'author_id' => $admin->id,
                    'slug'      => Str::slug($a['judul']) . '-' . Str::random(4),
                ]));
            }
        }

        // ===== MENUS =====
        $this->call(MenuSeeder::class);
    }
}
