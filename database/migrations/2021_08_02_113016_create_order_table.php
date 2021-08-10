<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->string('order_id', 32)->primary();
            $table->integer('user_id');
            $table->timestamp('order_date')->nullable();
            $table->timestamp('order_expire_date')->nullable();
            $table->string('service_name', 128);
            $table->string('payment_flow', 32)->nullable();
            $table->string('payment', 64)->nullable();
            $table->string('payment_type', 32)->nullable();
            $table->text('pay_date')->nullable();
            $table->unsignedInteger('amount');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order');
    }
}
