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
        Schema::create('serah_terima_bahan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('serah_terima_bahan_id')->constrained('serah_terima_bahans')->onDelete('cascade');
            $table->string('bahan_baku')->nullable();
            $table->string('spesifikasi')->nullable();
            $table->string('jumlah')->nullable();
            $table->string('keperluan_barang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('serah_terima_bahan_details');
    }
};
