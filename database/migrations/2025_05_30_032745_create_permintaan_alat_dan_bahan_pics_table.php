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
        Schema::create('permintaan_alat_dan_bahan_pics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_bahan_id')->constrained('permintaan_alat_dan_bahans')->onDelete('cascade');
            $table->text('dibuat_signature');
            $table->string('dibuat_name');
            $table->text('diketahui_signature')->nullable();
            $table->string('diketahui_name')->nullable();
            $table->text('diserahkan_signature')->nullable();
            $table->string('diserahkan_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_alat_dan_bahan_pics');
    }
};
