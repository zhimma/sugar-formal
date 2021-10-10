<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVipPass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('is_banned_log', 'vip_pass')) {
            Schema::table('is_banned_log', function (Blueprint $table) {
                $table->smallInteger('vip_pass')->nullable()->default(0)->after('expire_date');
            });
        }

        if (!Schema::hasColumn('is_warned_log', 'vip_pass')) {
            Schema::table('is_warned_log', function (Blueprint $table) {
                $table->smallInteger('vip_pass')->nullable()->default(0)->after('reason');
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
        Schema::table('is_banned_log', function (Blueprint $table) {
            $table->dropColumn('vip_pass');
        });

        Schema::table('is_warned_log', function (Blueprint $table) {
            $table->dropColumn('vip_pass');
        });
    }
}
