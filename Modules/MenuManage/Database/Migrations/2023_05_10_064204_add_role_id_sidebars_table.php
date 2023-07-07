<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoleIdSidebarsTable extends Migration
{
    public function up()
    {
        Schema::table('sidebars', function (Blueprint $table) {
            if(!Schema::hasColumn('sidebars', 'role_id')) {
                $table->integer('role_id')->nullable();
            }      

            if(!Schema::hasColumn('sidebars', 'ignore')) {
                $table->integer('ignore')->nullable();
            }          
        });
    }
    
    public function down()
    {
        Schema::table('', function (Blueprint $table) {

        });
    }
}
