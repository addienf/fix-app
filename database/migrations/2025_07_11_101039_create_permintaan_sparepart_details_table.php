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
        Schema::create('permintaan_sparepart_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sparepart_id')->constrained('permintaan_spareparts')->cascadeOnDelete();
            $table->string('bahan_baku');
            $table->string('spesifikasi');
            $table->integer('jumlah');
            $table->string('keperluan_barang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_sparepart_details');
    }
};
