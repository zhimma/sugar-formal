<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPageId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('stay_online_record', 'newer_manual')) {
            DB::statement('ALTER TABLE `stay_online_record` ADD `newer_manual` int(11) unsigned DEFAULT 0 AFTER `stay_online_time`, ALGORITHM = INPLACE, LOCK=NONE;');
        }
        if (!Schema::hasColumn('stay_online_record', 'browse')) {
            DB::statement('ALTER TABLE `stay_online_record` ADD `browse` int(11) unsigned DEFAULT 0 AFTER `stay_online_time`, ALGORITHM = INPLACE, LOCK=NONE;');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        if (Schema::hasColumn('stay_online_record', 'newer_manual')) {
            Schema::table('stay_online_record', function (Blueprint $table) {
                $table->dropColumn('newer_manual');
            });
        }
        if (Schema::hasColumn('stay_online_record', 'browse')) {
            Schema::table('stay_online_record', function (Blueprint $table) {
                $table->dropColumn('browse');
            });
        }
    }
}
