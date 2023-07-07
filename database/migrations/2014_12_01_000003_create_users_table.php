<?php

use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name', 192)->nullable();
            $table->string('username', 192)->nullable();
            $table->string('phone_number',191)->nullable();
            $table->string('email', 192)->nullable();
            $table->string('password', 100)->nullable();
            $table->string('usertype', 210)->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->text('random_code')->nullable();
            $table->text('notificationToken')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->string('language')->nullable()->default('en');
            $table->integer('style_id')->nullable()->default(1);
            $table->integer('rtl_ltl')->nullable()->default(2);
            $table->integer('selected_session')->nullable()->default(1);



            $table->integer('created_by')->nullable()->default(1);
            $table->integer('updated_by')->nullable()->default(1);
            $table->integer('access_status')->nullable()->default(1);

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            
            $table->integer('role_id')->nullable()->unsigned();
            $table->foreign('role_id')->references('id')->on('infix_roles')->onDelete('cascade');
            $table->enum('is_administrator', ['yes', 'no'])->default('no');
            $table->tinyInteger('is_registered')->default(0);
            $table->text('device_token')->nullable();


            $table->string('stripe_id')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_last_four', 4)->nullable();
            $table->string('verified')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
        });


        $data = User::find(1);

        if (empty($data)) {
            $user            = new User();
            $user->created_by   = 1;
            $user->updated_by   = 1;
            $user->school_id   = 1;
            $user->role_id   = 1;
            $user->full_name = 'admin';
            $user->email     = 'admin@infixedu.com';
            $user->is_administrator     = 'yes';
            $user->username  = 'admin@infixedu.com';
            $user->password  = Hash::make('123456');
            $user->created_at = date('Y-m-d h:i:s');
            $user->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
