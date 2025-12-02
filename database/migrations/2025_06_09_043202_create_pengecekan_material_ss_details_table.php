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
        Schema::create('pengecekan_material_ss_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengecekan_material_id')->constrained('pengecekan_material_ss')->onDelete('cascade');
            $table->json('details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengecekan_material_ss_details');
    }
};
