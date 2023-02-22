<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVvipSelectionRewardApplyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vvip_selection_reward_apply', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('vvip_selection_reward_id');
            $table->tinyInteger('status')->default(0)->comment('0:申請中; 1:通過; 2:不通過;');
            $table->text('note')->nullable()->comment('管理者備註用');
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
        Schema::dropIfExists('vvip_selection_reward_apply');
    }
}
