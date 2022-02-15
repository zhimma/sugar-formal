<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWarnedDays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //新增天數欄位
        Schema::table('set_auto_ban', function (Blueprint $table) {
            //加入expired_days欄位到expiry欄位後方
            $table->integer('expired_days')->after('expiry');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('set_auto_ban', function (Blueprint $table) {
            $table->dropColumn('expired_days');
        });
    }
}
