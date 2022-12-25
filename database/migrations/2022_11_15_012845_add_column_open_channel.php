<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOpenChannel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('anonymous_evaluation_chats', 'open_channel')) {
            Schema::table('anonymous_evaluation_chats', function (Blueprint $table) {
                $table->tinyInteger('open_channel')->unsigned()->default(0)->after('status')->comment('開啟匿名評價溝通,1:是 0:否（預設)');
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
        if (Schema::hasColumn('anonymous_evaluation_chats', 'open_channel')) {
            Schema::table('anonymous_evaluation_chats', function (Blueprint $table) {
                $table->dropColumn('open_channel');
            });
        }
    }
}
