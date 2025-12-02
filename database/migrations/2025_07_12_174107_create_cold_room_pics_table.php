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
        Schema::create('cold_room_pics', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'cold_id')->constrained('cold_rooms')->cascadeOnDelete();
            $table->string('checked_name');
            $table->string('checked_signature');
            $table->date('checked_date');
            $table->string('approved_name')->nullable();
            $table->string('approved_signature')->nullable();
            $table->date('approved_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cold_room_pics');
    }
};
