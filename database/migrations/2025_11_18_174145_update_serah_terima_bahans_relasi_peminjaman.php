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
        //
        Schema::table('serah_terima_bahans', function (Blueprint $table) {
            // Hapus FK lama
            $table->dropForeign(['permintaan_bahan_pro_id']);

            // Ubah nama kolom
            $table->renameColumn('permintaan_bahan_pro_id', 'peminjaman_alat_id');
        });

        Schema::table('serah_terima_bahans', function (Blueprint $table) {
            // Tambah FK baru
            $table
                ->foreign('peminjaman_alat_id')
                ->references('id')
                ->on('peminjaman_alats')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
