<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmItemReceiveChildrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_item_receive_children', function (Blueprint $table) {
            $table->increments('id');
            $table->float('unit_price')->nullable();
            $table->float('quantity')->nullable();
            $table->float('sub_total')->nullable();
            $table->string('description')->length('500')->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();


            $table->integer('item_id')->nullable()->unsigned();
            $table->foreign('item_id')->references('id')->on('sm_items')->onDelete('cascade');

            $table->integer('item_receive_id')->nullable()->unsigned();
            $table->foreign('item_receive_id')->references('id')->on('sm_item_receives')->onDelete('cascade');

            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');   
            
            $table->integer('academic_id')->nullable()->default(1)->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_item_receive_children');
    }
}
