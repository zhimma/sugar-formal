<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVisitorIdColumnForFindpuppetTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('puppet_analysis_ignores', 'visitor_id')) {
            Schema::table('puppet_analysis_ignores', function (Blueprint $table) {
                $table->string('visitor_id',50)->nullable()->default('')->after('cfp_id');
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
        if (Schema::hasColumn('puppet_analysis_ignores', 'visitor_id')) {
            Schema::table('puppet_analysis_ignores', function (Blueprint $table) {
                $table->dropColumn('visitor_id');
            });
        }
    }
}
