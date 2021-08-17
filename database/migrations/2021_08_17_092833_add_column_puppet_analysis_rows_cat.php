<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPuppetAnalysisRowsCat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('puppet_analysis_rows', 'cat')) {
            Schema::table('puppet_analysis_rows', function (Blueprint $table) {
                $table->string('cat',50)->default('')->after('id');
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
        Schema::table('puppet_analysis_rows', function (Blueprint $table) {
            $table->dropColumn('cat');
        });
    }
}
