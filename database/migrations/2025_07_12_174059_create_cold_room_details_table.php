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
        Schema::create('cold_room_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'cold_id')->constrained('cold_rooms')->cascadeOnDelete();
            $table->json('checklist');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cold_room_details');
    }
};
