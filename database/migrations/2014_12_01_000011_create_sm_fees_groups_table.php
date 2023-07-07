<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmFeesGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_fees_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200)->nullable();
            $table->string('type', 200)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('due_date')->nullable();
            $table->text('description', 200)->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();

            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');

            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');

            $table->integer('un_semester_label_id')->nullable();
        });

        // DB::table('sm_fees_groups')->insert([
        //     [
        //         'name' => 'Transport Fee',
        //         'type' => 'System',
        //         'created_by' => 1,
        //         'created_by' => 1,
        //         'school_id' => 1,
        //         'description' => 'System Automatic created. This fees will come from transport section',
        //     ],
        //     [
        //         'name' => 'Dormitory Fee',
        //         'type' => 'System',
        //         'created_by' => 1,
        //         'created_by' => 1,
        //         'school_id' => 1,
        //         'description' => 'System Automatic created. This fees will come from dormitory section',
        //     ]
        // ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_fees_groups');
    }
}
