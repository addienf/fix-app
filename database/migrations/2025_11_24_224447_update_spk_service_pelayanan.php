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
            $table->dropForeign(['complain_id']);
            $table->dropColumn('complain_id');

            $table->foreignId('pelayanan_id')
                ->nullable()
                ->after('id')
                ->constrained('permintaan_pelayanan_pelanggans')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('spk_services', function (Blueprint $table) {
            // $table->dropForeign(['complain_id']);
            // $table->dropColumn('complain_id');

            // $table->foreignId('complain_id')
            //     ->constrained('complains')
            //     ->onDelete('cascade');
        });
    }
};
