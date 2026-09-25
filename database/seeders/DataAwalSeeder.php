<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;
use App\Models\Partner;

class DataAwalSeeder extends Seeder
{
    public function run()
    {
        Testimonial::create(['name'=>'Ahmad Ridwan','subtitle'=>'Guru PAI, Jawa Barat','type'=>'video','content'=>'Sistem pembelajaran jarak jauh sangat fleksibel, responsif, dan sangat membantu guru di pelosok daerah.','media_url'=>'https://youtube.com','rating'=>5]);
        Testimonial::create(['name'=>'Siti Aminah','subtitle'=>'Guru MI, Jawa Tengah','type'=>'text','content'=>'Layanan Lapor Diri sangat memudahkan kami. Tidak perlu datang fisik, pemberkasan rapi, dan admin sangat membimbing setiap ada kendala.','rating'=>5]);
        Testimonial::create(['name'=>'Budi Santoso','subtitle'=>'Guru MA, Sumatera Selatan','type'=>'text','content'=>'Platform ini luar biasa canggih. Proses dari sinkronisasi data hingga cetak formulir semuanya serba otomatis. Sangat efisien!','rating'=>5]);
        
        Partner::create(['name'=>'Kemenag RI','logo'=>'']);
        Partner::create(['name'=>'Kemdikbudristek','logo'=>'']);
        Partner::create(['name'=>'LPDP','logo'=>'']);
        Partner::create(['name'=>'LPTK NASIONAL','logo'=>'']);
        Partner::create(['name'=>'Panitia Nasional PPG','logo'=>'']);
    }
}
