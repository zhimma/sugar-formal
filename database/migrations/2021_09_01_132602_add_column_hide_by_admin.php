<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnHideByAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message_board', function (Blueprint $table) {
            $table->tinyInteger('hide_by_admin')->unsigned()->default(0)->after('contents')->comment('是否顯示留言 1: 不顯示, 0: 顯示(預設)');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('message_board', function (Blueprint $table) {
            $table->dropColumn('hide_by_admin');
        });
    }
}
