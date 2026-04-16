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
        Schema::create('berita_acara_pics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('berita_id')->constrained('berita_acaras')->cascadeOnDelete();
            $table->string('jasa_name');
            $table->string('jasa_ttd');
            $table->string('pelanggan_name');
            $table->string('pelanggan_ttd');
            $table->string('sign_token')->nullable();
            $table->timestamp('sign_token_expires_at')->nullable();
            $table->timestamp('sign_at')->nullable();
            $table->string('signed_ip')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acara_pics');
    }
};
