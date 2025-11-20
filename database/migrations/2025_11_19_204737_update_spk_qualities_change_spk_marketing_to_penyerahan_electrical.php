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
        Schema::table('spk_qualities', function (Blueprint $table) {

            // 1. Hapus foreign key lama
            $table->dropForeign(['spk_marketing_id']);

            // 2. Hapus kolom lama
            $table->dropColumn('spk_marketing_id');

            // 3. Tambah kolom baru
            $table->foreignId('penyerahan_electrical_id')
                ->nullable()
                ->constrained('penyerahan_electricals')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spk_qualities', function (Blueprint $table) {

            // rollback: hapus kolom baru
            $table->dropForeign(['penyerahan_electrical_id']);
            $table->dropColumn('penyerahan_electrical_id');

            // buat lagi kolom lama
            $table->foreignId('spk_marketing_id')
                ->nullable()
                ->constrained('spk_marketings')
                ->onDelete('cascade');
        });
    }
};
