<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Define the schema for the first table
        Schema::table('sm_staff_attendences', function (Blueprint $table) {
            $table->timestamp('attendance_time')->nullable();
        });

        // Define the schema for the second table
        Schema::table('sm_student_attendances', function (Blueprint $table) {
            $table->timestamp('attendance_time')->nullable();
        });
    }

    public function down()
    {
        Schema::table('sm_staff_attendences', function (Blueprint $table) {
            $table->dropColumn('attendance_time');
        });

        Schema::table('sm_student_attendences', function (Blueprint $table) {
            $table->dropColumn('attendance_time');
        });
    }
};
