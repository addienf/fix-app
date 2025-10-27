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
        Schema::create('urs', function (Blueprint $table) {
            $table->id();
            $table->string('no_urs')->unique();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->text('permintaan_khusus')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('urs');
    }
};
