<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incomming_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_pembelian_id')->constrained('permintaan_pembelians')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('kondisi_material');
            $table->string('status_penerimaan');
            $table->string('dokumen_pendukung');
            $table->string('file_upload')->nullable();
            $table->string('status_penerimaan_pic');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomming_materials');
    }
};
