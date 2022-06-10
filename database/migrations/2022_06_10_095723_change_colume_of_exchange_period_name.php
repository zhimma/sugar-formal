<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumeOfExchangePeriodName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('exchange_period_name')->where('id', '1')->update(['name' => '中長期為主','name_explain' => '(一個月以上)']);
        DB::table('exchange_period_name')->where('id', '3')->update(['name' => '短期為主','name_explain' => '(一個月內)']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('exchange_period_name')->where('id', '1')->update(['name' => '中長期為主(一個月以上)','name_explain' => '']);
        DB::table('exchange_period_name')->where('id', '3')->update(['name' => '短期為主(一個月內)','name_explain' => '']);
    }
}
