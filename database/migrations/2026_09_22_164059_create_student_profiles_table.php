<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('nim')->nullable();
            $table->string('nim_lama')->nullable();
            $table->string('no_tes')->nullable();
            $table->string('nik')->nullable();
            $table->string('nama')->nullable();
            $table->string('tempat_lahir_id')->nullable();
            $table->string('kode_kota_lahir')->nullable();
            $table->string('nama_tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('jenis_kelamin')->nullable(); // Laki-laki / Perempuan
            $table->string('agama')->nullable();
            $table->string('kewarganegaraan')->nullable();
            $table->string('nisn')->nullable();
            $table->string('npwp')->nullable();
            $table->string('no_asuransi')->nullable();
            $table->string('jalur_pendaftaran')->nullable();
            $table->string('jenis_pendaftaran')->nullable();
            $table->string('gelombang')->nullable();
            $table->string('tahun_masuk')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->date('tgl_masuk_kuliah')->nullable();
            $table->string('mulai_semester')->nullable();
            $table->text('alamat')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('dusun')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('id_kecamatan')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('jenis_tinggal')->nullable();
            $table->string('alat_transportasi')->nullable();
            $table->string('no_telp')->nullable(); // telp_rumah
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
            $table->string('terima_kps')->nullable();
            $table->string('no_kps')->nullable();
            $table->string('status_nikah')->nullable();
            $table->string('status_nikah_kode')->nullable();
            $table->string('jenis_pembiayaan')->nullable();
            $table->string('biaya_masuk_kuliah')->nullable();
            $table->string('biaya_masuk')->nullable();
            $table->string('sumber_dana')->nullable();
            $table->string('beasiswa')->nullable();
            $table->string('tempat_kerja')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
