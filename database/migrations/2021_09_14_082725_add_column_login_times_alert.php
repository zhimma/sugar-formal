<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnLoginTimesAlert extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admin_announcement', function (Blueprint $table) {
            $table->integer('login_times_alert')->nullable()->after('isVip');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admin_announcement', function (Blueprint $table) {
            $table->dropColumn('login_times_alert');

        });
    }
}
