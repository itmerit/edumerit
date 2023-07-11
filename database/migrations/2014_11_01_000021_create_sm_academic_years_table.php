<?php

use App\SmAcademicYear;
use App\SmGeneralSettings;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmAcademicYearsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_academic_years', function (Blueprint $table) {
            $table->increments('id');
            $table->string('year', 200);
            $table->string('title', 200);
            $table->date('starting_date');
            $table->date('ending_date');
            $table->string('copy_with_academic_year')->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->string('created_at')->nullable();
            $table->string('updated_at')->nullable();
            $table->integer('created_by')->nullable()->default(1)->unsigned();
            $table->integer('updated_by')->nullable()->default(1)->unsigned();
            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        });

        $year = date('Y');
        $starting_date = $year . '-01-01';
        $ending_date = $year . '-12-30';
        $s = new SmAcademicYear();
        $s->year = $year;
        $s->title = 'Jan-Dec';
        $s->starting_date = $starting_date;
        $s->ending_date = $ending_date;
        $s->created_at = $year . '-01-01';
        $s->updated_at = $year . '-01-01';
        $s->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       // \Schema::dropIfExists('sm_academic_years');
    }
}