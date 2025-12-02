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
        Schema::create('service_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spk_service_id')->constrained('spk_services')->cascadeOnDelete();
            $table->string('form_no');
            $table->date('tanggal');
            $table->string('name_complaint');
            $table->string('company_name');
            $table->string('address');
            $table->string('phone_number');
            $table->string('service_category');
            $table->string('actions');
            $table->string('service_fields');
            $table->string('status_penyetujuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_reports');
    }
};
