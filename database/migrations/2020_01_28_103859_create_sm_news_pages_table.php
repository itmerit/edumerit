<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmNewsPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_news_pages', function (Blueprint $table) {
        $table->increments('id');
        $table->timestamps();
        $table->string('title')->nullable();
        $table->text('description')->nullable();
        $table->string('main_title')->nullable();
        $table->text('main_description')->nullable();
        $table->string('image')->nullable();
        $table->string('main_image')->nullable();
        $table->string('button_text')->nullable();
        $table->string('button_url')->nullable();
        $table->tinyInteger('active_status')->default(1);

        $table->integer('created_by')->nullable()->default(1)->unsigned();
        $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');

        $table->integer('updated_by')->nullable()->default(1)->unsigned();
        $table->foreign('updated_by')->references('id')->on('users')->onDelete('cascade');

        $table->integer('school_id')->nullable()->default(1)->unsigned();
        $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
    });
    DB::table('sm_news_pages')->insert([
        [
            'title' => 'News Infix',
            'description' => 'Lisus consequat sapien metus dis urna, facilisi. Nonummy rutrum eu lacinia platea a, ipsum parturient, orci tristique. Nisi diam natoque.',
            'image' => 'public/uploads/about_page/about.jpg',
            'button_text' => 'Learn More News ',
            'button_url' => 'news-page',
            'main_title'=>'Under Graduate Education',
            'main_description'=>'INFIX has all in one place. You’ll find everything what you are looking into education management system software. We care! User will never bothered in our real eye catchy user friendly UI & UX  Interface design. You know! Smart Idea always comes to well planners. And Our INFIX is Smart for its Well Documentation. Explore in new support world! It’s now faster & quicker. You’ll find us on Support Ticket, Email, Skype, WhatsApp.',
            'main_image'=>'public/uploads/about_page/about-img.jpg',
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
        Schema::dropIfExists('sm_news_pages');
    }
}