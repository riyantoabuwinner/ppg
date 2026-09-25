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
        Schema::create('student_educations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->string('asal_pt_kode')->nullable();
            $table->string('asal_perguruan_tinggi')->nullable();
            $table->string('asal_prodi_kode')->nullable();
            $table->string('asal_program_studi')->nullable();
            $table->string('smta_kode')->nullable();
            $table->string('jurusan_smta')->nullable();
            $table->string('jenis_smta')->nullable();
            $table->string('tahun_lulus_smta')->nullable();
            $table->string('no_ijazah_smta')->nullable();
            $table->date('tanggal_ijazah_smta')->nullable();
            $table->string('nilai_uas_smta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_educations');
    }
};
