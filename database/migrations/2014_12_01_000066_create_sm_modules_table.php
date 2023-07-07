<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->tinyInteger('active_status')->default(1);
            $table->integer('order');
            $table->timestamps();

            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');

        });

        $modules = ['Dashboard', 'Admin Section', 'Student Information', 'Teacher', 'Fees Collection', 'Accounts', 'Human resource', 'Leave Application', 'Examination', 'Academics', 'HomeWork', 'Communicate', 'Library', 'Inventory', 'Transport', 'Dormitory', 'Reports', 'System Settings', 'Common','Lesson'];

        $count = 0;
        foreach ($modules as $module) {
            DB::table('sm_modules')->insert([
                [
                    'name' => $module,
                    'order' => $count++,
                    'created_at' => date('Y-m-d h:i:s')
                ]
            ]);
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_modules');
    }
}
