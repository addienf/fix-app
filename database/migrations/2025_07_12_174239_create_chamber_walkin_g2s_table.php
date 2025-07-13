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
        Schema::create('chamber_walkin_g2s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spk_service_id')->constrained('spk_services')->cascadeOnDelete();
            $table->string('tag_no');
            $table->text('remarks');
            $table->string('status_penyetujuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chamber_walkin_g2s');
    }
};
