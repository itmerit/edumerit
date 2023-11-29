<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeesXtraTable extends Migration
{
    public function up()
    {
        $column = "record_id";
        if (!Schema::hasColumn('fm_fees_invoices', $column)) {
            Schema::table('fm_fees_invoices', function (Blueprint $table) use ($column) {
                $table->foreignId($column)->unsigned()->nullable();
            });
        }

        if (!Schema::hasColumn('fm_fees_transactions', $column)) {
            Schema::table('fm_fees_transactions', function (Blueprint $table) use ($column) {
                $table->foreignId($column)->unsigned()->nullable();
            });
        }

        Schema::table('fm_fees_types', function (Blueprint $table) {
            if (!Schema::hasColumn('fm_fees_types', 'type')) {
                $table->string('type')->nullable()->default("fees")->comment('fees, lms');
            }
        });

        Schema::table('fm_fees_invoices', function (Blueprint $table) {
            if (!Schema::hasColumn('fm_fees_invoices', 'type')) {
                $table->string('type')->nullable()->default("fees")->comment('fees, lms');
            }
        });

        Schema::table('fm_fees_transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('fm_fees_transactions', 'total_paid_amount')) {
                $table->string('total_paid_amount')->nullable();
            }
            
        });
    }

    public function down()
    {
        $column = "record_id";
        if (Schema::hasColumn('fm_fees_invoices', $column)) {
            Schema::table('fm_fees_invoices', function (Blueprint $table) use ($column) {
                $table->dropColumn($column);
            });
        }

        if (Schema::hasColumn('fm_fees_transactions', $column)) {
            Schema::table('fm_fees_transactions', function (Blueprint $table) use ($column) {
                $table->dropColumn($column);
            });
        }
        Schema::table('fm_fees_types', function (Blueprint $table) {
            if (Schema::hasColumn('fm_fees_types', 'type')) {
                $table->dropColumn('type');
            }
        });
        Schema::table('fm_fees_invoices', function (Blueprint $table) {
            if (Schema::hasColumn('fm_fees_invoices', 'type')) {
                $table->dropColumn('type');
            }
        });

        Schema::table('fm_fees_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('fm_fees_transactions', 'total_paid_amount')) {
                $table->dropColumn('total_paid_amount');
            }
        });
    }
}
