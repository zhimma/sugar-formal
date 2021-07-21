<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHideReportedLogColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function ($table) {
            $table->tinyInteger('notice_has_new_evaluation')->unsigned()->default(1)->after('isReadIntro')->comment('0:關閉提醒 1:開啟提醒(預設)');
        });

        Schema::table('reported', function ($table) {
            $table->tinyInteger('hide_reported_log')->unsigned()->default(0)->after('pic')->comment('是否顯示檢舉紀錄 1: 不顯示, 0: 顯示(預設)');
        });

        Schema::table('reported_avatar', function ($table) {
            $table->tinyInteger('hide_reported_log')->unsigned()->default(0)->after('pic')->comment('是否顯示檢舉紀錄 1: 不顯示, 0: 顯示(預設) ');
        });

        Schema::table('reported_pic', function ($table) {
            $table->tinyInteger('hide_reported_log')->unsigned()->default(0)->after('cancel')->comment('是否顯示檢舉紀錄 1: 不顯示, 0: 顯示(預設)');
        });

        Schema::table('message', function ($table) {
            $table->tinyInteger('hide_reported_log')->unsigned()->default(0)->after('sys_notice')->comment('是否顯示檢舉紀錄 1: 不顯示, 0: 顯示(預設)');
        });

        Schema::table('evaluation', function ($table) {
            $table->tinyInteger('hide_evaluation_to_id')->unsigned()->default(0)->after('admin_comment')->comment('是否顯示被評價紀錄 1: 不顯示, 0: 顯示(預設)');
        });

        Schema::table('member_fav', function ($table) {
            $table->tinyInteger('hide_member_id_log')->unsigned()->default(0)->after('member_fav_id')->comment('是否顯示收藏紀錄 1: 不顯示, 0: 顯示(預設)');
            $table->tinyInteger('hide_member_fav_id_log')->unsigned()->default(0)->after('hide_member_id_log')->comment('是否顯示被收藏紀錄 1: 不顯示, 0: 顯示(預設)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('notice_has_new_evaluation');
        });

        Schema::table('reported', function ($table) {
            $table->dropColumn('hide_reported_log');
        });

        Schema::table('reported_avatar', function ($table) {
            $table->dropColumn('hide_reported_log');
        });

        Schema::table('reported_pic', function ($table) {
            $table->dropColumn('hide_reported_log');
        });

        Schema::table('message', function ($table) {
            $table->dropColumn('hide_reported_log');
        });

        Schema::table('evaluation', function ($table) {
            $table->dropColumn('hide_evaluation_to_id');
        });

        Schema::table('member_fav', function ($table) {
            $table->dropColumn('hide_member_id_log');
            $table->dropColumn('hide_member_fav_id_log');
        });
    }
}
