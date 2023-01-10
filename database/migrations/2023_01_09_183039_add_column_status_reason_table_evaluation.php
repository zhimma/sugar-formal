<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnStatusReasonTableEvaluation extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('evaluation', 'status_reason')) {
            Schema::table('evaluation', function (Blueprint $table) {
                $table->string('status_reason',255)->nullable()->after('anonymous_content_status');
            });
        } 
    }

    public function down()
    {
        Schema::table('evaluation', function (Blueprint $table) {
            $table->dropColumn('status_reason');
        });            
    }
}