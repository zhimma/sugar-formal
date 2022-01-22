<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsAdvAuthEmail extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('users', 'advance_auth_email')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('advance_auth_email',255)->nullable()->after('advance_auth_phone');
            });
        } 

        if (!Schema::hasColumn('users', 'advance_auth_email_token')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('advance_auth_email_token',255)->nullable()->after('advance_auth_email');
            });
        }  

        if (!Schema::hasColumn('users', 'advance_auth_email_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->timestamp('advance_auth_email_at')->nullable()->after('advance_auth_email_token');
            });
        }          
            
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('advance_auth_email');
        });        
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('advance_auth_email_token');
        }); 

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('advance_auth_email_at');
        });         
    }
}