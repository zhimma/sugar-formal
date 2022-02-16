<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInboxRefuseSetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inbox_refuse_set', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->boolean('isRefused_vip_user')->default(0);
            $table->boolean('isRefused_common_user')->default(0);
            $table->boolean('isRefused_warned_user')->default(0);
            $table->integer('refuse_PR')->default(0);
            $table->integer('refuse_canned_message_PR')->default(0);
            $table->integer('refuse_register_days')->default(0);
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
        Schema::dropIfExists('inbox_refuse_set');
    }
}
