<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('message', 'views_count')) {
            DB::statement('ALTER TABLE `message` ADD `views_count` int(11) unsigned DEFAULT 0 AFTER `read`, ALGORITHM = INPLACE, LOCK=NONE;');
        }
        
        if (!Schema::hasColumn('message', 'views_count_quota')) {
            DB::statement('ALTER TABLE `message` ADD `views_count_quota` int(11) unsigned DEFAULT 0 AFTER `views_count`, ALGORITHM = INPLACE, LOCK=NONE;');
        }

        if (!Schema::hasColumn('message', 'show_time_limit')) {
            DB::statement('ALTER TABLE `message` ADD `show_time_limit` int(11) unsigned DEFAULT 0 AFTER `views_count_quota`, ALGORITHM = INPLACE, LOCK=NONE;');
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
        if (Schema::hasColumn('message', 'views_count')) {
            Schema::table('message', function (Blueprint $table) {
                $table->dropColumn('views_count');
            });
        }
        
        if (Schema::hasColumn('message', 'views_count_quota')) {
            Schema::table('message', function (Blueprint $table) {
                $table->dropColumn('views_count_quota');
            });
        }   

        if (Schema::hasColumn('message', 'show_time_limit')) {
            Schema::table('message', function (Blueprint $table) {
                $table->dropColumn('show_time_limit');
            });
        }   

    }
}
