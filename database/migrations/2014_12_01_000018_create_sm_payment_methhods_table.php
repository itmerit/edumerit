<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmPaymentMethhodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sm_payment_methhods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('method', 255);
            $table->string('type')->nullable();
            $table->tinyInteger('active_status')->default(1);
            $table->timestamps();

            $table->integer('gateway_id')->nullable()->unsigned();
            $table->foreign('gateway_id')->references('id')->on('sm_payment_gateway_settings')->onDelete('cascade');

            $table->integer('created_by')->nullable()->default(1)->unsigned();
            $table->integer('updated_by')->nullable()->default(1)->unsigned();

            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');

        });


        DB::table('sm_payment_methhods')->insert([
            [
                'method' => 'Cash',
                'type' => 'System',
                'created_at' => date('Y-m-d h:i:s'),
            ],
            [
                'method' => 'Cheque',
                'type' => 'System',
                'created_at' => date('Y-m-d h:i:s'),
            ],
            [
                'method' => 'Bank',
                'type' => 'System',
                'created_at' => date('Y-m-d h:i:s'),
            ],
            [
                'method' => 'PayPal',
                'type' => 'System',
                'created_at' => date('Y-m-d h:i:s'),
            ],

            [
                'method' => 'Stripe',
                'type' => 'System',
                'created_at' => date('Y-m-d h:i:s'),
            ],
            [
                'method' => 'Paystack',
                'type' => 'System',
                'created_at' => date('Y-m-d h:i:s'),
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sm_payment_methhods');
    }
}
