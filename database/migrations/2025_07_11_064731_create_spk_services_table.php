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
        Schema::create('spk_services', function (Blueprint $table) {
            $table->id();
            $table->string('no_complaint_form');
            $table->string('no_spk_service');
            $table->date('tanggal');
            $table->string('alamat');
            $table->string('perusahaan');
            $table->text('deskripsi_pekerjaan');
            $table->date('jadwal_pelaksana');
            $table->date('waktu_selesai');
            $table->string('status_penyelesaian');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk_services');
    }
};
