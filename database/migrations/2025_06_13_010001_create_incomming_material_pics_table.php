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
        Schema::create('incomming_material_pics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('incomming_material_id')->constrained('incomming_materials')->onDelete('cascade');
            $table->string('submited_name');
            $table->string('submited_signature');
            $table->string('received_name');
            $table->string('received_signature');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomming_material_pics');
    }
};
