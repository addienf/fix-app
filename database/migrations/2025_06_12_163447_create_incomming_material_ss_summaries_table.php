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
        Schema::create('incomming_material_ss_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_ss_id')->constrained('incomming_material_ss')->onDelete('cascade');
            $table->json('summary');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomming_material_ss_summaries');
    }
};
