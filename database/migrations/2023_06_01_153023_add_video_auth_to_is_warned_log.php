<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVideoAuthToIsWarnedLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('is_warned_log', function (Blueprint $table) {
            $table->boolean('video_auth')->nullable()->default(0)->after('adv_auth');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('is_warned_log', function (Blueprint $table) {
            $table->dropColumn('video_auth');
        });
    }
}
