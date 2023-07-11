<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fees_carry_forward_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('student_record_id');
            $table->text('note');
            $table->float('amount');
            $table->string('amount_type');
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->string('type');
            $table->timestamp('date');
            $table->integer('school_id')->nullable()->default(1)->unsigned();
            $table->foreign('school_id')->references('id')->on('sm_schools')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fees_carry_forward_logs');
    }
};
