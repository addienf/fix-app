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
            //
            $table->enum('sumber', ['serah', 'spk'])->after('id');

            // kolom baru dulu
            $table->foreignId('spk_marketing_id')
                ->nullable()
                ->after('serah_terima_bahan_id');

            // baru foreign key
            $table->foreign('spk_marketing_id')
                ->references('id')
                ->on('spk_marketings')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('standarisasi_drawings', function (Blueprint $table) {
            //
        });
    }
};
