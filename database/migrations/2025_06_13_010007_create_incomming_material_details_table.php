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
        Schema::create('incomming_material_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incomming_material_id')->constrained('incomming_materials')->onDelete('cascade');
            $table->string('nama_material');
            $table->string('batch_no');
            $table->integer('jumlah');
            $table->string('satuan');
            $table->string('kondisi_material');
            $table->string('status_qc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomming_material_details');
    }
};
