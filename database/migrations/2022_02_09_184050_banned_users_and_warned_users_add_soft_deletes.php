<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BannedUsersAndWarnedUsersAddSoftDeletes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if(!Schema::hasColumn('banned_users', 'deleted_at')){
            Schema::table('banned_users', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
        if(!Schema::hasColumn('warned_users', 'deleted_at')){
            Schema::table('warned_users', function (Blueprint $table) {
                $table->softDeletes();
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
    }
}
