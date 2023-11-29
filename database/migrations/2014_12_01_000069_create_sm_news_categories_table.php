<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmNewsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_news_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('category_name');
            $table->string('type')->default('news');
            $table->timestamps();

            $table->unsignedBigInteger('school_id')->default(1)->unsigned();
            // $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        
        });

        DB::table('sm_news_categories')->insert([
            [
                'category_name' => 'International',    //      1
                'school_id' => '1', 
                'type'=>'news'   
            ],
            [
                'category_name' => 'Our history',   //      3
                'school_id' => '1',
                'type'=>'history'
            ],
            [
                'category_name' => 'Our mission and vision',   //      3
                'school_id' => '1',
                'type'=>'mission'
            ],
            [
                'category_name' => 'National',   //      2
                'school_id' => '1',
                'type'=>'news'   

            ],
            [
                'category_name' => 'Sports',   //      3
                'school_id' => '1',
                'type'=>'news'   
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_news_categories');
    }
}