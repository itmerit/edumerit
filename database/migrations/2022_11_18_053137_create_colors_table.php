<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->boolean('is_color')->nullable()->default(true);
            $table->boolean('status')->nullable()->default(true);
            $table->string('default_value')->nullable();
            $table->string('lawn_green')->nullable();
            $table->timestamps();
        });
        $sql = [
            ['name'  => "base_color", 'default_value' => "#415094" , 'lawn_green' =>'#415094', 'is_color'=>1, 'status'=>1],
            ['name'  => "gradient_1", 'default_value' => "#7c32ff" , 'lawn_green' =>'#03e396', 'is_color'=>1, 'status'=>1],
            ['name'  => "gradient_2", 'default_value' => "#c738d8" , 'lawn_green' =>'#03e396', 'is_color'=>1, 'status'=>1],
            ['name'  => "gradient_3", 'default_value' => "#7c32ff" , 'lawn_green' =>'#03e396', 'is_color'=>1, 'status'=>1],

            ['name'  => "scroll_color", 'default_value' => "#828bb2" , 'lawn_green' =>'#7e7172', 'is_color'=>1, 'status'=>1],
            ['name'  => "text-color", 'default_value' => "#828bb2" , 'lawn_green' =>'#828bb2', 'is_color'=>1, 'status'=>1],
            ['name'  => "text_white", 'default_value' => "#ffffff" , 'lawn_green' =>'#ffffff', 'is_color'=>1, 'status'=>1],
            ['name'  => "bg_white", 'default_value' => "#ffffff" , 'lawn_green' =>'#ffffff', 'is_color'=>1, 'status'=>1],
            ['name'  => "text_black", 'default_value' => "#000000" , 'lawn_green' =>'#000000', 'is_color'=>1, 'status'=>1],
            ['name'  => "bg_black", 'default_value' => "#000000" , 'lawn_green' =>'#000000', 'is_color'=>1, 'status'=>1],
            ['name'  => "border_color", 'default_value' => "#c738d8" , 'lawn_green' =>'#03e396', 'is_color'=>1, 'status'=>1],
            ['name'  => "input_bg", 'default_value' => "#ffffff" , 'lawn_green' =>'#ffffff', 'is_color'=>1, 'status'=>0],

            ['name'  => "success", 'default_value' => "#51A351" , 'lawn_green' =>'#51A351', 'is_color'=>1, 'status'=>1],
            ['name'  => "warning", 'default_value' => "#E09079" , 'lawn_green' =>'#E09079', 'is_color'=>1, 'status'=>1],
            ['name'  => "danger", 'default_value' => "#FF6D68" , 'lawn_green' =>'#FF6D68', 'is_color'=>1, 'status'=>1],

            ['name'  => "primary_color", 'default_value' => '#415094' , 'lawn_green' =>'#415094', 'is_color'=>1, 'status'=>0],
            ['name'  => "primary_color2", 'default_value' => '#222222' , 'lawn_green' =>'#222222', 'is_color'=>1, 'status'=>0],
            ['name'  => "title_color", 'default_value' => '#415094' , 'lawn_green' =>'#415094', 'is_color'=>1, 'status'=>1],
            ['name'  => "sidebar_bg", 'default_value' => '#e7ecff' , 'lawn_green' =>'#ffffff', 'is_color'=>1, 'status'=>1],
            ['name'  => "sidebar_active", 'default_value' => '#4c5c9b' , 'lawn_green' =>'#e7ecff', 'is_color'=>1, 'status'=>1],
            ['name'  => "sidebar_hover", 'default_value' => '#415094' , 'lawn_green' =>'#e7ecff', 'is_color'=>1, 'status'=>1],
            ['name'  => "barchart1", 'default_value' => '#8a33f8' , 'lawn_green' =>'#8a33f8', 'is_color'=>1, 'status'=>0],
            ['name'  => "barchart2", 'default_value' => '#f25278' , 'lawn_green' =>'#f25278', 'is_color'=>1, 'status'=>0],
            ['name'  => "barcharttextcolor", 'default_value' => '#415094' , 'lawn_green' =>'#415094', 'is_color'=>1, 'status'=>0],
            ['name'  => "barcharttextfamily", 'default_value' => '"poppins", sans-serif', 'lawn_green' =>'"poppins", sans-serif', 'is_color'=>0, 'status'=>0],
            ['name'  => "areachartlinecolor1", 'default_value' => 'rgba(124, 50, 255, 0.5)', 'lawn_green' =>'rgba(124, 50, 255, 0.5)', 'is_color'=>1, 'status'=>0],
            ['name'  => "areachartlinecolor2", 'default_value' => 'rgba(242, 82, 120, 0.5)', 'lawn_green' =>'rgba(242, 82, 120, 0.5)', 'is_color'=>1, 'status'=>0],
            ['name'  => "dashboardbackground", 'default_value' => '', 'lawn_green' =>'', 'is_color'=>0, 'status'=>0],
            ['name'  => "box_shadow", 'default_value' => 'rgb(226 222 227)', 'lawn_green' =>'', 'is_color'=>1, 'status'=>0],

        ];

        DB::table('colors')->insert($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('colors');
    }
}
