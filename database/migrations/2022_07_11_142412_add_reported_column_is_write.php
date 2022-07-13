<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReportedColumnIsWrite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reported', function (Blueprint $table) {
            $table->unsignedTinyInteger('is_write')->default(0)->comment('route: admin/users/reported 的紀錄是否點擊過撰寫 1 為點擊過 0 為尚未點擊過');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reported', function (Blueprint $table) {
            $table->dropColumn('is_write');
        });
    }
}
