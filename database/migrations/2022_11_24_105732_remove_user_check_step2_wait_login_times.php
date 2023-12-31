<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\BackendUserDetails;

class RemoveUserCheckStep2WaitLoginTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('backend_user_details', function($table) {
            $table->boolean('is_waiting_for_more_data')->default(0)->after('user_id');
        });

        BackendUserDetails::where('user_check_step2_wait_login_times', '!=', 0)->update(['is_waiting_for_more_data' => 1]);

        Schema::table('backend_user_details', function($table) {
            $table->dropColumn('user_check_step2_wait_login_times');
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
            $table->dropColumn('is_waiting_for_more_data');
            $table->boolean('user_check_step2_wait_login_times')->default(0)->after('user_id');
        });
    }
}
