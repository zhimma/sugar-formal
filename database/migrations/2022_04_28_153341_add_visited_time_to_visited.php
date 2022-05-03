<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVisitedTimeToVisited extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('ALTER TABLE `visited` ADD `visited_time` int(11) unsigned DEFAULT NULL AFTER `visited_id`, ALGORITHM = INPLACE, LOCK=NONE;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('visited', function (Blueprint $table) {
            $table->dropColumn('visited_time');
        });
    }
}
