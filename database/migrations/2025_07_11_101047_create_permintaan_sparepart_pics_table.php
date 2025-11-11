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
        Schema::create('permintaan_sparepart_pics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sparepart_id')->constrained('permintaan_spareparts')->cascadeOnDelete();
            $table->string('dibuat_signature');
            $table->string('dibuat_name');
            $table->string('diketahui_signature')->nullable();
            $table->string('diketahui_name')->nullable();
            $table->string('diserahkan_signature')->nullable();
            $table->string('diserahkan_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_sparepart_pics');
    }
};
