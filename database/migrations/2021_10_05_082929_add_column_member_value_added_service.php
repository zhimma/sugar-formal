<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnMemberValueAddedService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('member_value_added_service', 'remain_days')) {
            Schema::table('member_value_added_service', function (Blueprint $table) {
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
        Schema::table('member_value_added_service', function (Blueprint $table) {
            //
            $table->dropColumn('remain_days');
        });
    }
}
