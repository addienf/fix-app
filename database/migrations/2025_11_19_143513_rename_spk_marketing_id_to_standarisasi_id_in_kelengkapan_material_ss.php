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
        Schema::table('kelengkapan_material_ss', function (Blueprint $table) {
            $table->dropForeign(['spk_marketing_id']);

            // Rename kolom
            $table->renameColumn('spk_marketing_id', 'standarisasi_drawing_id');
        });

        Schema::table('kelengkapan_material_ss', function (Blueprint $table) {
            // Tambah foreign key baru
            $table->foreign('standarisasi_drawing_id')
                ->references('id')
                ->on('standarisasi_drawings')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelengkapan_material_ss', function (Blueprint $table) {
            $table->dropForeign(['standarisasi_id']);
            $table->renameColumn('standarisasi_id', 'spk_marketing_id');
        });

        Schema::table('kelengkapan_material_ss', function (Blueprint $table) {
            $table->foreign('spk_marketing_id')
                ->references('id')
                ->on('spk_marketings')
                ->cascadeOnDelete();
        });
    }
};
