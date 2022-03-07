<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPuppetAnalysisIgnoresCfpid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('puppet_analysis_ignores', 'cfp_id')) {
            Schema::table('puppet_analysis_ignores', function (Blueprint $table) {
                $table->string('cfp_id',50)->default('')->after('ip');
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
            $table->dropColumn('cfp_id');
        });
    }
}
