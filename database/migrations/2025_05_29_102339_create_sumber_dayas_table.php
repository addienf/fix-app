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
        Schema::create('sumber_dayas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_produksi_id')->constrained('jadwal_produksis')->onDelete('cascade');
            $table->string('mesin_yang_digunakan');
            $table->string('tenaga_kerja');
            $table->json('bahan_baku');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sumber_dayas');
    }
};
