<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\RolePermission\Entities\InfixModuleInfo;

class CreateInfixModuleInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infix_module_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('module_id')->nullable();
            $table->string('module_name')->nullable();
            $table->integer('parent_id')->nullable()->default(0);
            $table->string('name')->nullable();
            $table->tinyInteger('is_saas')->default(0);
            $table->string('route')->nullable();
            $table->string('parent_route')->nullable();
            $table->string('lang_name')->nullable();
            $table->string('icon_class')->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->integer('created_by')->nullable()->default(1)->unsigned();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

            $table->integer('updated_by')->nullable()->default(1)->unsigned();
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

            $table->integer('school_id')->nullable()->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');

            $table->integer('type')->nullable()->comment('1 for module, 2 for module link, 3 for module links crud');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infix_module_infos');
    }
}
