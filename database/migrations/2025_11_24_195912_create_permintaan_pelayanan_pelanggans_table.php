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
        Schema::create('permintaan_pelayanan_pelanggans', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'complain_id')->constrained('complains')->cascadeOnDelete();
            $table->string('no_form');
            $table->date('tanggal');
            $table->string('alamat');
            $table->string('perusahaan');
            $table->string('jenis_permintaan');
            $table->string('jenis_permintaan_lainnya')->nullable();
            $table->date('tanggal_pelaksanaan');
            $table->string('tempat_pelaksanaan');
            $table->string('no_kontak');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_pelayanan_pelanggans');
    }
};
