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
        Schema::create('pengecekan_performas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spk_marketing_id')->constrained('spk_marketings')->onDelete('cascade');
            $table->string('tipe');
            $table->string('volume');
            $table->string('serial_number');
            $table->text('note');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengecekan_performas');
    }
};
