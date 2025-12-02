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
        Schema::create('defect_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spk_marketing_id')->constrained('spk_marketings')->onDelete('cascade');
            $table->string('no_surat');
            $table->string('tipe_sumber');
            $table->unsignedBigInteger('sumber_id');
            $table->string('tipe');
            $table->string('volume');
            $table->string('serial_number');
            $table->string('file_upload');
            $table->text('note');
            $table->string('status_penyelesaian')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defect_statuses');
    }
};
