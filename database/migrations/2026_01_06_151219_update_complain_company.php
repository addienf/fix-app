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
        //
        Schema::table('complains', function (Blueprint $table) {
            // $table->dropForeign(['complain_id']);
            $table->dropColumn('company_name');

            $table->foreignId('company_id')
                ->nullable()
                ->after('name_complain')
                ->constrained('companies')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
