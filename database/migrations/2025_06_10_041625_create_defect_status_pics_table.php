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
        Schema::create('defect_status_pics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('defect_status_id')->constrained('defect_statuses')->onDelete('cascade');
            $table->string('inspected_signature');
            $table->string('inspected_name');
            $table->date('inspected_date');
            $table->string('accepted_signature')->nullable();
            $table->string('accepted_name')->nullable();
            $table->date('accepted_date')->nullable();
            $table->string('approved_signature')->nullable();
            $table->string('approved_name')->nullable();
            $table->date('approved_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defect_status_pics');
    }
};
