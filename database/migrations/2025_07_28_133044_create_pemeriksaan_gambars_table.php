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
        Schema::create('pemeriksaan_gambars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standarisasi_drawing_id')->constrained('standarisasi_drawings')->onDelete('cascade');
            $table->string('pemeriksaan_komponen');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemeriksaan_gambars');
    }
};
