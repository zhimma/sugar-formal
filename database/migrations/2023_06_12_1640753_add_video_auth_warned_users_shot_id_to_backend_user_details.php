<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVideoAuthWarnedUsersShotIdToBackendUserDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('backend_user_details', function (Blueprint $table) {
            $table->integer('video_auth_warned_users_shot_id')->nullable()->after('is_need_video_verify');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('backend_user_details', function (Blueprint $table) {
            $table->dropColumn('video_auth_warned_users_shot_id');
        });
    }
}
