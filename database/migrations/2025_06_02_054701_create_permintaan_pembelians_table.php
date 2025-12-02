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
        Schema::create('permintaan_pembelians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_bahan_wbb_id')
                ->nullable()
                ->constrained('permintaan_bahans')
                ->onDelete('cascade');
            $table->string('is_stock')->nullable();
            $table->string('status_persetujuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_pembelians');
    }
};
