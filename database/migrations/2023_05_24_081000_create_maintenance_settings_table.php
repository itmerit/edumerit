<?php

use App\SmSchool;
use App\Models\MaintenanceSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('maintenance_settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default('We will be back soon!')->nullable();
            $table->string('sub_title')->default('Sorry for the inconvenience but we are performing some maintenance at the moment.')->nullable();
            $table->string('image')->nullable();
            $table->string('applicable_for')->nullable();
            $table->boolean('maintenance_mode')->nullable()->default(0);
            $table->integer('school_id')->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->timestamps();
        });

        
       
            $new = new MaintenanceSetting();
            $new->save();
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_settings');
    }
};
