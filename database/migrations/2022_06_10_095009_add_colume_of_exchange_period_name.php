<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumeOfExchangePeriodName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exchange_period_name', function (Blueprint $table) {
            if (!Schema::hasColumn('exchange_period_name', 'name_explain')) {
                Schema::table('exchange_period_name', function (Blueprint $table) {
                    $table->string('name_explain', 100)->default('')->after('name');
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
                $table->dropColumn('name_explain');
            });
        });
    }
}
