<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAdvAuth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('banned_users', 'adv_auth')) {
            Schema::table('banned_users', function (Blueprint $table) {
                $table->smallInteger('adv_auth')->nullable()->default(0)->after('vip_pass');
            });
        }

        if (!Schema::hasColumn('warned_users', 'adv_auth')) {
            Schema::table('warned_users', function (Blueprint $table) {
                $table->smallInteger('adv_auth')->nullable()->default(0)->after('vip_pass');
            });
        }
        
        if (!Schema::hasColumn('is_banned_log', 'adv_auth')) {
            Schema::table('is_banned_log', function (Blueprint $table) {
                $table->smallInteger('adv_auth')->nullable()->default(0)->after('vip_pass');
            });
        }

        if (!Schema::hasColumn('is_warned_log', 'adv_auth')) {
            Schema::table('is_warned_log', function (Blueprint $table) {
                $table->smallInteger('adv_auth')->nullable()->default(0)->after('vip_pass');
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
            $table->dropColumn('adv_auth');
        });

        Schema::table('warned_users', function (Blueprint $table) {
            $table->dropColumn('adv_auth');
        });
        
        Schema::table('is_banned_log', function (Blueprint $table) {
            $table->dropColumn('adv_auth');
        });

        Schema::table('is_warned_log', function (Blueprint $table) {
            $table->dropColumn('adv_auth');
        });        
    }
}
