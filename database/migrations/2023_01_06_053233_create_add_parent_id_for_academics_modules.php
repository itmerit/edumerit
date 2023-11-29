<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddParentIdForAcademicsModules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $name ="parent_id";

        if (!Schema::hasColumn('sm_sections', $name)) {
            Schema::table('sm_sections', function ($table) use ($name) {
                $table->integer($name)->nullable();
            });
        }
        if (!Schema::hasColumn('sm_classes', $name)) {
            Schema::table('sm_classes', function ($table) use ($name) {
                $table->integer($name)->nullable();
            });
        }
        if (!Schema::hasColumn('sm_class_sections', $name)) {
            Schema::table('sm_class_sections', function ($table) use ($name) {
                $table->integer($name)->nullable();
            });
        }

        if (!Schema::hasColumn('sm_subjects', $name)) {
            Schema::table('sm_subjects', function ($table) use ($name) {
                $table->integer($name)->nullable();
            });
        }

        if (!Schema::hasColumn('sm_assign_subjects', $name)) {
            Schema::table('sm_assign_subjects', function ($table) use ($name) {
                $table->integer($name)->nullable();
            });
        }
        if (!Schema::hasColumn('sm_teacher_upload_contents', $name)) {
            Schema::table('sm_teacher_upload_contents', function ($table) use ($name) {
                $table->integer($name)->nullable();
            });
        }

        if (!Schema::hasColumn('sm_exams', $name)) {
            Schema::table('sm_exams', function ($table) use ($name) {
                $table->integer($name)->nullable();
            });
        }

        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('add_parent_id_for_academics_modules');
    }
}
