<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVipExpiryLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_vip_expiry_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vip_log_id');
            $table->integer('member_id');
            $table->timestamp('expire_origin')->nullable();
            $table->timestamp('expiry')->nullable();
            $table->integer('remain_days_origin')->nullable()->default(0);
            $table->integer('remain_days')->nullable()->default(0);
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
        Schema::dropIfExists('member_vip_expiry_log');
    }
}
