<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\SmMarksGrade;

class CreateSmMarksGradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_marks_grades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('grade_name')->nullable();
            $table->float('gpa')->nullable();
            $table->float('from')->nullable();
            $table->float('up')->nullable();
            $table->float('percent_from')->nullable();
            $table->float('percent_upto')->nullable();
            $table->text("description")->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();
            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');

            $table->integer('academic_id')->nullable()->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');
        });

        $data = [
            ['A+',  '5.00',  5.00,    5.99,   80, 100,     'Outstanding !'],
            ['A',  '4.00',  4.00,    4.99,   70, 79.99,      'Very Good !'],
            ['A-',  '3.50',  3.50,    3.99,   60, 69.99,      'Good !'],
            ['B',  '3.00',  3.00,    3.49,   50, 59.99,     'Outstanding !'],
            ['C',  '2.00',  2.00,    2.99,   40, 49.99,      'Bad !'],
            ['D',  '1.00',  1.00,    1.99,   33, 39.99,      'Very Bad !'],
            ['F',  '0.00',  0.00,    0.99,   0, 32.99,       'Failed !'],
        ];
        foreach ($data as $r) {
            $store = new SmMarksGrade();
            $store->academic_id          = 1;
            $store->grade_name          = $r[0];
            $store->gpa                 = $r[1];
            $store->from                = $r[2];
            $store->up                  = $r[3];
            $store->percent_from        = $r[4];
            $store->percent_upto        = $r[5];
            $store->description         = $r[6];
            $store->created_at         =  date('Y-m-d h:i:s');
            $store->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_marks_grades');
    }
}
