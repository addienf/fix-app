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
            $table->string('no_spk_vendor')->after('permintaan_bahan_pro_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spk_vendors', function (Blueprint $table) {
            $table->dropColumn('no_spk_vendor');
        });
    }
};
