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
        Schema::create('student_families', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_profile_id')->constrained()->cascadeOnDelete();
            $table->enum('tipe', ['ayah', 'ibu', 'wali']);
            $table->string('nik')->nullable();
            $table->string('nama')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->string('pendidikan_kode')->nullable();
            $table->string('pendidikan')->nullable();
            $table->string('pekerjaan_kode')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->string('penghasilan_kode')->nullable();
            $table->string('penghasilan')->nullable();
            $table->string('status_hidup')->nullable(); // 1 = Hidup, 0 = Meninggal
            $table->date('tanggal_meninggal')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_hp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_families');
    }
};
