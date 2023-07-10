<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBehaviouralAppraisalToSmTemporaryMeritlistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sm_temporary_meritlists', function (Blueprint $table) {
            $table->dropColumn('behavioural_appraisal');
        });
        Schema::table('sm_temporary_meritlists', function (Blueprint $table) {
            $table->decimal('behavioural_appraisal')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sm_temporary_meritlists', function (Blueprint $table) {
            $table->dropColumn('behavioural_appraisal');
        });
    }
}
