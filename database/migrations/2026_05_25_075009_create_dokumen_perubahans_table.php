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
        Schema::create('dokumen_perubahans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perubahan_id')->constrained('perubahan_informasis')->onDelete('cascade');
            $table->string('nomor_dok_lama');
            $table->string('nomor_rev_lama');
            $table->string('judul_dok_lama');
            $table->string('nomor_dok_baru');
            $table->string('nomor_rev_baru');
            $table->string('judul_dok_baru');
            $table->text('dokumen_terkait');
            $table->text('uraian_perubahan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen_perubahans');
    }
};
