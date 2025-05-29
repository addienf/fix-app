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
        Schema::create('spk_marketings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spesifikasi_product_id')->constrained('spesifikasi_products')->onDelete('cascade');
            $table->string('no_spk');
            $table->date('tanggal');
            $table->string('no_order');
            $table->string('dari');
            $table->string('kepada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk_marketings');
    }
};
