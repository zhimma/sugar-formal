<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SuspiciousListCheck extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('queue_global_variables')->insert(
            array(
                'name' => 'suspicious_list_communication_weekly_count_set',
                'type' => 'int',
                'value' => '0',
            )
        );

        DB::table('queue_global_variables')->insert(
            array(
                'name' => 'suspicious_list_communication_country_count_set',
                'type' => 'int',
                'value' => '0',
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('queue_global_variables')->where('name','suspicious_list_communication_weekly_count_set')->delete();
        DB::table('queue_global_variables')->where('name','suspicious_list_communication_country_count_set')->delete();
    }
}
