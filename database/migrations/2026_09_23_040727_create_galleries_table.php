<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->nullable();
            $table->string('file_path');
            $table->text('file_url')->nullable();
            $table->string('kategori')->default('Umum');
            $table->string('ukuran')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('sumber')->default('manual'); // 'manual' | 'wordpress_import'
            $table->foreignId('article_id')->nullable()->constrained('articles')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('galleries');
    }
};
