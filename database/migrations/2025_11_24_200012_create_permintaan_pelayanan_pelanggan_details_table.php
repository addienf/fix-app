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
        Schema::create('pelayanan_pelanggan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'pelayanan_id')->constrained('permintaan_pelayanan_pelanggans')->cascadeOnDelete();
            $table->string('nama_alat');
            $table->string('tipe');
            $table->string('nomor_seri');
            $table->text('deskripsi');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelayanan_pelanggan_details');
    }
};
