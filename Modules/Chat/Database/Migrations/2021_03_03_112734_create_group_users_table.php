<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_group_users', function (Blueprint $table) {
            $table->id();
            $table->uuid('group_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('role')->default(1);
            $table->unsignedBigInteger('added_by');
            $table->unsignedBigInteger('removed_by')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();

//            $table->foreign('group_id')
//                ->references('id')->on('chat_groups')
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
//
//            $table->foreign('user_id')
//                ->references('id')->on('users')
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
//
//            $table->foreign('removed_by')
//                ->references('id')->on('users')
//                ->onUpdate('cascade')
//                ->onDelete('cascade');
//
//            $table->foreign('added_by')
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
        Schema::dropIfExists('chat_group_users');
    }
}
