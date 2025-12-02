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
        Schema::create('spk_service_pics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spk_service_id')->constrained('spk_services')->cascadeOnDelete();
            $table->string('dikonfirmasi_signature');
            $table->string('dikonfirmasi_name');
            $table->string('dibuat_signature')->nullable();
            $table->string('dibuat_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk_service_pics');
    }
};
