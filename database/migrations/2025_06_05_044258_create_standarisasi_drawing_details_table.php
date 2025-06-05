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
        Schema::create('standarisasi_drawing_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('standarisasi_drawing_id')->constrained('standarisasi_drawings')->onDelete('cascade');
            $table->text('lampiran');
            $table->text('catatan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standarisasi_drawing_details');
    }
};
