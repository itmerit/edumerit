<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->index()->nullable();
            $table->string('provider')->nullable();
            $table->string('name', 191);
            $table->string('secret', 200);
            $table->text('redirect');
            $table->boolean('personal_access_client');
            $table->boolean('password_client');
            $table->boolean('revoked');
            $table->timestamps();
        });

        $redirect_url = url('/');

        $oauth = new \App\Models\OauthClient;
        $oauth->provider = null;
        $oauth->name = 'Laravel Personal Access Client';
        $oauth->secret = '2e1LEl0zBTmD8XN4sa0meCTtKslUBpShKW4AGrej';
        $oauth->redirect = $redirect_url;
        $oauth->personal_access_client = 1;
        $oauth->password_client = 0;
        $oauth->revoked = 0;
        $oauth->saveQuietly();

        $oauth = new \App\Models\OauthClient;
        $oauth->provider = 'users';
        $oauth->name = 'Laravel Password Grant Client';
        $oauth->secret = 'oDaHAi0ml3To8OC7Da10TGVUm7zjhMyq00cmwoDZ';
        $oauth->redirect = $redirect_url;
        $oauth->personal_access_client = 0;
        $oauth->password_client = 1;
        $oauth->revoked = 0;
        $oauth->saveQuietly();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oauth_clients');
    }
}
