<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->nullable();
            $table->string('type')->default('System');
            $table->tinyInteger('active_status')->default(1);
            $table->string('created_by')->nullable()->default(1);
            $table->string('updated_by')->nullable()->default(1);
            $table->timestamps();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        });


        DB::table('roles')->insert([
            [
                'name' => 'Super admin',    //      1
                'type' => 'System',
                'school_id' => 1,
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'name' => 'Student',    //      2
                'type' => 'System',
                'school_id' => 1,
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'name' => 'Parents',    //      3
                'type' => 'System',
                'school_id' => 1,
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'name' => 'Teacher',    //      4
                'type' => 'System',
                'school_id' => 1,
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'name' => 'Admin',    //      5
                'type' => 'System',
                'school_id' => 1,
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'name' => 'Accountant',    //      6
                'type' => 'System',
                'school_id' => 1,
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'name' => 'Receptionist',    //      7
                'type' => 'System',
                'school_id' => 1,
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'name' => 'Librarian',    //      8
                'type' => 'System',
                'school_id' => 1,
                'created_at' => date('Y-m-d h:i:s')
            ],
            [
                'name' => 'Driver',    //      9
                'type' => 'System',
                'school_id' => 1,
                'created_at' => date('Y-m-d h:i:s')
            ]

        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }
}
