<?php

use App\SmSchool;
use App\Models\DirectFeesReminder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDirectFeesRemindersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direct_fees_reminders', function (Blueprint $table) {
            $table->id();
            $table->integer('due_date_before');
            $table->string('notification_types');
            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->timestamps();
        });

        
        $schools = SmSchool::all();
        foreach($schools as $school){
            $data = new DirectFeesReminder();
            $data->due_date_before = 5;
            $data->school_id = $school->id;
            $data->notification_types = '["system"]';
            $data->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('direct_fees_reminders');
    }
}
