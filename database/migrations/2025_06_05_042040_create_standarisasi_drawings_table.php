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
        Schema::create('standarisasi_drawings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spk_marketing_id')->constrained('spk_marketings')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('jenis_gambar', ['arsitektur', 'struktur', 'mekanikal', 'elektrikal', 'lainnya']);
            $table->enum('format_gambar', ['A4', 'A3', 'A2', 'A1']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standarisasi_drawings');
    }
};
