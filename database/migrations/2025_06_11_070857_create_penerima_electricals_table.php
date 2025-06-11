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
        Schema::create('penerima_electricals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyerahan_electrical_id')->constrained('penyerahan_electricals')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('diterima_oleh');
            $table->string('catatan_tambahan');
            $table->string('status_penerimaan');
            $table->string('penjelasan_status')->nullable();
            $table->string('alasan_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerima_electricals');
    }
};
