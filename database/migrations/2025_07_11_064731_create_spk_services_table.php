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
            $table->foreignId(column: 'complain_id')->constrained('complains')->cascadeOnDelete();
            $table->string('no_spk_service');
            $table->string('perusahaan');
            $table->string('alamat');
            $table->string('deskripsi_pekerjaan');
            $table->string('deskripsi_pekerjaan_lainnya')->nullable();
            $table->date('tanggal_pelaksanaan');
            $table->string('tempat_pelaksanaan');
            $table->string('status');
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
