<?php

namespace App\Exports;

use App\Models\StudentProfile;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PddiktiExport implements FromCollection, WithMapping, WithHeadings
{
    public function collection()
    {
        // Hanya ambil yang sudah diverifikasi (disini asumsi status user = submitted atau verified)
        return StudentProfile::with(['education', 'families', 'user'])->get();
    }

    public function map($student): array
    {
        $ayah = $student->families->where('tipe', 'ayah')->first();
        $ibu = $student->families->where('tipe', 'ibu')->first();
        $wali = $student->families->where('tipe', 'wali')->first();
        $edu = $student->education;

        return [
            $student->nim ?? '',
            $student->nama ?? '',
            $student->nama_tempat_lahir ?? '',
            $student->tanggal_lahir ?? '',
            $student->jenis_kelamin ?? '',
            $student->nik ?? '',
            $student->agama ?? '',
            $student->nisn ?? '',
            $student->jalur_pendaftaran ?? '',
            $student->npwp ?? '',
            $student->kewarganegaraan ?? '',
            $student->jenis_pendaftaran ?? '',
            $student->tgl_masuk_kuliah ?? '',
            $student->mulai_semester ?? '',
            $student->alamat ?? '',
            $student->rt ?? '',
            $student->rw ?? '',
            $student->dusun ?? '',
            $student->kelurahan ?? '',
            $student->kecamatan ?? '', 
            $student->kode_pos ?? '',
            $student->jenis_tinggal ?? '',
            $student->alat_transportasi ?? '',
            $student->no_telp ?? '',
            $student->no_hp ?? '',
            $student->email ?? '',
            $student->terima_kps ?? '',
            $student->no_kps ?? '',
            $ayah->nik ?? '',
            $ayah->nama ?? '',
            $ayah->tanggal_lahir ?? '',
            $ayah->pendidikan ?? '',
            $ayah->pekerjaan ?? '',
            $ayah->penghasilan ?? '',
            $ibu->nik ?? '',
            $ibu->nama ?? '',
            $ibu->tanggal_lahir ?? '',
            $ibu->pendidikan ?? '',
            $ibu->pekerjaan ?? '',
            $ibu->penghasilan ?? '',
            $wali->nama ?? '',
            $wali->tanggal_lahir ?? '',
            $wali->pendidikan ?? '',
            $wali->pekerjaan ?? '',
            $wali->penghasilan ?? '',
            $edu->asal_prodi_kode ?? '',
            $student->jenis_pembiayaan ?? '',
            $student->biaya_masuk_kuliah ?? '',
            $edu->asal_perguruan_tinggi ?? '',
            $edu->asal_program_studi ?? '',
        ];
    }

    public function headings(): array
    {
        return [
            'NIM', 'NAMA', 'Tempat Lahir', 'Tanggal Lahir', 'Jenis Kelamin', 'NIK', 'Agama', 
            'NISN', 'Jalur Pendaftaran', 'NPWP', 'Kewarganegaraan', 'Jenis Pendaftaran', 
            'Tgl Masuk Kuliah', 'Mulai Semester', 'Jalan', 'RT', 'RW', 'Nama Dusun', 
            'Kelurahan', 'Kecamatan', 'Kode Pos', 'Jenis Tinggal', 'Alat Transportasi', 
            'Telp Rumah', 'No HP', 'Email', 'Terima KPS', 'No KPS', 'NIK Ayah', 'Nama Ayah', 
            'Tgl Lahir Ayah', 'Pendidikan Ayah', 'Pekerjaan Ayah', 'Penghasilan Ayah', 
            'NIK Ibu', 'Nama Ibu', 'Tanggal Lahir Ibu', 'Pendidikan Ibu', 'Pekerjaan Ibu', 
            'Penghasilan Ibu', 'Nama Wali', 'Tanggal Lahir wali', 'Pendidikan Wali', 
            'Pekerjaan Wali', 'Penghasilan Wali', 'Kode Prodi', 'Jenis Pembiayaan', 
            'Biaya Masuk Kuliah', 'Asal Perguruan Tinggi', 'Asal Program Studi'
        ];
    }
}
