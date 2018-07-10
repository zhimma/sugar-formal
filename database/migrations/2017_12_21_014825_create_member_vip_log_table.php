<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberVipLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_vip_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id');
            $table->string('member_name');
            // $table->integer('business_id');
            // $table->integer('amount');
            $table->string('txn_id', 128);
            $table->integer('action');
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
        Schema::dropIfExists('member_vip_log');
    }
}
