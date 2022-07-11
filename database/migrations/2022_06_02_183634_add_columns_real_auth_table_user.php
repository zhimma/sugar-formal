<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsRealAuthTableUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('users', 'self_auth_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('self_auth_status')->nullable();
            });
        } 
/*
        if (!Schema::hasColumn('users', 'self_auth_apply_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('self_auth_apply_id')->nullable()->index();
            });
        } 

        if (!Schema::hasColumn('users', 'self_auth_modify_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('self_auth_modify_id')->nullable()->index();
            });
        }      */    


        if (!Schema::hasColumn('users', 'beauty_auth_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('beauty_auth_status')->nullable();
            });
        } 
        /*
        if (!Schema::hasColumn('users', 'beauty_auth_apply_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('beauty_auth_apply_id')->nullable()->index();
            });
        }  
        */
        if (!Schema::hasColumn('users', 'famous_auth_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('famous_auth_status')->nullable();
            });
        } 
/*
        if (!Schema::hasColumn('users', 'famous_auth_apply_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->integer('famous_auth_apply_id')->nullable()->index();
            });
        }  
*/        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        if (Schema::hasColumn('users', 'self_auth_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('self_auth_status');
            });  
        }
/*        
        if (Schema::hasColumn('users', 'self_auth_apply_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('self_auth_apply_id');
            });  
        }  
*/

        if (Schema::hasColumn('users', 'beauty_auth_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('beauty_auth_status');
            });  
        }
        /*
        if (Schema::hasColumn('users', 'beauty_auth_apply_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('beauty_auth_apply_id');
            });  
        }  
*/

        if (Schema::hasColumn('users', 'famous_auth_status')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('famous_auth_status');
            });  
        }
        /*
        if (Schema::hasColumn('users', 'famous_auth_apply_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('famous_auth_apply_id');
            });  
        }  
*/        
    }
}
