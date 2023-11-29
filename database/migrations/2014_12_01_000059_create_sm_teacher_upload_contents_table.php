<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmTeacherUploadContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_teacher_upload_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('content_title')->length(200)->nullable();
            $table->string('content_type')->nullable()->comment("as assignment, st study material, sy sullabus, ot others download");
            $table->integer('available_for_admin')->default(0)->nullable();
            $table->integer('available_for_all_classes')->default(0);
            $table->date('upload_date')->nullable();
            $table->string('description')->length(500)->nullable();
            $table->string('source_url')->nullable();
            $table->string('upload_file')->length(200)->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();

            $table->integer('course_id')->nullable();
            $table->integer('parent_course_id')->nullable();
            
            $table->integer('class')->nullable()->unsigned();
            $table->foreign('class')->references('id')->on('sm_classes')->onDelete('cascade');

            $table->integer('section')->nullable();
            // $table->foreign('section')->references('id')->on('sm_sections')->onDelete('cascade');

            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');

            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');
            // $table->created_at->format('Y-m-d');
            // $table->updated_at->format('Y-m-d');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_teacher_upload_contents');
    }
}