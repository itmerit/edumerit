<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_block_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('block_by');
            $table->unsignedBigInteger('block_to');
            $table->timestamps();

//            $table->foreign('block_by')
//                ->references('id')->on('users')
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
//
//            $table->foreign('block_to')
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
        Schema::dropIfExists('chat_block_users');
    }
}
