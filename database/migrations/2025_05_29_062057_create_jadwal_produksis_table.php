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
        Schema::create('jadwal_produksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spk_marketing_id')->constrained('spk_marketings')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('pic_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_produksis');
    }
};
