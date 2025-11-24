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
        Schema::create('product_release_pics', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'release_id')->constrained('product_releases')->cascadeOnDelete();
            $table->string('dibuat_name');
            $table->string('dibuat_signature');
            $table->date('dibuat_date');
            $table->string('dikonfirmasi_name')->nullable();
            $table->string('dikonfirmasi_signature')->nullable();
            $table->date('dikonfirmasi_date')->nullable();
            $table->string('diterima_name')->nullable();
            $table->string('diterima_signature')->nullable();
            $table->date('diterima_date')->nullable();
            $table->string('diketahui_name')->nullable();
            $table->string('diketahui_signature')->nullable();
            $table->date('diketahui_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_release_pics');
    }
};
