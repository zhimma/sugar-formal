<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnChatWithAdmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('message', function (Blueprint $table) {
            if (!Schema::hasColumn('message', 'chat_with_admin')) {
                Schema::table('message', function (Blueprint $table) {
                    $table->tinyInteger('chat_with_admin')->unsigned()->default(0)->after('sys_notice')->comment('站長站內溝通,1:是 0:否（預設)');
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
        Schema::table('message', function (Blueprint $table) {
            Schema::table('message', function (Blueprint $table) {
                $table->dropColumn('chat_with_admin');
            });
        });
    }
}
