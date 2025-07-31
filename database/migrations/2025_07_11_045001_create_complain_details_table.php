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
        Schema::create('complain_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'complain_id')->constrained('complains')->cascadeOnDelete();
            $table->string('unit_name');
            $table->string('tipe_model');
            $table->string('status_warranty');
            $table->string('field_category');
            $table->text('deskripsi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complain_details');
    }
};
