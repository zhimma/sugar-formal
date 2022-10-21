<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsAdminChatChannelOpen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users', 'is_admin_chat_channel_open')) {
            Schema::table('users', function (Blueprint $table) {
                $table->tinyInteger('is_admin_chat_channel_open')->unsigned()->default(0)->after('is_real')->comment('開啟會員對話,1:是 0:否（預設)');
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
        if (Schema::hasColumn('users', 'is_admin_chat_channel_open')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_admin_chat_channel_open');
            });
        }
    }
}
