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
        Schema::table('pengecekan_performas', function (Blueprint $table) {
            $table->dropForeign(['spk_marketing_id']);
            $table->dropColumn('spk_marketing_id');

            $table->foreignId('produk_jadi_id')
                ->after('id')
                ->constrained('penyerahan_produk_jadis')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengecekan_performas', function (Blueprint $table) {
            $table->dropForeign(['produk_jadi_id']);
            $table->dropColumn('produk_jadi_id');

            $table->foreignId('spk_marketing_id')
                ->constrained('spk_marketings')
                ->onDelete('cascade');
        });
    }
};
