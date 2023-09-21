<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyRecommendSweetheart extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_recommend_sweetheart', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('sweetheart_type');
            $table->integer('truth_message_count')->nullable()->comment('人氣甜心真心話數量');
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
        Schema::dropIfExists('daily_recommend_sweetheart');
    }
}
