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
        Schema::create('spk_vendor_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spk_vendor_id')->constrained('spk_vendors')->onDelete('cascade');
            $table->string('nama_bahan');
            $table->string('spesifikasi');
            $table->string('jumlah');
            $table->string('keperluan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk_vendor_details');
    }
};
