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
        Schema::create('permintaan_bahans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_bahan_pro_id')->constrained('permintaan_alat_dan_bahans')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('no_surat');
            $table->string('dari');
            $table->string('kepada');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_bahans');
    }
};
