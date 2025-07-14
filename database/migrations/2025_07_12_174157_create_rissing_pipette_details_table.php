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
        Schema::create('rissing_pipette_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'rissing_id')->constrained('rissing_pipettes')->cascadeOnDelete();
            $table->json('checklist');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rissing_pipette_details');
    }
};
