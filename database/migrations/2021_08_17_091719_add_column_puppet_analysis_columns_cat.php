<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnPuppetAnalysisColumnsCat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('puppet_analysis_columns', 'cat')) {
            Schema::table('puppet_analysis_columns', function (Blueprint $table) {
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
        Schema::table('puppet_analysis_columns', function (Blueprint $table) {
            $table->dropColumn('cat');
        });
    }
}
