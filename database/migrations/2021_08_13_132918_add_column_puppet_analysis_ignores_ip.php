<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPuppetAnalysisIgnoresIp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('puppet_analysis_ignores', 'ip')) {
            Schema::table('puppet_analysis_ignores', function (Blueprint $table) {
                $table->string('ip',50)->default('')->after('item');
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
        //
        Schema::table('puppet_analysis_ignores', function (Blueprint $table) {
            $table->dropColumn('ip');
        });
    }
}
