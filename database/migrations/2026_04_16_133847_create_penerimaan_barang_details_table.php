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
        Schema::create('penerimaan_barang_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penerimaan_barang_id')->constrained('penerimaan_barangs')->onDelete('cascade');
            $table->string('nama_material');
            $table->string('kode_material');
            $table->integer('jumlah');
            $table->string('satuan');
            $table->string('kondisi');
            $table->string('status_barang');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerimaan_barang_details');
    }
};
