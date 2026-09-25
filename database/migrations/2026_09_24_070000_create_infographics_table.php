<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('infographics', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori')->default('Umum'); // Alur Lapor Diri, Syarat Berkas, Ketentuan Foto, Layanan Bantuan
            $table->text('deskripsi')->nullable();
            $table->string('gambar');
            $table->string('file_pdf')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infographics');
    }
};
