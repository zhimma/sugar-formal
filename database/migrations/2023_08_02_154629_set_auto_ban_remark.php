<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetAutoBanRemark extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('set_auto_ban', function (Blueprint $table) {
            $table->after('host', function (Blueprint $table) {
                $table->string('remark')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('set_auto_ban', function (Blueprint $table) {
            $table->dropColumn('remark');
        });
    }
}
