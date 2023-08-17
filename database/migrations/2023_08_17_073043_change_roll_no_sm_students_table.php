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
        Schema::table('sm_students', function (Blueprint $table) {
            $table->dropColumn('roll_no');
        });
        Schema::table('sm_students', function (Blueprint $table) {
            $table->string('roll_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sm_students', function (Blueprint $table) {
            $table->dropColumn('roll_no');
        });
        Schema::table('sm_students', function (Blueprint $table) {
            $table->integer('roll_no')->nullable();
        });
    }
};
