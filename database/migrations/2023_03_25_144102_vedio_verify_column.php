<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VedioVerifyColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('video_verify_auth_status');
        });
        
        Schema::table('warned_users', function (Blueprint $table) {
            $table->boolean('video_auth')->after('adv_auth')->nullable()->default(0);
        });

        Schema::table('backend_user_details', function (Blueprint $table) {
            $table->after('remain_login_times_of_wait_for_more_data', function (Blueprint $table) {
                $table->boolean('is_need_video_verify')->default(0);
                $table->integer('video_verify_fail_count')->default(0);
                $table->timestamp('need_video_verify_date')->nullable();
                $table->integer('login_times_after_need_video_verify_date')->default(0);
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('video_verify_auth_status');
        });
        
        Schema::table('warned_users', function (Blueprint $table) {
            $table->dropColumn('video_auth');
        });

        Schema::table('backend_user_details', function (Blueprint $table) {
            $table->dropColumn('is_need_video_verify');
            $table->dropColumn('video_verify_fail_count');
            $table->dropColumn('need_video_verify_date');
            $table->dropColumn('login_times_after_need_video_verify_date');
        });
    }
}
