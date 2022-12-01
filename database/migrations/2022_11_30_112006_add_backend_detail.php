<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBackendDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('backend_user_details', function($table) {
            $table->integer('remain_login_times_of_wait_for_more_data')->default(0)->after('is_waiting_for_more_data');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('backend_user_details', function($table) {
            $table->dropColumn('remain_login_times_of_wait_for_more_data');
        });
    }
}
