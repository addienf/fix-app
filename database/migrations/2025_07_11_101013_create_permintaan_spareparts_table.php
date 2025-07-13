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
        Schema::create('permintaan_spareparts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spk_service_id')->constrained('spk_services')->cascadeOnDelete();
            $table->date('tanggal');
            $table->string('no_surat');
            $table->string('dari');
            $table->string('kepada');
            $table->string('status_penyerahan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_spareparts');
    }
};
