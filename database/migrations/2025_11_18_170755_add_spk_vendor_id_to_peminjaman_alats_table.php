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
        Schema::table('peminjaman_alats', function (Blueprint $table) {
            $table->foreignId('spk_vendor_id')
                ->after('id')               // 👈 taruh setelah id
                ->constrained('spk_vendors')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman_alats', function (Blueprint $table) {
            $table->dropForeign(['spk_vendor_id']);
            $table->dropColumn('spk_vendor_id');
        });
    }
};
