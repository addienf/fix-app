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
        Schema::create('permintaan_bahan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_bahan_wbb_id')->constrained('permintaan_bahans')->onDelete('cascade');
            $table->string('bahan_baku');
            $table->string('spesifikasi')->nullable();
            $table->string('jumlah')->nullable();
            $table->string('keperluan_barang')->nullable();
            $table->string('status_stock')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_bahan_details');
    }
};
