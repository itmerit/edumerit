<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_invitations', function (Blueprint $table) {
            $table->id();
            $table->integer('from')->unsigned();
            $table->integer('to')->unsigned();
            $table->tinyInteger('status')->default(0)
                ->comment('0- pending, 1- connected, 2- blocked');
            $table->timestamps();

//            $table->foreign('from')->references('id')->on('users');
//            $table->foreign('to')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_invitations');
    }
}
