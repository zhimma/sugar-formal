<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsVvipUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('users', 'is_vvip')) {
            Schema::table('users', function (Blueprint $table) {
                $table->tinyInteger('is_vvip')->default(0)->after('hide_online_hide_time')->comment('0:false; 1:true');
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
        if (Schema::hasColumn('users', 'is_vvip')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('is_vvip');
            });
        }
    }
}
