<?php

namespace App\Services;

use App\Models\User;
use App\Models\StudentProfile;
use App\Models\StudentEducation;
use App\Models\StudentFamily;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToArray;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class LaporDiriImportService
{
    /**
     * Map alias strings to canonical field keys.
     */
    protected array $aliasMap = [
        'nim' => ['nim', 'nomor induk mahasiswa', 'no induk mahasiswa', 'nim wajib diisi', 'no mhs'],
        'nama' => ['nama', 'nama lengkap', 'nama mahasiswa', 'nama wajib diisi', 'nama peserta'],
        'nik' => ['nik', 'no ktp', 'nomor induk kependudukan', 'nik mahasiswa', 'no ktp mahasiswa'],
        'no_tes' => ['no tes', 'no. tes', 'nomor tes', 'no peserta', 'nomor peserta', 'no ujian', 'no. tes (wajib diisi)'],
        'tempat_lahir' => ['tempat lahir', 'nama tempat lahir', 'kota lahir', 'tempat lahir id'],
        'tanggal_lahir' => ['tanggal lahir', 'tgl lahir', 'tgl_lahir', 'tanggal lahir yyyymmdd', 'tanggal lahir mahasiswa'],
        'jenis_kelamin' => ['jenis kelamin', 'jk', 'gender', 'l/p', 'sex'],
        'agama' => ['agama'],
        'alamat' => ['alamat', 'jalan', 'alamat lengkap', 'alamat ktp', 'alamat domisili'],
        'rt' => ['rt'],
        'rw' => ['rw'],
        'dusun' => ['dusun', 'nama dusun'],
        'kelurahan' => ['kelurahan', 'desa', 'kelurahan/desa'],
        'kecamatan' => ['kecamatan'],
        'kode_pos' => ['kode pos', 'kodepos'],
        'no_hp' => ['no hp', 'hp', 'telepon', 'telp', 'no telp', 'whatsapp', 'nomor hp', 'telp rumah'],
        'email' => ['email', 'e-mail', 'surel'],
        'asal_perguruan_tinggi' => ['asal perguruan tinggi', 'asal pt', 'kampus asal', 'universitas asal', 'pt asal', 'nama pt'],
        'asal_program_studi' => ['asal program studi', 'asal prodi', 'prodi asal', 'jurusan s1', 'jurusan asal', 'program studi s1'],
        'jalur_pendaftaran' => ['jalur pendaftaran', 'jalur masuk', 'jalur', 'jalur masuk (wajib diisi)'],
        'gelombang' => ['gelombang', 'gelombang (wajib diisi)'],
        'tahun_masuk' => ['tahun masuk', 'angkatan', 'tahun masuk (wajib diisi)'],
        'status_lapor_diri' => ['status lapor diri', 'status', 'status lapor'],
        'nik_ayah' => ['nik ayah'],
        'nama_ayah' => ['nama ayah'],
        'pekerjaan_ayah' => ['pekerjaan ayah'],
        'penghasilan_ayah' => ['penghasilan ayah'],
        'pendidikan_ayah' => ['pendidikan ayah'],
        'nik_ibu' => ['nik ibu'],
        'nama_ibu' => ['nama ibu'],
        'pekerjaan_ibu' => ['pekerjaan ibu'],
        'penghasilan_ibu' => ['penghasilan ibu'],
        'pendidikan_ibu' => ['pendidikan ibu'],
        'nik_wali' => ['nik wali'],
        'nama_wali' => ['nama wali'],
        'pekerjaan_wali' => ['pekerjaan wali'],
        'penghasilan_wali' => ['penghasilan wali'],
        'pendidikan_wali' => ['pendidikan wali'],
    ];

    /**
     * Import student reporting data from spreadsheet/CSV file.
     *
     * @param string|\Illuminate\Http\UploadedFile $file
     * @param array $options
     * @return array
     */
    public function import($file, array $options = []): array
    {
        $duplicateAction = $options['duplicate_action'] ?? 'update'; // 'update' or 'skip'
        $statusDefault   = $options['status_default'] ?? 'submitted'; // 'draft', 'submitted', 'verified'
        $passwordScheme  = $options['password_scheme'] ?? 'dob';      // 'dob', 'nik', 'default'
        $defaultPassword = $options['default_password'] ?? '12345678';

        // Read raw data from Excel / CSV
        $sheets = Excel::toArray(new class implements ToArray {
            public function array(array $array): array
            {
                return $array;
            }
        }, $file);

        if (empty($sheets) || empty($sheets[0])) {
            return [
                'total_rows' => 0,
                'imported'   => 0,
                'updated'    => 0,
                'skipped'    => 0,
                'errors'     => ['Berkas kosong atau tidak dapat dibaca.'],
            ];
        }

        $rows = $sheets[0];

        // Find header row (search first 5 rows for column containing 'nim', 'nama', or 'nik')
        $headerRowIndex = null;
        $columnMapping  = [];

        for ($i = 0; $i < min(5, count($rows)); $i++) {
            $normalized = $this->buildColumnMapping($rows[$i]);
            if (isset($normalized['nim']) || isset($normalized['nama']) || isset($normalized['nik'])) {
                $headerRowIndex = $i;
                $columnMapping  = $normalized;
                break;
            }
        }

        if ($headerRowIndex === null) {
            return [
                'total_rows' => 0,
                'imported'   => 0,
                'updated'    => 0,
                'skipped'    => 0,
                'errors'     => ['Header kolom tidak ditemukan. Pastikan ada kolom NIM, Nama, atau NIK.'],
            ];
        }

        $imported = 0;
        $updated  = 0;
        $skipped  = 0;
        $errors   = [];
        $dataRows = array_slice($rows, $headerRowIndex + 1);

        foreach ($dataRows as $index => $row) {
            $rowNum = $headerRowIndex + $index + 2; // 1-based index in Excel

            // Extract mapped fields
            $rowData = $this->extractRowData($row, $columnMapping);

            // Skip empty rows
            if (empty(array_filter($rowData))) {
                continue;
            }

            // Must have at least NIM or NIK and Nama
            $nim = trim($rowData['nim'] ?? '');
            $nik = trim($rowData['nik'] ?? '');
            $nama = trim($rowData['nama'] ?? '');

            if (empty($nim) && empty($nik)) {
                $errors[] = "Baris {$rowNum}: Dilewati karena tidak memiliki NIM atau NIK.";
                continue;
            }

            if (empty($nama)) {
                $errors[] = "Baris {$rowNum}: Dilewati karena tidak memiliki Nama Lengkap.";
                continue;
            }

            // If NIM is empty, use NIK as username / NIM
            if (empty($nim)) {
                $nim = $nik;
            }

            // Determine status
            $status = strtolower(trim($rowData['status_lapor_diri'] ?? ''));
            if (!in_array($status, ['draft', 'submitted', 'verified', 'rejected'])) {
                $status = $statusDefault;
            }

            // Format tanggal lahir
            $tglLahir = $this->parseDate($rowData['tanggal_lahir'] ?? null);

            // Format jenis kelamin
            $jk = $this->parseGender($rowData['jenis_kelamin'] ?? null);

            // Determine initial password
            $rawPassword = $defaultPassword;
            if ($passwordScheme === 'dob' && $tglLahir) {
                $rawPassword = str_replace('-', '', $tglLahir); // e.g. 19980514
            } elseif ($passwordScheme === 'nik' && !empty($nik)) {
                $rawPassword = $nik;
            }

            try {
                DB::transaction(function () use (
                    $nim, $nik, $nama, $rowData, $status, $tglLahir, $jk, 
                    $rawPassword, $duplicateAction, &$imported, &$updated, &$skipped, $rowNum
                ) {
                    // Check if User or StudentProfile already exists
                    $existingUser = User::where('username', $nim)->first();
                    $existingProfile = null;

                    if (!$existingUser && !empty($nik)) {
                        $existingProfile = StudentProfile::where('nik', $nik)->first();
                        if ($existingProfile) {
                            $existingUser = $existingProfile->user;
                        }
                    } else if ($existingUser) {
                        $existingProfile = StudentProfile::where('user_id', $existingUser->id)->first();
                    }

                    if ($existingUser) {
                        if ($duplicateAction === 'skip') {
                            $skipped++;
                            return;
                        }

                        // Mode UPDATE: Update existing user & profile
                        $existingUser->name = $nama;
                        if (!empty($rowData['email'])) {
                            $existingUser->email = $rowData['email'];
                        }
                        if ($status) {
                            $existingUser->status_lapor_diri = $status;
                        }
                        $existingUser->save();

                        $profileData = $this->prepareProfileData($existingUser->id, $nim, $nik, $nama, $tglLahir, $jk, $rowData);
                        if ($existingProfile) {
                            $existingProfile->update($profileData);
                            $profile = $existingProfile;
                        } else {
                            $profile = StudentProfile::create($profileData);
                        }

                        $this->saveEducationAndFamilies($profile, $rowData);
                        $updated++;
                    } else {
                        // Mode INSERT: Create new User & Profile
                        $email = !empty($rowData['email']) ? $rowData['email'] : null;

                        $user = User::create([
                            'username'          => $nim,
                            'name'              => $nama,
                            'email'             => $email,
                            'password'          => Hash::make($rawPassword),
                            'role'              => 'mahasiswa',
                            'status_lapor_diri' => $status,
                        ]);

                        $profileData = $this->prepareProfileData($user->id, $nim, $nik, $nama, $tglLahir, $jk, $rowData);
                        $profile = StudentProfile::create($profileData);

                        $this->saveEducationAndFamilies($profile, $rowData);
                        $imported++;
                    }
                });
            } catch (\Throwable $e) {
                $errors[] = "Baris {$rowNum} ({$nama}): Gagal diproses — " . $e->getMessage();
            }
        }

        return [
            'total_rows' => count($dataRows),
            'imported'   => $imported,
            'updated'    => $updated,
            'skipped'    => $skipped,
            'errors'     => $errors,
        ];
    }

    /**
     * Map row headers to our canonical keys by matching aliases.
     */
    protected function buildColumnMapping(array $headerRow): array
    {
        $mapping = [];

        foreach ($headerRow as $colIndex => $headerText) {
            if ($headerText === null) {
                continue;
            }

            $cleanHeader = strtolower(trim((string) $headerText));
            $cleanHeader = preg_replace('/[^a-z0-9]/', ' ', $cleanHeader);
            $cleanHeader = preg_replace('/\s+/', ' ', trim($cleanHeader));

            foreach ($this->aliasMap as $canonicalKey => $aliases) {
                if (isset($mapping[$canonicalKey])) {
                    continue; // Already mapped to a previous column
                }

                foreach ($aliases as $alias) {
                    $cleanAlias = preg_replace('/[^a-z0-9]/', ' ', strtolower($alias));
                    $cleanAlias = preg_replace('/\s+/', ' ', trim($cleanAlias));

                    if ($cleanHeader === $cleanAlias || str_starts_with($cleanHeader, $cleanAlias)) {
                        $mapping[$canonicalKey] = $colIndex;
                        break;
                    }
                }
            }
        }

        return $mapping;
    }

    /**
     * Extract data array from a single row using column mapping.
     */
    protected function extractRowData(array $row, array $columnMapping): array
    {
        $data = [];

        foreach ($columnMapping as $key => $colIndex) {
            $val = $row[$colIndex] ?? null;
            $data[$key] = $val !== null ? trim((string) $val) : null;
        }

        return $data;
    }

    /**
     * Prepare student profile attributes.
     */
    protected function prepareProfileData(int $userId, string $nim, ?string $nik, string $nama, ?string $tglLahir, ?string $jk, array $rowData): array
    {
        return [
            'user_id'            => $userId,
            'nim'                => $nim,
            'nik'                => $nik ?: null,
            'nama'               => $nama,
            'no_tes'             => $rowData['no_tes'] ?? null,
            'nama_tempat_lahir'  => $rowData['tempat_lahir'] ?? null,
            'tanggal_lahir'      => $tglLahir,
            'jenis_kelamin'      => $jk,
            'agama'              => $rowData['agama'] ?? null,
            'alamat'             => $rowData['alamat'] ?? null,
            'rt'                 => $rowData['rt'] ?? null,
            'rw'                 => $rowData['rw'] ?? null,
            'dusun'              => $rowData['dusun'] ?? null,
            'kelurahan'          => $rowData['kelurahan'] ?? null,
            'kecamatan'          => $rowData['kecamatan'] ?? null,
            'kode_pos'           => $rowData['kode_pos'] ?? null,
            'no_hp'              => $rowData['no_hp'] ?? null,
            'email'              => $rowData['email'] ?? null,
            'jalur_pendaftaran'  => $rowData['jalur_pendaftaran'] ?? null,
            'gelombang'          => $rowData['gelombang'] ?? null,
            'tahun_masuk'        => $rowData['tahun_masuk'] ?? null,
        ];
    }

    /**
     * Save student education and families.
     */
    protected function saveEducationAndFamilies(StudentProfile $profile, array $rowData): void
    {
        // 1. Education
        if (!empty($rowData['asal_perguruan_tinggi']) || !empty($rowData['asal_program_studi'])) {
            StudentEducation::updateOrCreate(
                ['student_profile_id' => $profile->id],
                [
                    'asal_perguruan_tinggi' => $rowData['asal_perguruan_tinggi'] ?? null,
                    'asal_program_studi'    => $rowData['asal_program_studi'] ?? null,
                ]
            );
        }

        // 2. Family: Ayah
        if (!empty($rowData['nama_ayah']) || !empty($rowData['nik_ayah'])) {
            StudentFamily::updateOrCreate(
                ['student_profile_id' => $profile->id, 'tipe' => 'ayah'],
                [
                    'nama'        => $rowData['nama_ayah'] ?? null,
                    'nik'         => $rowData['nik_ayah'] ?? null,
                    'pekerjaan'   => $rowData['pekerjaan_ayah'] ?? null,
                    'penghasilan' => $rowData['penghasilan_ayah'] ?? null,
                    'pendidikan'  => $rowData['pendidikan_ayah'] ?? null,
                ]
            );
        }

        // 3. Family: Ibu
        if (!empty($rowData['nama_ibu']) || !empty($rowData['nik_ibu'])) {
            StudentFamily::updateOrCreate(
                ['student_profile_id' => $profile->id, 'tipe' => 'ibu'],
                [
                    'nama'        => $rowData['nama_ibu'] ?? null,
                    'nik'         => $rowData['nik_ibu'] ?? null,
                    'pekerjaan'   => $rowData['pekerjaan_ibu'] ?? null,
                    'penghasilan' => $rowData['penghasilan_ibu'] ?? null,
                    'pendidikan'  => $rowData['pendidikan_ibu'] ?? null,
                ]
            );
        }

        // 4. Family: Wali
        if (!empty($rowData['nama_wali']) || !empty($rowData['nik_wali'])) {
            StudentFamily::updateOrCreate(
                ['student_profile_id' => $profile->id, 'tipe' => 'wali'],
                [
                    'nama'        => $rowData['nama_wali'] ?? null,
                    'nik'         => $rowData['nik_wali'] ?? null,
                    'pekerjaan'   => $rowData['pekerjaan_wali'] ?? null,
                    'penghasilan' => $rowData['penghasilan_wali'] ?? null,
                    'pendidikan'  => $rowData['pendidikan_wali'] ?? null,
                ]
            );
        }
    }

    /**
     * Parse date from various formats or Excel serial number.
     */
    protected function parseDate(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $value = trim($value);

        // If numeric and looks like Excel timestamp (e.g. 35000 - 50000)
        if (is_numeric($value) && (int)$value > 10000 && (int)$value < 70000) {
            try {
                return ExcelDate::excelToDateTimeObject((int)$value)->format('Y-m-d');
            } catch (\Throwable $e) {
                // Ignore and fall through
            }
        }

        // Standard string date parsing
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return null;
        }
    }

    /**
     * Parse gender to 'Laki-laki' or 'Perempuan'.
     */
    protected function parseGender(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        $v = strtolower(trim($value));
        if ($v === 'l' || str_contains($v, 'laki') || str_contains($v, 'pria') || $v === '1' || $v === 'm' || $v === 'male') {
            return 'Laki-laki';
        }
        if ($v === 'p' || str_contains($v, 'perempuan') || str_contains($v, 'wanita') || $v === '2' || $v === 'f' || $v === 'female') {
            return 'Perempuan';
        }

        return $value;
    }
}
