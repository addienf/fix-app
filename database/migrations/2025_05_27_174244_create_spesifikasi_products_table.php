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
        Schema::create('spesifikasi_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('urs_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_stock')->nullable();
            $table->text('detail_specification');
            $table->string('delivery_address');
            $table->date('estimasi_pengiriman');
            $table->string('status_penerimaan_order');
            $table->text('alasan')->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spesifikasi_products');
    }
};
