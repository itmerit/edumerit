<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupMessageRemovesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_group_message_removes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_message_recipient_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

//            $table->foreign('group_message_recipient_id')
//                ->references('id')->on('chat_group_message_recipients')
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
//            $table->foreign('user_id')
//                ->references('id')->on('users')
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chat_group_message_removes');
    }
}
