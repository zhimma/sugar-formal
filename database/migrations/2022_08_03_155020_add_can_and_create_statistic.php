<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCanAndCreateStatistic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message', function (Blueprint $table) {
            $table->boolean('is_can')->default(0)->after('content');
        });
        Schema::create('log_system_day_statistic', function (Blueprint $table) {
            $table->id();
            $table->timestamp('date')->nullable();
            $table->float('average_recipients_count_of_vip_male_senders')->nullable();
            $table->float('median_recipients_count_of_vip_male_senders')->nullable();
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
        Schema::table('message', function (Blueprint $table) {
            $table->dropColumn('is_can');
        });
        Schema::dropIfExists('log_system_day_statistic');
    }
}
