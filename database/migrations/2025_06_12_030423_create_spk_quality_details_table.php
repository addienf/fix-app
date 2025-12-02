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
        Schema::create('spk_quality_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spk_qualities_id')->constrained('spk_qualities')->onDelete('cascade');
            $table->string('nama_produk');
            $table->string('nomor_seri');
            $table->string('jumlah');
            $table->string('no_urs');
            $table->string('rencana_pengiriman');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk_quality_details');
    }
};
