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
        Schema::create('defect_status_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('defect_status_id')->constrained('defect_statuses')->onDelete('cascade');
            $table->json('details');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defect_status_details');
    }
};
