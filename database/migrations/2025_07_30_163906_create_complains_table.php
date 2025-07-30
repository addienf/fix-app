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
        Schema::create('complains', function (Blueprint $table) {
            $table->id();
            $table->string('form_no');
            $table->date('tanggal');
            $table->string('dari');
            $table->string('kepada');
            $table->string('name_complain');
            $table->string('company_name');
            $table->string('department');
            $table->string('phone_number');
            $table->string('receive_by');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complains');
    }
};
