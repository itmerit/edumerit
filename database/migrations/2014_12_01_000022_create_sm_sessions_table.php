<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\SmSession;
class CreateSmSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('session', 255);
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();
   
        
            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');         
        });

        $s = new SmSession();
        $s->session = '2020-2021';
        $s->school_id = 1;
        $s->created_by = 1;
        $s->updated_by = 1;
        $s->active_status = 1;
        $s->created_at = date('Y-m-d h:i:s');
        $s->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_sessions');
    }
}
