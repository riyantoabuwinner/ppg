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
        Schema::create('master_kampus_prodi', function (Blueprint $table) {
            $table->id();
            $table->string('kampus');
            $table->string('kode_pt');
            $table->string('program_studi');
            $table->string('jenjang');
            $table->string('kode_prodi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_kampus_prodi');
    }
};
