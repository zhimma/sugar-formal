<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogFreeVipPicActsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_free_vip_pic_acts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('pic_type', 20);
            $table->string('user_operate', 100);
            $table->integer('img_remain_num'); 
            $table->string('sys_react', 100);
            $table->timestamp('shot_vip_record')->default('0000-00-00 00:00:00'); 
            $table->char('shot_is_free_vip', 1);
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_free_vip_pic_acts');
    }
}
