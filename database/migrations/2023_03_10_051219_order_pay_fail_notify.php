<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OrderPayFailNotify extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasTable('order_pay_fail_notify')) {
            Schema::create('order_pay_fail_notify', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->string('order_id', 32);
                $table->timestamp('last_pay_fail_date');
                $table->tinyInteger('status')->default(1)->comment('1:通知中; 0:已忽略;');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('order_pay_fail_notify');
    }
}
