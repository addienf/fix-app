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
            $table->text('signature');
            $table->string('name');
            $table->date('date');
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
