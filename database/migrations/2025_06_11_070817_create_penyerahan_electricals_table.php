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
        Schema::create('penyerahan_electricals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengecekan_material_id')->constrained('pengecekan_material_ss')->onDelete('cascade');
            $table->string('nama_produk');
            $table->string('kode_produk');
            $table->string('no_seri');
            $table->date('tanggal_selesai');
            $table->integer('jumlah');
            $table->string('kondisi');
            $table->text('deskripsi_kondisi');
            $table->string('status_penyelesaian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyerahan_electricals');
    }
};
