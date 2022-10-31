<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnNormalCount extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('special_industries_test_topic', 'normal_count')) {
            Schema::table('special_industries_test_topic', function (Blueprint $table) {
                $table->integer('normal_count')->default(0)->after('topic_count')->comment('正常會員數量');
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
        if (Schema::hasColumn('special_industries_test_topic', 'normal_count')) {
            Schema::table('special_industries_test_topic', function (Blueprint $table) {
                $table->dropColumn('normal_count');
            });
        }
    }
}
