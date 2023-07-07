<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddServiceChargeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('fm_fees_transactions', function (Blueprint $table) {
            if(!Schema::hasColumn('fm_fees_transactions', 'service_charge')){
                $table->float('service_charge')->nullable();
            }
        });
        Schema::table('fm_fees_invoice_chields', function (Blueprint $table) {
            if(!Schema::hasColumn('fm_fees_invoice_chields', 'service_charge')){
                $table->float('service_charge')->after('paid_amount')->nullable();
            }
        });
        Schema::table('fm_fees_transaction_chields', function (Blueprint $table) {
            if(!Schema::hasColumn('fm_fees_transaction_chields', 'service_charge')){
                $table->float('service_charge')->after('paid_amount')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('', function (Blueprint $table) {

        });
    }
}
