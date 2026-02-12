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
        Schema::create('pelayanan_pelanggan_pics', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'pelayanan_id')->constrained('permintaan_pelayanan_pelanggans')->cascadeOnDelete();
            $table->string('dibuat_signature');
            $table->string('dibuat_name');
            $table->string('diterima_signature')->nullable();
            $table->string('diterima_name')->nullable();
            $table->string('diketahui_signature')->nullable();
            $table->string('diketahui_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelayanan_pelanggan_pics');
    }
};
