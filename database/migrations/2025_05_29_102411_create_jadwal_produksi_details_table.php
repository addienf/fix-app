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
        Schema::create('jadwal_produksi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_produksi_id')->constrained('jadwal_produksis')->onDelete('cascade');
            $table->string('nama_produk');
            $table->string('tipe');
            $table->string('volume');
            $table->integer('jumlah');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_produksi_details');
    }
};
