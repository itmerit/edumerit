<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmContactPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_contact_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->string('address')->nullable();
            $table->string('address_text')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_text')->nullable();
            $table->string('email')->nullable();
            $table->string('email_text')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->integer('zoom_level')->nullable();
            $table->string('google_map_address')->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();

            $table->integer('created_by')->nullable()->default(1)->unsigned();

            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
        });
        DB::table('sm_contact_pages')->insert([
            [
                'title' => 'Contact Us',
                'description' => 'Have any questions? We’d love to hear from you! Here’s how to get in touch with us.',
                'image' => 'public/uploads/contactPage/contact.jpg',
                'button_text' => 'Learn More About Us',
                'button_url' => 'about',
                'address' => '56/8 Panthapath, Dhanmondi,Dhaka',
                'address_text' => 'Santa monica bullevard',
                'phone' => '01841136251',
                'phone_text' => 'Mon to Fri 9am to 6 pm',
                'email' => 'admin@infixedu.com',
                'email_text' => 'Send us your query anytime!',
                'latitude' => '23.707310',
                'longitude' => '90.415480',
                'zoom_level' => 15,
                'google_map_address' => 'Panthapath, Dhaka',
                'school_id' => 1,
            ],
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_contact_pages');
    }
}
