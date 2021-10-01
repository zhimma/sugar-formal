<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnMemberVip extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('member_vip', 'remain_days')) {
            Schema::table('member_vip', function (Blueprint $table) {
                $table->integer('remain_days')->nullable()->default(0);
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
        Schema::table('member_vip', function (Blueprint $table) {
            //
            $table->dropColumn('remain_days');
        });
    }
}
