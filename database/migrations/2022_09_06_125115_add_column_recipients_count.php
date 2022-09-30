<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRecipientsCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_meta', function (Blueprint $table) {
            if (!Schema::hasColumn('user_meta', 'recipients_count')) {
                Schema::table('user_meta', function (Blueprint $table) {
                    $table->integer('recipients_count')->unsigned()->default(0)->after('notifhistory')->comment('通訊人數');
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
        Schema::table('user_meta', function (Blueprint $table) {
            Schema::table('user_meta', function (Blueprint $table) {
                $table->dropColumn('recipients_count');
            });
        });
    }
}
