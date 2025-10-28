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
        Schema::create('permintaan_bahan_pics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permintaan_bahan_wbb_id')->constrained('permintaan_bahans')->onDelete('cascade');
            $table->string('dibuat_signature');
            $table->string('dibuat_name');
            $table->date('dibuat_date');
            $table->string('mengetahui_signature')->nullable();
            $table->string('mengetahui_name')->nullable();
            $table->date('mengetahui_date')->nullable();
            $table->string('diserahkan_signature')->nullable();
            $table->string('diserahkan_name')->nullable();
            $table->date('diserahkan_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permintaan_bahan_pics');
    }
};
