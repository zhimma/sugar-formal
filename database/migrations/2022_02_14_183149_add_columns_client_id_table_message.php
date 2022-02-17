<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsClientIdTableMessage extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('message', 'client_id')) {
            Schema::table('message', function (Blueprint $table) {
                $table->string('client_id',50)->nullable()->after('id')->index();
            });
        } 

        if (Schema::hasColumn('message', 'parent_msg')) {
            Schema::table('message', function (Blueprint $table) {
                $table->index('parent_msg');
            });
            
            if (!Schema::hasColumn('message', 'parent_client_id')) {
                Schema::table('message', function (Blueprint $table) {
                    $table->string('parent_client_id',50)->nullable()->after('parent_msg')->index();
                });
            }               
        }  
        
     

    }

    public function down()
    {
        Schema::table('message', function (Blueprint $table) {
            $table->dropColumn('client_id');
        });   

        Schema::table('message', function (Blueprint $table) {
            $table->dropColumn('parent_client_id');
        });           
    }
}