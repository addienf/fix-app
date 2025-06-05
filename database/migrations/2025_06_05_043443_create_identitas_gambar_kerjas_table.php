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
        Schema::create('identitas_gambar_kerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standarisasi_drawing_id')->constrained('standarisasi_drawings')->onDelete('cascade');
            $table->string('judul_gambar');
            $table->string('no_gambar');
            $table->date('tanggal_pembuatan');
            $table->boolean('revisi');
            $table->string('nama_pembuat');
            $table->string('nama_pemeriksa');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('identitas_gambar_kerjas');
    }
};
