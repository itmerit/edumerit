<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_access_tokens', function (Blueprint $table) {
            $table->string('id');
            $table->bigInteger('user_id')->index()->nullable();
            $table->unsignedInteger('client_id');
            $table->string('name', 100)->nullable();
            $table->string('scopes', 100)->nullable();
            $table->string('revoked', 100);
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
        });
    }

    /**`
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oauth_access_tokens');
    }
}
