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
        Schema::create('incomming_material_non_ss', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_pembelian_id')->constrained('permintaan_pembelians')->onDelete('cascade');
            $table->string('no_po');
            $table->string('supplier');
            $table->string('batch_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomming_material_non_ss');
    }
};
