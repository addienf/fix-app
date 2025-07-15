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
        Schema::create('service_report_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'service_id')->constrained('service_reports')->cascadeOnDelete();
            $table->text('taken_item');
            $table->string('status_service');
            $table->string('action');
            $table->string('service_fields');
            $table->string('upload_file');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_report_details');
    }
};
