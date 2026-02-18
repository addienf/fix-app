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
            $table->dropForeign(['peminjaman_alat_id']);
            $table->renameColumn('peminjaman_alat_id', 'perencanaan_id');
        });

        Schema::table('serah_terima_bahans', function (Blueprint $table) {
            // Tambah FK baru
            $table
                ->foreign('perencanaan_id')
                ->references('id')
                ->on('jadwal_produksis')
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
