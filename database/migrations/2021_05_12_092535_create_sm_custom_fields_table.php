<?php

use App\SmGeneralSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmCustomFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_custom_fields', function (Blueprint $table) {
            $table->id();
            $table->string('form_name');
            $table->string('label');
            $table->string('type');
            $table->string('min_max_length')->nullable();
            $table->string('min_max_value')->nullable();
            $table->string('name_value')->nullable();
            $table->string('width')->nullable();
            $table->tinyInteger('required')->nullable();
            $table->integer('school_id')->nullable()->default(1);
            $table->integer('academic_id')->default(1);
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
        Schema::dropIfExists('sm_custom_fields');
    }
}
