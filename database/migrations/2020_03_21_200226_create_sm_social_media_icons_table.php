<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmSocialMediaIconsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_social_media_icons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url')->nullable();
            $table->string('icon')->nullable();
            $table->tinyInteger('status')->default(0)->comment('1 active, 0 inactive');
            $table->timestamps();

            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');

        });

        DB::table('sm_social_media_icons')->insert([
            [
                'url' => 'https://www.facebook.com/Spondonit',
                'icon' => 'fa fa-facebook',
                'status' => 1,
            ],
            [
                'url' => 'https://www.facebook.com/Spondonit',
                'icon' => 'fa fa-twitter',
                'status' => 1,
            ],
            [
                'url' => 'https://www.facebook.com/Spondonit',
                'icon' => 'fa fa-dribbble',
                'status' => 1,
            ],
            [
                'url' => 'https://www.facebook.com/Spondonit',
                'icon' => 'fa fa-linkedin',
                'status' => 1,
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
        Schema::dropIfExists('sm_social_media_icons');
    }
}