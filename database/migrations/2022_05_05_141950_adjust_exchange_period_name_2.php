<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AdjustExchangePeriodName2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('exchange_period_name')->where('id', '1')->update(['name' => '中長期為主(一個月以上)','remark' => '此選項站方會幫您管制短期約會的罐頭訊息。如果您收到短約的罐頭訊息，請直接檢舉。']);
        DB::table('exchange_period_name')->where('id', '2')->update(['remark' => '此選項站方將不進行任何管制。']);
        DB::table('exchange_period_name')->where('id', '3')->update(['name' => '短期為主(一個月內)','remark' => '此選項站方將不進行任何管制。']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('exchange_period_name')->where('id', '1')->update(['name' => '長期為主','remark' => '']);
        DB::table('exchange_period_name')->where('id', '2')->update(['remark' => '']);
        DB::table('exchange_period_name')->where('id', '3')->update(['name' => '單次為主','remark' => '']);
    }
}
