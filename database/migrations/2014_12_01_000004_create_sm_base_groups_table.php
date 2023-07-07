<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmBaseGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_base_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 200);
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();

            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        });

        DB::table('sm_base_groups')->insert([
            [
                'name' => 'Gender',
                'created_at' => date('Y-m-d h:i:s'),
            ],
            [
                'name' => 'Religion',
                'created_at' => date('Y-m-d h:i:s'),
            ],
            [
                'name' => 'Blood Group',
                'created_at' => date('Y-m-d h:i:s'),
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
        Schema::dropIfExists('sm_base_groups');
    }
}
