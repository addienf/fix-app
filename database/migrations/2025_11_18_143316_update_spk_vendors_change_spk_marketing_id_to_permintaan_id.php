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
        Schema::table('spk_vendors', function (Blueprint $table) {
            //
            // Hapus foreign key lama
            $table->dropForeign(['spk_marketing_id']);

            // Ubah nama kolom
            $table->renameColumn('spk_marketing_id', 'permintaan_bahan_pro_id');
        });

        Schema::table('spk_vendors', function (Blueprint $table) {
            // Tambahkan foreign key baru
            $table->foreign('permintaan_bahan_pro_id')->references('id')->on('permintaan_alat_dan_bahans')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spk_vendors', function (Blueprint $table) {
            $table->dropForeign(['permintaan_bahan_pro_id']);
            $table->renameColumn('permintaan_bahan_pro_id', 'spk_marketing_id');
        });

        Schema::table('spk_vendors', function (Blueprint $table) {
            $table->foreign('spk_marketing_id')->references('id')->on('spk_marketings')->cascadeOnDelete();
        });
    }
};
