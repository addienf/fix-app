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
        Schema::create('persetujuan_perubahans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('perubahan_id')->constrained('perubahan_informasis')->onDelete('cascade');
            $table->string('persetujuan_pic');
            $table->string('alasan_penolakan_pic');
            $table->string('signature_pic');
            $table->string('signature_pic_date');
            $table->string('persetujuan_manajemen');
            $table->string('alasan_penolakan_manajemen');
            $table->string('signature_manajemen');
            $table->string('signature_manajemen_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persetujuan_perubahans');
    }
};
