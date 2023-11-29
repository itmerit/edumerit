<?php

use App\SmSchool;
use App\Models\Theme;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThemesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('path_main_style', 255)->nullable();
            $table->string('path_infix_style', 255)->nullable();
            $table->string('replicate_theme', 255)->nullable();
            $table->string('color_mode')->default('gradient');
            $table->boolean('box_shadow')->nullable()->default(true);
            $table->string('background_type')->default('image');
            $table->string('background_color')->nullable();
            $table->string('background_image')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_system')->default(false);
            $table->integer('created_by')->nullable();
            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->timestamps();
        });

      

        $schools = SmSchool::all();
        $default_themes = ['Default'];
        foreach ($schools as $school) {
            foreach($default_themes as $key=>$item) {
                $theme = Theme::updateOrCreate([
                    'title'=>$item,
                    'school_id'=>$school->id
                ]);
                $theme->path_main_style = 'style.css';
                $theme->path_infix_style = 'infix.css';
                $theme->is_default = $key == 0 ? 1: 0;
                $theme->color_mode = "gradient";
                $theme->background_type = "image";
                $theme->background_image = asset('/public/backEnd/img/body-bg.jpg');
                $theme->is_system = true;
                $theme->created_by = 1;
                $theme->school_id = $school->id;
                $theme->save();                
            }                
            
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('themes');
    }
}
