<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberVipStroeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_vip_stroe', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('buysafeno', 45)->nullable();//訂單編號
            $table->string('store_code', 45)->nullable();//商店定編
            $table->string('type', 45)->nullable();//paycode 代碼繳款 Barcode 商店代收
            $table->string('ChkValue', 255)->nullable();
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
        Schema::dropIfExists('member_vip_stroe');
    }
}
