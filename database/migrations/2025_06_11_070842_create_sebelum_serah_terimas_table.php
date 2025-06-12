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
        Schema::create('sebelum_serah_terimas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyerahan_electrical_id')->constrained('penyerahan_electricals')->onDelete('cascade');
            $table->string('kondisi_fisik');
            $table->text('detail_kondisi_fisik')->nullable();
            $table->string('kelengkapan_komponen');
            $table->text('detail_kelengkapan_komponen')->nullable();
            $table->string('dokumen_pendukung');
            $table->string('file_pendukung')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sebelum_serah_terimas');
    }
};
