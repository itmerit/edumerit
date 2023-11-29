<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTranscationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transcations', function (Blueprint $table) {
            $table->integer('id');
            $table->text('title')->nullable();
            $table->string('type', 20)->default('debit');
            $table->string('payment_method', 20)->nullable();
            $table->string('reference', 20)->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('morphable_id')->nullable();
            $table->string('morphable_type')->nullable();
            $table->bigInteger('amount')->default(0);
            $table->date('transaction_date')->nullable();
            $table->integer('user_id')->nullable()->unsigned();
            $table->foreign('user_id')->on('users')->references('id')->onDelete('set null');
            $table->integer('school_id')->default(1);
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
        Schema::dropIfExists('transcations');
    }
}
