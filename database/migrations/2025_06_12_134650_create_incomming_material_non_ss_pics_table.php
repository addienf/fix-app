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
        Schema::create('incomming_material_non_ss_pics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_non_ss_id')->constrained('incomming_material_non_ss')->onDelete('cascade');
            $table->string('checked_signature');
            $table->string('checked_name');
            $table->date('checked_date');
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
        Schema::dropIfExists('incomming_material_non_ss_pics');
    }
};
