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
        Schema::create('berita_acara_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_id')->constrained('berita_acaras')->cascadeOnDelete();
            $table->string('jenis_pekerjaan');
            $table->string('produk');
            $table->string('serial_number');
            $table->text('desc_pekerjaan');
            $table->string('status_barang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acara_details');
    }
};
