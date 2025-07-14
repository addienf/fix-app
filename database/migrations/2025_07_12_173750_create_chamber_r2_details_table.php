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
        Schema::create('chamber_r2_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('r2_id')->constrained('chamber_r2s')->cascadeOnDelete();
            $table->json('checklist');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chamber_r2_details');
    }
};
