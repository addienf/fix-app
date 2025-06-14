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
        Schema::create('qc_passed_pics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qc_passed_id')->constrained('qc_passeds')->onDelete('cascade');
            $table->string('created_signature');
            $table->string('created_name');
            $table->string('approved_signature')->nullable();
            $table->string('approved_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qc_passed_pics');
    }
};
