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
        Schema::table('spk_services', function (Blueprint $table) {
            // Drop lama
            $table->dropForeign(['pelayanan_id']);
            $table->dropColumn(['pelayanan_id']);

            // Tambah kolom baru
            $table->string('jenis_spk')
                ->after('no_spk_service');
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
