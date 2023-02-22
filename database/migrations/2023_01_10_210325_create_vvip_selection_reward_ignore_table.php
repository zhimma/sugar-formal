<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVvipSelectionRewardIgnoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('vvip_selection_reward_ignore')) {
            Schema::create('vvip_selection_reward_ignore', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id');
                $table->integer('vvip_selection_reward_id');
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
        Schema::dropIfExists('vvip_selection_reward_ignore');
    }
}
