<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\LaporDiriImportService;

class ImportLaporDiriCommand extends Command
{
    protected $signature = 'ppg:import-lapor-diri 
                            {file : Path ke berkas Excel (.xlsx, .xls) atau CSV (.csv)}
                            {--duplicate=update : Aksi duplikat: update atau skip}
                            {--status=submitted : Status default: draft, submitted, verified}
                            {--password-scheme=dob : Skema password: dob (tgl lahir), nik, atau default}
                            {--default-password=12345678 : Password default jika skema=default}';

    protected $description = 'Import data lapor diri mahasiswa dari file Excel/CSV sistem sebelumnya';

    public function handle(LaporDiriImportService $service): int
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("Berkas tidak ditemukan: {$filePath}");
            return 1;
        }

        $this->info("Memulai proses impor data lapor diri dari: {$filePath}...");

        $options = [
            'duplicate_action' => $this->option('duplicate'),
            'status_default'   => $this->option('status'),
            'password_scheme'  => $this->option('password-scheme'),
            'default_password' => $this->option('default-password'),
        ];

        $result = $service->import($filePath, $options);

        $this->newLine();
        $this->info("=== Hasil Impor Data Lapor Diri ===");
        $this->line("Total Baris Data : {$result['total_rows']}");
        $this->line("Data Baru Diimpor: {$result['imported']}");
        $this->line("Data Diperbarui  : {$result['updated']}");
        $this->line("Data Dilewati    : {$result['skipped']}");

        if (!empty($result['errors'])) {
            $this->newLine();
            $this->warn("Catatan / Peringatan (" . count($result['errors']) . "):");
            foreach (array_slice($result['errors'], 0, 10) as $err) {
                $this->line(" - {$err}");
            }
            if (count($result['errors']) > 10) {
                $this->line(" ... dan " . (count($result['errors']) - 10) . " catatan lainnya.");
            }
        }

        $this->newLine();
        $this->info("Proses impor selesai dengan sukses!");

        return 0;
    }
}
