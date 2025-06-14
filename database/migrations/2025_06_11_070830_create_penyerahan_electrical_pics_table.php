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
        Schema::create('penyerahan_electrical_pics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyerahan_electrical_id')->constrained('penyerahan_electricals')->onDelete('cascade');
            $table->string('submit_signature');
            $table->string('submit_name');
            $table->string('receive_signature')->nullable();
            $table->string('receive_name')->nullable();
            $table->string('knowing_signature')->nullable();
            $table->string('knowing_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyerahan_electrical_pics');
    }
};
