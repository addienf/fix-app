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
        Schema::table('standarisasi_drawings', function (Blueprint $table) {
            $table->dropForeign(['spk_marketing_id']);
            $table->renameColumn('spk_marketing_id', 'serah_terima_bahan_id');
        });

        Schema::table('standarisasi_drawings', function (Blueprint $table) {
            // Tambah foreign key baru
            $table->foreign('serah_terima_bahan_id')
                ->references('id')
                ->on('serah_terima_bahans')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('standarisasi_drawings', function (Blueprint $table) {
            // Balikkan perubahan
            $table->dropForeign(['serah_terima_bahan_id']);
            $table->renameColumn('serah_terima_bahan_id', 'spk_marketing_id');
        });

        Schema::table('standarisasi_drawings', function (Blueprint $table) {
            $table->foreign('spk_marketing_id')
                ->references('id')
                ->on('spk_marketings')
                ->onDelete('cascade');
        });
    }
};
