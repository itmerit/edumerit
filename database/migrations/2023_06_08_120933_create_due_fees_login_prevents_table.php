<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        
        Schema::create('due_fees_login_prevents', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->nullable()->nullable()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('role_id')->nullable()->unsigned();
            $table->foreign('role_id')->references('id')->on('infix_roles')->onDelete('cascade');
            $table->integer('school_id')->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->integer('academic_id')->nullable()->unsigned();
            $table->foreign('academic_id')->references('id')->on('sm_academic_years')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('sm_general_settings', function (Blueprint $table) {
            if(!Schema::hasColumn('sm_general_settings', 'due_fees_login')) {
                $table->boolean('due_fees_login')->nullable()->default(0);
            }
            
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('due_fees_login_prevents');
    }
};
