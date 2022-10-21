<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnShowCanMessage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users', 'show_can_message')) {
            Schema::table('users', function (Blueprint $table) {
                $table->tinyInteger('show_can_message')->unsigned()->default(1)->after('can_message_alert')->comment('顯示罐頭訊息,1:是（預設) 0:否');
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
        if (Schema::hasColumn('users', 'show_can_message')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('show_can_message');
            });
        }
    }
}
