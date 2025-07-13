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
        Schema::create('penyedia_jasas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_id')->constrained('berita_acaras')->cascadeOnDelete();
            $table->string('nama');
            $table->string('perusahaan');
            $table->string('alamat');
            $table->string('jabatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyedia_jasas');
    }
};
