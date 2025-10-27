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
        Schema::create('ketidaksesuaian_snks', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'ketidaksesuaian_id')->constrained('ketidaksesuaians')->cascadeOnDelete();
            $table->text('penyebab');
            $table->string('tindakan_kolektif');
            $table->string('tindakan_pencegahan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ketidaksesuaian_snks');
    }
};
