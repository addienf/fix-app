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
        Schema::create('spesifikasi_product_pics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spesifikasi_product_id')->constrained('spesifikasi_products')->onDelete('cascade');
            $table->string('signed_signature');
            $table->string('signed_name');
            $table->date('signed_date');
            $table->string('accepted_signature')->nullable();
            $table->string('accepted_name')->nullable();
            $table->date('accepted_date')->nullable();
            $table->string('acknowledge_signature')->nullable();
            $table->string('acknowledge_name')->nullable();
            $table->date('acknowledge_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spesifikasi_product_pics');
    }
};
