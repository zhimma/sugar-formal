<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnFirstLoginAfterVideoRecordVerifyToUserRecord extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_record', function (Blueprint $table) {
            $table->after('cost_time_of_first_dataprofile', function (Blueprint $table) {
                $table->boolean('first_login_after_video_record_verify')->default(0);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_record', function (Blueprint $table) {
            $table->dropColumn('first_login_after_video_record_verify');
        });
    }
}
