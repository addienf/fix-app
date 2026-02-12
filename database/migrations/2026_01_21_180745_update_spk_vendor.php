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
        Schema::table('spk_vendors', function (Blueprint $table) {

            // 1. Hapus FK lama
            $table->dropForeign(['permintaan_bahan_pro_id']);
            $table->dropColumn('permintaan_bahan_pro_id');

            // 2. Tambah FK baru (nullable dulu!!)
            $table->foreignId('perencanaan_id')
                ->nullable()
                ->after('id')
                ->constrained('jadwal_produksis')
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
