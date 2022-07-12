<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdjustExchangePeriodName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange_period_name', function (Blueprint $table) {
            if (!Schema::hasColumn('exchange_period_name', 'remark')) {
                Schema::table('exchange_period_name', function (Blueprint $table) {
                    $table->string('remark', 100)->default('')->after('name');
                });
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
        Schema::table('exchange_period_name', function (Blueprint $table) {
            Schema::table('exchange_period_name', function (Blueprint $table) {
                $table->dropColumn('remark');
            });
        });
    }
}
