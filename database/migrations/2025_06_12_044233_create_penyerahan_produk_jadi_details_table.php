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
        Schema::create('penyerahan_produk_jadi_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_jadi_id')->constrained('penyerahan_produk_jadis')->onDelete('cascade');
            $table->string('nama_produk');
            $table->string('tipe');
            $table->string('volume');
            $table->string('no_seri');
            $table->string('jumlah');
            $table->string('no_spk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyerahan_produk_jadi_details');
    }
};
