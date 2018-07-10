<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberVipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_vip', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->unsigned()->index();
            $table->integer('business_id')->unsigned();
            $table->integer('order_id')->unsigned();
            $table->integer('txn_id')->unsigned();
            $table->integer('amount')->unsigned();
            $table->dateTime('expiry');
            $table->integer('active');
            $table->integer('free');
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
        Schema::table('member_vip', function (Blueprint $table) {
            //
        });
    }
}
