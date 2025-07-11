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
        Schema::create('pemeriksaan_persetujuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spk_service_id')->constrained('spk_services')->cascadeOnDelete();
            $table->string('status_pekerjaan')->nullable();
            $table->text('catatan_tambahan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_persetujuans');
    }
};
