<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HideTopic extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('special_industries_test_topic', 'is_hide')) {
            Schema::table('special_industries_test_topic', function (Blueprint $table) {
                $table->boolean('is_hide')->default(false)->after('topic_count');
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
        if (Schema::hasColumn('special_industries_test_topic', 'is_hide')) {
            Schema::table('special_industries_test_topic', function (Blueprint $table) {
                $table->dropColumn('is_hide');
            });
        }
    }
}
