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
        Schema::create('kelengkapan_material_ss_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelengkapan_material_id')->constrained('kelengkapan_material_ss')->onDelete('cascade');
            // $table->string('nama_alat');
            // $table->string('nomor_seri');
            $table->json('details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelengkapan_material_ss_details');
    }
};
