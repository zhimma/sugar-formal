<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WarnedUsersUpdateReason extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('warned_users')->where('type', 'no_mobile_verify')->update(['reason' => '註冊未滿10天&電腦登入3次']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('warned_users')->where('type', 'no_mobile_verify')->update(['reason' => '尚未進行手機驗證']);
    }
}
