<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateColorThemeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('color_theme', function (Blueprint $table) {
            $table->id();
            $table->foreignId('color_id')->nullable();
            $table->string('value')->nullable();
            $table->foreign('color_id')->on('colors')->references('id')->onDelete('cascade');
            $table->foreignId('theme_id')->nullable();
            $table->foreign('theme_id')->on('themes')->references('id')->onDelete('cascade');
            $table->timestamps();
        });

        $themes = \App\Models\Theme::withOutGlobalScopes()->get();
        $sql = [];
        foreach($themes as $theme){
            if($theme->title == 'Default') { 
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 1, 'value'   => "#415094"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 2, 'value'  => "#7c32ff"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 3, 'value'  => "#c738d8"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 4, 'value'  => "#7c32ff"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 5, 'value'  => "#828bb2"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 6, 'value'  => "#828bb2"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 7, 'value'  => "#ffffff"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 8, 'value'  => "#ffffff"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 9, 'value'  => "#000000"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 10, 'value' => "#000000"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 11, 'value' => "#c738d8"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 12, 'value' => "#ffffff"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 13, 'value' => "#51A351"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 14, 'value' => "#E09079"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 15, 'value' => "#FF6D68"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 16, 'value' => "#415094"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 17, 'value'  => "#222222"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 18, 'value'  => "#415094"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 19, 'value'  => "transparent"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 20, 'value'  => "#4c5c9b"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 21, 'value'  => "#415094"];
                $sql[] = ['theme_id'  => $theme->id, 'color_id' => 29, 'value'  => "#415094"];
        
            }
        }

        DB::table('color_theme')->insert($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('color_theme', function (Blueprint $table) {
            $table->dropForeign('color_theme_color_id_foreign');
            $table->dropForeign('color_theme_theme_id_foreign');
        });
        Schema::dropIfExists('color_theme');
    }
}
