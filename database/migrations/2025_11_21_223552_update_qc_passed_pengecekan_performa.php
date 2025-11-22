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
        Schema::table('qc_passeds', function (Blueprint $table) {

            // 1. Hapus FK lama
            $table->dropForeign(['spk_marketing_id']);
            $table->dropColumn('spk_marketing_id');

            // 2. Tambah FK baru (nullable dulu!!)
            $table->foreignId('pengecekan_performa_id')
                ->nullable()
                ->after('id')
                ->constrained('pengecekan_performas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('qc_passeds', function (Blueprint $table) {
            $table->dropForeign(['pengecekan_performa_id']);
            $table->dropColumn('pengecekan_performa_id');

            $table->foreignId('spk_marketing_id')
                ->constrained('spk_marketings')
                ->onDelete('cascade');
        });
    }
};
