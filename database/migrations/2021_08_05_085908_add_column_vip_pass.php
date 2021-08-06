<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnVipPass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('banned_users', 'vip_pass')) {
            Schema::table('banned_users', function (Blueprint $table) {
                $table->smallInteger('vip_pass')->nullable()->default(0)->after('message_time');
            });
        }

        if (!Schema::hasColumn('warned_users', 'vip_pass')) {
            Schema::table('warned_users', function (Blueprint $table) {
                $table->smallInteger('vip_pass')->nullable()->default(0)->after('isAdminWarnedRead');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('banned_users', function (Blueprint $table) {
            $table->dropColumn('vip_pass');
        });

        Schema::table('warned_users', function (Blueprint $table) {
            $table->dropColumn('vip_pass');
        });
    }
}
