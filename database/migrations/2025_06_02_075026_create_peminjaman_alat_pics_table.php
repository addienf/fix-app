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
        Schema::create('peminjaman_alat_pics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peminjaman_alat_id')->constrained('peminjaman_alats')->onDelete('cascade');
            $table->string('department');
            $table->string('nama_peminjam');
            $table->text('signature');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman_alat_pics');
    }
};
