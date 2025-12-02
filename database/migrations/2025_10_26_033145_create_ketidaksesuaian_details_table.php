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
        Schema::create('ketidaksesuaian_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'ketidaksesuaian_id')->constrained('ketidaksesuaians')->cascadeOnDelete();
            $table->string('nama_produk');
            $table->string('serial_number');
            $table->string('ketidaksesuaian');
            $table->integer('jumlah');
            $table->string('satuan');
            $table->string('keterangan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ketidaksesuaian_details');
    }
};
