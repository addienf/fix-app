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
        Schema::create('qc_passed_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qc_passed_id')->constrained('qc_passeds')->onDelete('cascade');
            $table->string('nama_produk');
            $table->string('tipe');
            $table->string('serial_number');
            $table->string('jenis_transaksi');
            $table->integer('jumlah');
            $table->text('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qc_passed_details');
    }
};
