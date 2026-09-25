<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->string('slug')->unique();
            $table->longText('konten'); // mendukung HTML
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->string('meta_description')->nullable();
            $table->boolean('tampil_di_menu')->default(false); // tampilkan di navigasi publik
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pages'); }
};
