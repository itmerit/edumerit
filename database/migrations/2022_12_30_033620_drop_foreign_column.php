<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropForeignColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            DB::statement('ALTER TABLE `sm_exam_schedules` DROP FOREIGN KEY sm_exam_schedules_room_id_foreign');
            DB::statement('ALTER TABLE `sm_exam_schedules` DROP INDEX `sm_exam_schedules_room_id_foreign`');
        } catch (\Throwable $th) {
            //throw $th;
        }
        
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
