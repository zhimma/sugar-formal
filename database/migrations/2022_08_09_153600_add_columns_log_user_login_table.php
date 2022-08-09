<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsLogUserLoginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(!Schema::hasColumn('log_user_login', 'log_hide')) {
            Schema::table('log_user_login', function (Blueprint $table) {
                $table->tinyInteger('log_hide')->default(0)->after('created_at');

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
        if (Schema::hasColumn('log_user_login', 'log_hide')) {
            Schema::table('log_user_login', function (Blueprint $table) {
                $table->dropColumn('log_hide');
            });  
        }        

    }
}
