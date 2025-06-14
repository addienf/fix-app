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
        Schema::create('penyerahan_produk_jadi_pics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_jadi_id')->constrained('penyerahan_produk_jadis')->onDelete('cascade');
            $table->text('submit_signature');
            $table->string('submit_name');
            $table->text('receive_signature')->nullable();
            $table->string('receive_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyerahan_produk_jadi_pics');
    }
};
