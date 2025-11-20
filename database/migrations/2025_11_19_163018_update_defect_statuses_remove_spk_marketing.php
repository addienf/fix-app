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
        Schema::table('defect_statuses', function (Blueprint $table) {
            if (Schema::hasColumn('defect_statuses', 'spk_marketing_id')) {
                $table->dropForeign(['spk_marketing_id']);
                $table->dropColumn('spk_marketing_id');
            }
            if (!Schema::hasColumn('defect_statuses', 'tipe_sumber')) {
                $table->string('tipe_sumber')->after('id');
            }
            if (!Schema::hasColumn('defect_statuses', 'sumber_id')) {
                $table->unsignedBigInteger('sumber_id')->after('tipe_sumber');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('defect_statuses', function (Blueprint $table) {
            $table->foreignId('spk_marketing_id')
                ->constrained('spk_marketings')
                ->onDelete('cascade');
        });
    }
};
