<?php

namespace App\Exports;

use App\Models\StudentProfile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SiakadExport implements FromCollection, WithMapping, WithHeadings
{
    public function collection()
    {
        return StudentProfile::with(['education', 'families', 'user'])->get();
    }

    public function map($student): array
    {
        $edu = $student->education;
        // Pad array dengan spasi kosong hingga 79 kolom
        $row = array_fill(0, 79, '');
        
        $row[0] = $student->nim ?? '';
        $row[1] = $student->no_tes ?? '';
        $row[2] = $student->nama ?? '';
        $row[3] = $student->jalur_pendaftaran ?? '';
        $row[4] = $student->gelombang ?? '';
        $row[5] = $student->tahun_masuk ?? '';
        $row[6] = $edu->asal_prodi_kode ?? '';
        $row[7] = '1'; // Status Masuk PT Aktif
        $row[8] = optional($student->created_at)->format('Y-m-d') ?? '';
        $row[9] = $student->nim_lama ?? '';
        $row[10] = $student->nik ?? '';
        $row[11] = $student->kode_kota_lahir ?? '';
        $row[12] = $student->nama_tempat_lahir ?? '';
        $row[13] = $student->tanggal_lahir ?? '';
        $row[14] = $this->translateAgama($student->agama);
        $row[15] = $student->jenis_kelamin == 'Laki-laki' ? 'L' : 'P';
        $row[16] = $student->status_nikah_kode ?? '1';
        
        // ... (dilanjutkan hingga index ke 78)
        
        return $row;
    }

    private function translateAgama($agama) {
        $map = ['Islam' => 1, 'Kristen' => 2, 'Katolik' => 3, 'Hindu' => 4, 'Buddha' => 5, 'Konghucu' => 6];
        return $map[$agama] ?? 1;
    }

    public function headings(): array
    {
        $headings = array_fill(0, 79, 'Kolom SIAKAD');
        $headings[0] = 'NIM (wajib diisi)';
        $headings[1] = 'No. Tes (wajib diisi)';
        $headings[2] = 'Nama (wajib diisi)';
        $headings[3] = 'Jalur Masuk (wajib diisi)';
        $headings[4] = 'Gelombang (wajib diisi)';
        $headings[5] = 'Tahun Masuk (wajib diisi)';
        $headings[6] = 'Kode Program dari Program Studi (wajib diisi)';
        $headings[7] = 'Status Masuk PT (wajib diisi)';
        
        return $headings;
    }
}
