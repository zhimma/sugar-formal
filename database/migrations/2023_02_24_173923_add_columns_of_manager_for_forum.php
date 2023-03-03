<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddColumnsOfManagerForForum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('forum', 'hire_manager_quota')) {
            Schema::table('forum', function (Blueprint $table) {
                $table->integer('hire_manager_quota')->nullable()->after('is_warned');
            });
        } 
        
        DB::table('forum')->where('hire_manager_quota',0)->orWhereNull('hire_manager_quota')
        ->update(['hire_manager_quota'=>5]);
        
        if (!Schema::hasColumn('forum_manage', 'is_manager')) {
            Schema::table('forum_manage', function (Blueprint $table) {
                $table->boolean('is_manager')->default(0)->nullable()->after('active');
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
        if (Schema::hasColumn('forum', 'hire_manager_quota')) {
            Schema::table('forum', function (Blueprint $table) {
                $table->dropColumn('hire_manager_quota');
            }); 
        } 

        if (Schema::hasColumn('forum_manage', 'is_manager')) {
            Schema::table('forum_manage', function (Blueprint $table) {
                $table->dropColumn('is_manager');
            }); 
        }          
    }
}
