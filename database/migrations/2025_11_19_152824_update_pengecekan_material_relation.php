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
        Schema::table('pengecekan_material_ss', function (Blueprint $table) {

            // Hapus foreign key lama
            $table->dropForeign(['spk_marketing_id']);
            $table->dropColumn('spk_marketing_id');

            // Tambah kolom baru
            $table->foreignId('kelengkapan_material_id')
                ->after('id')
                ->constrained('kelengkapan_material_ss')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('pengecekan_material_ss', function (Blueprint $table) {

            // Balik seperti semula jika rollback
            $table->dropForeign(['kelengkapan_material_id']);
            $table->dropColumn('kelengkapan_material_id');

            $table->foreignId('spk_marketing_id')
                ->constrained('spk_marketings')
                ->onDelete('cascade');
        });
    }
};
