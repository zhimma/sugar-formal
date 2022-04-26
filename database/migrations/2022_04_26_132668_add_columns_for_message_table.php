<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForMessageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('message', 'views_count')) {
            Schema::table('message', function (Blueprint $table) {
                $table->integer('views_count')->default(0)->after('read');
            });
        }
        
        if (!Schema::hasColumn('message', 'views_count_quota')) {
            Schema::table('message', function (Blueprint $table) {
                $table->integer('views_count_quota')->default(0)->after('views_count');
            });
        }

        if (!Schema::hasColumn('message', 'show_time_limit')) {
            Schema::table('message', function (Blueprint $table) {
                $table->integer('show_time_limit')->default(0)->after('views_count_quota');
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
        if (Schema::hasColumn('message', 'views_count')) {
            Schema::table('message', function (Blueprint $table) {
                $table->dropColumn('views_count');
            });
        }
        
        if (Schema::hasColumn('message', 'views_count_quota')) {
            Schema::table('message', function (Blueprint $table) {
                $table->dropColumn('views_count_quota');
            });
        }   

        if (Schema::hasColumn('message', 'show_time_limit')) {
            Schema::table('message', function (Blueprint $table) {
                $table->dropColumn('show_time_limit');
            });
        }   

    }
}
