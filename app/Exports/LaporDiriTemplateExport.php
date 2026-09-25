<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LaporDiriTemplateExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return new Collection([
            [
                '2409110001',
                'Budi Santoso',
                '3209123456780001',
                'PPG-2025-001',
                'Cirebon',
                '1998-05-14',
                'Laki-laki',
                'Islam',
                'Jl. Pemuda No. 45',
                '001',
                '002',
                'Dusun Manis',
                'Sunyaragi',
                'Kesambi',
                '45132',
                '081234567890',
                'budi.santoso@example.com',
                'UIN Siber Syekh Nurjati Cirebon',
                'Pendidikan Agama Islam',
                'Reguler',
                'Gelombang 1',
                '2025',
                'verified',
                '3209123456780002',
                'Santoso',
                'PNS / Guru',
                'Rp 3.000.000 - Rp 5.000.000',
                '3209123456780003',
                'Siti Aminah',
                'Ibu Rumah Tangga',
                '< Rp 1.000.000',
            ],
            [
                '2409110002',
                'Dewi Lestari',
                '3209123456780004',
                'PPG-2025-002',
                'Kuningan',
                '1999-08-22',
                'Perempuan',
                'Islam',
                'Jl. Siliwangi No. 12',
                '003',
                '001',
                'Dusun Pahing',
                'Ciporang',
                'Kuningan',
                '45514',
                '089876543210',
                'dewi.lestari@example.com',
                'Universitas Kuningan',
                'Pendidikan Bahasa Inggris',
                'Daljab',
                'Gelombang 1',
                '2025',
                'submitted',
                '3209123456780005',
                'Rahmat Hidayat',
                'Wiraswasta',
                'Rp 2.000.000 - Rp 3.000.000',
                '3209123456780006',
                'Nurjanah',
                'PNS',
                'Rp 3.000.000 - Rp 5.000.000',
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'NIM',
            'Nama Lengkap',
            'NIK',
            'No Tes',
            'Tempat Lahir',
            'Tanggal Lahir (YYYY-MM-DD)',
            'Jenis Kelamin',
            'Agama',
            'Alamat',
            'RT',
            'RW',
            'Dusun',
            'Kelurahan',
            'Kecamatan',
            'Kode Pos',
            'No HP',
            'Email',
            'Asal Perguruan Tinggi',
            'Asal Program Studi',
            'Jalur Pendaftaran',
            'Gelombang',
            'Tahun Masuk',
            'Status Lapor Diri',
            'NIK Ayah',
            'Nama Ayah',
            'Pekerjaan Ayah',
            'Penghasilan Ayah',
            'NIK Ibu',
            'Nama Ibu',
            'Pekerjaan Ibu',
            'Penghasilan Ibu',
        ];
    }
}
