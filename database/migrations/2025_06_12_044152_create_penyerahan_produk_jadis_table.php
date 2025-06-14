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
        Schema::create('penyerahan_produk_jadis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spk_marketing_id')->constrained('spk_marketings')->onDelete('cascade');
            $table->string('tanggal');
            $table->string('penanggug_jawab');
            $table->string('penerima');
            $table->string('kondisi_produk');
            $table->string('catatan_tambahan');
            $table->string('status_penerimaan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyerahan_produk_jadis');
    }
};
