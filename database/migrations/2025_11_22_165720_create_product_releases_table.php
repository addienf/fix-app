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
        Schema::create('product_releases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengecekan_performa_id')->constrained('pengecekan_performas')->onDelete('cascade')->nullable();
            $table->string('no_order_release');
            $table->string('product');
            $table->string('batch');
            $table->text('remarks');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_releases');
    }
};
