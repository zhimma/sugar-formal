<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCanMessageAlert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'can_message_alert')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->tinyInteger('can_message_alert')->unsigned()->default(1)->after('line_notify_alert')->comment('罐頭訊息警示設定,1:是（預設) 0:否');
                });
            }
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
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('can_message_alert');
            });
        });
    }
}
