<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTopicCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('special_industries_test_topic', 'topic_count')) {
            Schema::table('special_industries_test_topic', function (Blueprint $table) {
                $table->integer('topic_count')->default(0)->after('test_setup_id')->comment('題目數量');
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
        if (Schema::hasColumn('special_industries_test_topic', 'topic_count')) {
            Schema::table('special_industries_test_topic', function (Blueprint $table) {
                $table->dropColumn('topic_count');
            });
        }
    }
}
