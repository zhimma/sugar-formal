<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToLogSystemDayStatistic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('log_system_day_statistic', function (Blueprint $table) {
            $table->integer('number_of_female_registrants')->after('median_recipients_count_of_vip_male_senders');
            $table->integer('number_of_male_registrants')->after('median_recipients_count_of_vip_male_senders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_system_day_statistic', function (Blueprint $table) {
            $table->dropColumn('number_of_female_registrants');
            $table->dropColumn('number_of_male_registrants');
        });
    }
}
