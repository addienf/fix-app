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
        Schema::create('refrigerator_pics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('refrigerator_id')->constrained('refrigerators')->cascadeOnDelete();
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
        Schema::dropIfExists('refrigerator_pics');
    }
};
